<?php
	$time_start = microtime(true);

	$video_id = $_GET['video_id'];

	if(!array_key_exists('video_id', $_GET))
		die('Nah man, video_id is not found ese');

	$v_info = array();
	$operators = "";

	function decipherSignature($signature, $operators)
	{
		$op = explode(';', $operators);
		$s = $signature;
		
		foreach($op as $o)
		{
			$temp = explode('=', $o);
			if($temp[0] === "reverse")
				$s = strrev($s);
			else if($temp[0] === "splice")
				$s = substr($s, $temp[1]);
			else if($temp[0] === "swap")
			{
				$c = $s[0];
				$s[0] = $s[$temp[1] % strlen($s)];
				$s[$temp[1]] = $c;
			}
			else if($temp[0] === "ret")
				return $s;
		}
	}

	//Get player js
	{
		$functionPattern 		= "/#NAME#=function\([^\)]+\){.*?};/";
		$jsFilePattern 			= "/(?<JavascriptFile>s.ytimg.com\/yts\/jsbin\/player-([\w\d\-]+)\/base.js)/";
		$ytPlayerConfigPattern 	= "/ytplayer\.config\s*=\s*(\{.+?\});/";
		$sigFunctionPattern		= "/.sig\|\|(?<FunctionName>[a-zA-Z0-9$]+)/";
		$functioNamePattern		= "/;(?<HelperObjectName>[a-zA-Z0-9]+)\.(?<HelperFunctionName>[A-Za-z0-9]+)/";
		$helperFunctionPattern2 = "/var #NAME#={([^;]*)(.*?)};/";

		//Get the video page
		$video_page = file_get_contents('https://youtube.com/watch?v=' . $video_id);
		preg_match($jsFilePattern, $video_page, $js_pattern);

		preg_match_all($ytPlayerConfigPattern, $video_page, $ytplayer);

		//Process the javascript
		{
			$js_url = "https://" . $js_pattern['JavascriptFile'];
			$js_header = get_headers($js_url, true);

			$cache_file = ("./cache/" . md5($js_header['Last-Modified']) . ".cache");

			if(!file_exists($cache_file))
			{
				//Search the javascript for the decipher function and for the helper object
				{
					$js_content = file_get_contents($js_url);
					
					preg_match_all($sigFunctionPattern, $js_content, $sig_function);
					preg_match(str_replace('#NAME#', $sig_function['FunctionName'][1], $functionPattern), $js_content, $decipher_function);

					preg_match_all($functioNamePattern, $decipher_function[0], $function_pattern);

					$search = str_replace("#NAME#", $function_pattern['HelperObjectName'][0], $helperFunctionPattern2);
					preg_match($search, $js_content, $helper_object);

					preg_match('/(?<HFunction>\w+):\bfunction\b\(\w+\)/', $helper_object[0], $reverse_function);
					preg_match('/(?<HFunction>\w+):\bfunction\b\([a],b\).(\breturn\b)?.?\w+\./', $helper_object[0], $splice_function);
					preg_match('/(?<HFunction>\w+):\bfunction\b\(\w+\,\w\).\bvar\b.\bc=a\b/', $helper_object[0], $swap_function);
					preg_match('/=function\(\w+\){(?<TheFunction>.*?)}/', $decipher_function[0], $the_function);

					$reverse_f = $reverse_function['HFunction'];
					$splice_f = $splice_function['HFunction'];
					$swap_f = $swap_function['HFunction'];

					$operators = "";
					foreach(explode(';', $the_function['TheFunction']) as $t)
					{
						preg_match('/\w+\(.*?\,(?<B>.*?)\)/', $t, $ret_param);
						if(strpos($t, $reverse_f) !== false)
							$operators[] = "reverse=" . $ret_param['B'];
						else if(strpos($t, $splice_f) !== false)
							$operators[] = "splice=" . $ret_param['B'];
						else if(strpos($t, $swap_f) !== false)
							$operators[] = "swap=" . $ret_param['B'];
						else if(strpos($t, "return") !== false)
							$operators[] = "ret";
					}
					$operators = implode(';', $operators);
				}

				//Cache the operators in a file so we don't need to go through the whole procedure again. (see above)
				{
					$file_handle = fopen($cache_file, "w");

					if($file_handle)
						fwrite($file_handle, $operators);

					fclose($file_handle);
				}
			} else
			{
				$file_handle = fopen($cache_file, "r");

				if($file_handle)
				{
					$operators = "";
					$operators = fread($file_handle, filesize($cache_file));
				}

				fclose($file_handle);
			}
		}
	}
	
	//Parse video info
	{
		$video_info = json_decode($ytplayer[1][0]);
		$video_array = $video_info->args;
		
		$v_info['info'] = array('title' => $video_array->title, 'image' => 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg');

		$loop = array('url_encoded_fmt_stream_map' => 'video', 'adaptive_fmts' => 'video_audio');
		foreach($loop as $l => $k)
		{
			foreach(explode(',', $video_array->$l) as $uefsm)
			{
				parse_str($uefsm, $video_info_array);
				parse_str($video_info_array['url'], $v_url_info);

				if(!array_key_exists('signature', $v_url_info))
					$video_info_array['url'] = urldecode($video_info_array['url']) . "&signature=" . decipherSignature($video_info_array['s'], $operators);

				$v_info[$k][] = $video_info_array;
			}
		}
	}

	$time_end = microtime(true);
	$time = $time_end - $time_start;
	//echo "Process Time: {$time}";

	echo json_encode($v_info);
?>