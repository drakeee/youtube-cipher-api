<!DOCTYPE html>
<html>
	<head>
		<title>Teszt!</title>
	</head>
	<body>
		<?php
			$y_video = file_get_contents('http://localhost/yt_dl_api.php?video_id=' . $_GET['video_id']);
			$y_video = json_decode($y_video);

			echo '<img src="' . $y_video->info->image . '" width="10%" /> </br>';
			foreach($y_video->video_audio as $video)
			{
				$format_info = file_get_contents('http://localhost:4008/yt/yt_itag_api.php?itag=' . $video->itag);
				$format_info = json_decode($format_info);
				$format_info->format_note = str_replace('DASH ', '', $format_info->format_note);

				$format_s = "";
				if($format_info->format_note === "video")
					$format_s = $format_info->format_note . ' (' . $format_info->ext . ', ' . $format_info->height . 'p)';
				else if($format_info->format_note === "audio")
					$format_s = $format_info->format_note . ' (' . $format_info->ext . ', ' . $format_info->abr . ' kbps)';

				echo '<a href="' . $video->url . '">' . $format_s . '</a><br/>';
			}
		?>
	</body>
</html>