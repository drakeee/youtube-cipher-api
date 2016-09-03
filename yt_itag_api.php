<?php
	if(!array_key_exists('itag', $_GET))
		die('Nah man, itag is not found ese');

	$itag = $_GET['itag'];

	$_formats = array(
		'5' => array('ext' => 'flv', 'width' => 400, 'height' => 240, 'acodec' => 'mp3', 'abr' => 64, 'vcodec' => 'h263'),
		'6' => array('ext' => 'flv', 'width' => 450, 'height' => 270, 'acodec' => 'mp3', 'abr' => 64, 'vcodec' => 'h263'),
		'13' => array('ext' => '3gp', 'acodec' => 'aac', 'vcodec' => 'mp4v'),
		'17' => array('ext' => '3gp', 'width' => 176, 'height' => 144, 'acodec' => 'aac', 'abr' => 24, 'vcodec' => 'mp4v'),
		'18' => array('ext' => 'mp4', 'width' => 640, 'height' => 360, 'acodec' => 'aac', 'abr' => 96, 'vcodec' => 'h264'),
		'22' => array('ext' => 'mp4', 'width' => 1280, 'height' => 720, 'acodec' => 'aac', 'abr' => 192, 'vcodec' => 'h264'),
		'34' => array('ext' => 'flv', 'width' => 640, 'height' => 360, 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264'),
		'35' => array('ext' => 'flv', 'width' => 854, 'height' => 480, 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264'),
		# itag 36 videos are either 320x180 (BaW_jenozKc) or 320x240 (__2ABJjxzNo), abr varies as well
		'36' => array('ext' => '3gp', 'width' => 320, 'acodec' => 'aac', 'vcodec' => 'mp4v'),
		'37' => array('ext' => 'mp4', 'width' => 1920, 'height' => 1080, 'acodec' => 'aac', 'abr' => 192, 'vcodec' => 'h264'),
		'38' => array('ext' => 'mp4', 'width' => 4096, 'height' => 3072, 'acodec' => 'aac', 'abr' => 192, 'vcodec' => 'h264'),
		'43' => array('ext' => 'webm', 'width' => 640, 'height' => 360, 'acodec' => 'vorbis', 'abr' => 128, 'vcodec' => 'vp8'),
		'44' => array('ext' => 'webm', 'width' => 854, 'height' => 480, 'acodec' => 'vorbis', 'abr' => 128, 'vcodec' => 'vp8'),
		'45' => array('ext' => 'webm', 'width' => 1280, 'height' => 720, 'acodec' => 'vorbis', 'abr' => 192, 'vcodec' => 'vp8'),
		'46' => array('ext' => 'webm', 'width' => 1920, 'height' => 1080, 'acodec' => 'vorbis', 'abr' => 192, 'vcodec' => 'vp8'),
		'59' => array('ext' => 'mp4', 'width' => 854, 'height' => 480, 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264'),
		'78' => array('ext' => 'mp4', 'width' => 854, 'height' => 480, 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264'),


		# 3D videos
		'82' => array('ext' => 'mp4', 'height' => 360, 'format_note' => '3D', 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264', 'preference' => -20),
		'83' => array('ext' => 'mp4', 'height' => 480, 'format_note' => '3D', 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264', 'preference' => -20),
		'84' => array('ext' => 'mp4', 'height' => 720, 'format_note' => '3D', 'acodec' => 'aac', 'abr' => 192, 'vcodec' => 'h264', 'preference' => -20),
		'85' => array('ext' => 'mp4', 'height' => 1080, 'format_note' => '3D', 'acodec' => 'aac', 'abr' => 192, 'vcodec' => 'h264', 'preference' => -20),
		'100' => array('ext' => 'webm', 'height' => 360, 'format_note' => '3D', 'acodec' => 'vorbis', 'abr' => 128, 'vcodec' => 'vp8', 'preference' => -20),
		'101' => array('ext' => 'webm', 'height' => 480, 'format_note' => '3D', 'acodec' => 'vorbis', 'abr' => 192, 'vcodec' => 'vp8', 'preference' => -20),
		'102' => array('ext' => 'webm', 'height' => 720, 'format_note' => '3D', 'acodec' => 'vorbis', 'abr' => 192, 'vcodec' => 'vp8', 'preference' => -20),

		# Apple HTTP Live Streaming
		'91' => array('ext' => 'mp4', 'height' => 144, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 48, 'vcodec' => 'h264', 'preference' => -10),
		'92' => array('ext' => 'mp4', 'height' => 240, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 48, 'vcodec' => 'h264', 'preference' => -10),
		'93' => array('ext' => 'mp4', 'height' => 360, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264', 'preference' => -10),
		'94' => array('ext' => 'mp4', 'height' => 480, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 128, 'vcodec' => 'h264', 'preference' => -10),
		'95' => array('ext' => 'mp4', 'height' => 720, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 256, 'vcodec' => 'h264', 'preference' => -10),
		'96' => array('ext' => 'mp4', 'height' => 1080, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 256, 'vcodec' => 'h264', 'preference' => -10),
		'132' => array('ext' => 'mp4', 'height' => 240, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 48, 'vcodec' => 'h264', 'preference' => -10),
		'151' => array('ext' => 'mp4', 'height' => 72, 'format_note' => 'HLS', 'acodec' => 'aac', 'abr' => 24, 'vcodec' => 'h264', 'preference' => -10),

		# DASH mp4 video
		'133' => array('ext' => 'mp4', 'height' => 240, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'134' => array('ext' => 'mp4', 'height' => 360, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'135' => array('ext' => 'mp4', 'height' => 480, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'136' => array('ext' => 'mp4', 'height' => 720, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'137' => array('ext' => 'mp4', 'height' => 1080, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'138' => array('ext' => 'mp4', 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),  # Height can vary (https =>//github.com/rg3/youtube-dl/issues/4559)
		'160' => array('ext' => 'mp4', 'height' => 144, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'264' => array('ext' => 'mp4', 'height' => 1440, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),
		'298' => array('ext' => 'mp4', 'height' => 720, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'fps' => 60, 'preference' => -40),
		'299' => array('ext' => 'mp4', 'height' => 1080, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'fps' => 60, 'preference' => -40),
		'266' => array('ext' => 'mp4', 'height' => 2160, 'format_note' => 'DASH video', 'vcodec' => 'h264', 'preference' => -40),

		# Dash mp4 audio
		'139' => array('ext' => 'm4a', 'format_note' => 'DASH audio', 'acodec' => 'aac', 'abr' => 48, 'preference' => -50, 'container' => 'm4a_dash'),
		'140' => array('ext' => 'm4a', 'format_note' => 'DASH audio', 'acodec' => 'aac', 'abr' => 128, 'preference' => -50, 'container' => 'm4a_dash'),
		'141' => array('ext' => 'm4a', 'format_note' => 'DASH audio', 'acodec' => 'aac', 'abr' => 256, 'preference' => -50, 'container' => 'm4a_dash'),
		'256' => array('ext' => 'm4a', 'format_note' => 'DASH audio', 'acodec' => 'aac', 'preference' => -50, 'container' => 'm4a_dash'),
		'258' => array('ext' => 'm4a', 'format_note' => 'DASH audio', 'acodec' => 'aac', 'preference' => -50, 'container' => 'm4a_dash'),

		# Dash webm
		'167' => array('ext' => 'webm', 'height' => 360, 'width' => 640, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'168' => array('ext' => 'webm', 'height' => 480, 'width' => 854, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'169' => array('ext' => 'webm', 'height' => 720, 'width' => 1280, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'170' => array('ext' => 'webm', 'height' => 1080, 'width' => 1920, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'218' => array('ext' => 'webm', 'height' => 480, 'width' => 854, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'219' => array('ext' => 'webm', 'height' => 480, 'width' => 854, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp8', 'preference' => -40),
		'278' => array('ext' => 'webm', 'height' => 144, 'format_note' => 'DASH video', 'container' => 'webm', 'vcodec' => 'vp9', 'preference' => -40),
		'242' => array('ext' => 'webm', 'height' => 240, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'243' => array('ext' => 'webm', 'height' => 360, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'244' => array('ext' => 'webm', 'height' => 480, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'245' => array('ext' => 'webm', 'height' => 480, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'246' => array('ext' => 'webm', 'height' => 480, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'247' => array('ext' => 'webm', 'height' => 720, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'248' => array('ext' => 'webm', 'height' => 1080, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'271' => array('ext' => 'webm', 'height' => 1440, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		# itag 272 videos are either 3840x2160 (e.g. RtoitU2A-3E) or 7680x4320 (sLprVF6d7Ug)
		'272' => array('ext' => 'webm', 'height' => 2160, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'302' => array('ext' => 'webm', 'height' => 720, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'fps' => 60, 'preference' => -40),
		'303' => array('ext' => 'webm', 'height' => 1080, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'fps' => 60, 'preference' => -40),
		'308' => array('ext' => 'webm', 'height' => 1440, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'fps' => 60, 'preference' => -40),
		'313' => array('ext' => 'webm', 'height' => 2160, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'preference' => -40),
		'315' => array('ext' => 'webm', 'height' => 2160, 'format_note' => 'DASH video', 'vcodec' => 'vp9', 'fps' => 60, 'preference' => -40),

		# Dash webm audio
		'171' => array('ext' => 'webm', 'acodec' => 'vorbis', 'format_note' => 'DASH audio', 'abr' => 128, 'preference' => -50),
		'172' => array('ext' => 'webm', 'acodec' => 'vorbis', 'format_note' => 'DASH audio', 'abr' => 256, 'preference' => -50),

		# Dash webm audio with opus inside
		'249' => array('ext' => 'webm', 'format_note' => 'DASH audio', 'acodec' => 'opus', 'abr' => 50, 'preference' => -50),
		'250' => array('ext' => 'webm', 'format_note' => 'DASH audio', 'acodec' => 'opus', 'abr' => 70, 'preference' => -50),
		'251' => array('ext' => 'webm', 'format_note' => 'DASH audio', 'acodec' => 'opus', 'abr' => 160, 'preference' => -50),

		# RTMP (unnamed)
		'_rtmp' => array('protocol' => 'rtmp')
	);

	if(array_key_exists($itag, $_formats))
		echo json_encode($_formats[$itag]);
	else
		echo json_encode(array("error" => "itag not found"));
