<?php $pageId = "download"; ?>
<?php $title = "PIX - Download"; ?>
<?php include("incl/config.php"); ?>
<?php include("incl/header.php"); ?>

<!-- Main -->		
		<h1>Download PIX</h1>
		<p>Latest version (v0.1): <a href="https://github.com/MickeMakaron/PIX/tree/v0.1">GitHub</a></p>
		<p>At GitHub, download PIX by clicking "Download ZIP" in the lower right.</p>
		
		<p></p>
		<h1>Install PIX</h1>
		<p>Before you install PIX, make sure you have the prerequisites installed. They are listed below at "Prerequisites".</p>
		<p>When you've downloaded PIX you will have two files: pix.js (the client) and pix_server.js (the server). You will also have a folder "node_modules" that contain the node.js modules PIX uses by default.</p>
		<p>Put the client wherever you want it in your website's directory and include the script on your wanted page. Make sure that you include jQuery <strong>before</strong> PIX.</p>
		<p>Create a HTML5 Canvas element on the page you included PIX on and id-tag it with "pix". Singleplayer should now be operational on your page.</p>
		<p>To enable multiplayer you need to put the server file where you want it and run it with node.js. You must then edit the configuration variables at the top of pix.js and pix_server.js. See "Configure PIX" for details.</p>
		
		<p></p>
		<h1>Prerequisites</h1>
		<p>PIX requires <a href="http://jquery.com/">jQuery</a> to work. If you want to use multiplayer, you must have <a href="http://nodejs.org/">node.js</a> with a <a href="https://github.com/Worlize/WebSocket-Node">Websocket</a> module installed.</p>
		
		<p></p>
		<h1>Configure PIX</h1>
		<p>There are currently four ways to configure PIX on your page:</p>
		<li>Canvas size - You MUST set your wanted Canvas size in the HTML element. Like so: <br><code>"&lt;canvas id="pix" width="500px" height="500px"></canvas>"</code>.</li>
		<li>Block size - Edit the BLOCK_SIZE variable at the top of pix.js. Default is {width: 10, height: 10}.</li>
		<li>Player size - Edit the PLAYER_SIZE variable at the top of pix.js. Default is {width: 15, height: 15}.</li>
		<li>Default state - Edit the DEFAULT_STATE variable at the top of pix.js. Default is 'main'. If set to 'main', the game will always start in the main menu on page reload. If set to 'singleplayer', it'll start in singleplayer mode.</li>
		<p></p>
		<p>When editing the block and player size, the game you're joining most be hosted by a client that has the same sizes as you, else the game will fail.</p>
		
		<p></p>
		<p>By default, you will connect to PIX's server. To connect to your own server, you must edit the following:
		<li>Server port in pix_server.js - Set "port" to the port you want your server to listen to. Make sure that port is open.</li>
		<li>Server port in pix.js - Set PORT to the same value as "port" in pix_server.js.</li>
		<li>Server host in pix.js - Set HOST to the adress you are hosting pix_server.js from.</li>
		
		<p></p>
		<p>Additionally, you can allow only certain connection sources by editing the following:</p>
		<li>Allowed connection sources in pix_server.js - Set the sources you want to allow in the allowedOrigins array. If you're unsure about the source, you can check the log of the server to see the source you're connecting from when trying to connect. If the array is empty, all connections are allowed.</li>
		
	</article>
</div>
<!-- end -->
