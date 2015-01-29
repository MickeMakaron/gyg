<!doctype html>
<html lang="en">
<head>
	<!-- what -->
	<meta charset='utf-8'/>
	<meta name="viewport" content="width=device-width; initial-scale=0.5, maximum-scale=0.5, minimum-scale=0.5; " />
	<link rel = "shortcut icon" href = "/file/nukebox/cool.png"/>
	<link rel="stylesheet" type="text/css" href="/file/nukebox/style/stylesheet.css">
	<script src="/file/nukebox/js/jquery.js"></script>
	
	<title>Nukebox</title>
	<meta name="description" content="Watch and listen to random music videos." />
	<meta name="keywords" content="mikael hernvall, mikael, hernvall, nukebox, random video, jukebox, music, funny, odd, videos, music videos" />

	<script>	
		function randomizeVideo()
		{
			$.ajax
			({
				url: "/nukebox/randomizeVideo",
				dataType: 'json',
				success: function(data)
				{
					var player = document.getElementById('video1');
					var currentVid = player.src.replace('http://mikael.hernvall.com/', '');
					console.log('Checking random video: "' + data.path + '", current video: "' + currentVid + '"');
					if(currentVid === data.path)
					{
						
						randomizeVideo();
						return;
					}
					
					player.src = '/nukebox/vids/' + data.path;
					player.type = data.type;
					console.log('Starting next video: "' + data.path + '", type: "' + data.type + '"');
				}
			});
		}
		
		$(document).ready(randomizeVideo);
	</script>
</head>
<body background="/file/nukebox/cool.png">

<table id="main-wrapper">
	<tr>
		<td id = "playerCell" valign="middle">
				<div id="player">
					<video id="video1" onended="randomizeVideo()" width="640" height="360" autoplay controls>
						Your browser does not support HTML5 video :(
					</video>
				</div>
		</td>
	</tr>
	
	<tr>
		<td id = "footerCell" valign="bottom">
			<div id = "footer" class = "shadow">
				<p>&copy; 2014-2015 <a href = "/">Mikael Hernvall</a></p>
			</div>
		</td>
	</tr>
	
</table>

	

</body>
</html>