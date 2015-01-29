<?php $pageId = "about"; ?>
<?php $title = "PIX - About"; ?>
<?php include("incl/config.php"); ?>
<?php include("incl/header.php"); ?>

<!-- Main -->		
		<h1>What is PIX?</h1>
		<p>PIX is a playground for creativity on your website. There is no goal or purpose, only fun.</p>
		
		<p>Coob has found himself in an empty world, and it is up to you to fill that world! Paint the landscape and move around in it with Coob.</p>
		
		<li>FREE - PIX is an entirely free game! You can download it and view installation instructions <a href="download.php">here</a>.</li>
		<li>MULTIPLAYER - Coob doesn't have to be alone. Play with your friends!</li>
		<li>OPTIMIZED - PIX is designed to run smoothly in a browser, with heavy optimizations.</li>
		<li>EASY SETUP - It's easy to install and configure PIX on your own website. Make sure you have the <a href="download.php">necessary libraries</a> installed.</li>
		<li>OPEN SOURCE - <a href="https://github.com/MickeMakaron/PIX">Make it and break it!</a></li>
		
		<p></p>
		<p>PIX is a school project made by a student at BTH in Sweden. No rights reserved. No copyrights. No trademarks.</p>
		
		<p></p>
		<h1>How do I play?</h1>
		<p>Controls:</p>
		<li>Move around with the WASD or the arrow keys.</li>
		<li>Left-click to place a block, right-click to remove it.</li>
		<li>Press escape to exit to main menu.</li>
		<p></p>
		<p>You can either play singleplayer or join/host a multiplayer game. If everyone has left a multiplayer game, the game and its scene will disappear.</p>
		
		<h1>Compatibility issues</h1>
		<p>PIX works 100% on Firefox.</p>
		<p>PIX's collision system does not work on Chrome. I have no idea why.</p>
		
		
		<p></p>
		<h1>Features</h1>
		<li>INTERACTIVE ENVIRONMENT - Paint your environment with your mouse and mind. Because of PIX's optimized stature, PIX can handle a block size of only 1 pixel!</li>
		<li>MULTIPLAYER - There is no limit on how many players can play on the same server. PIX can easily handle up to 10 players on the same server, more has not been tested.</li>
		<p></p>
		<p>Gravity is implemented but currently disabled. If you have experience in JavaScript you can enable it yourself.</p>
		<p>Sadly, the feature list is currently meagre. However, thanks to the expandability of PIX, new features are just waiting to be implemented. 
		The major selling point of PIX is its availability and performance. It will probably not outshine non-browser sandbox games, such as Terraria, but it will be much more available, requiring no user installation, because it's easily available at your website!</p>
		
		
		<p></p>
		<h1>Upcoming features</h1>
		<li>AUDIO - Coob thinks his world is a little too quiet.</li>
		<li>IN-GAME CHAT - Chat chat chat!</li>
		<li>INFINITE WORLDS - Oh, yes! Because of PIX's infinite scalability, infinite worlds are possible. This means Coob will be able to move around with a scrolling camera, no longer limited by the boundaries of the screen.</li>
		<li>NEW BLOCK TYPES - Not only different colors, but different properties as well, such as slippery, damaging and bouncy.</li>
		<li>DOWNLOAD AND UPLOAD SCENES - Save your fun and share it with others!</li>
		<li>ACCOUNT - Make a permanent account so that you can add your friends, view their scenes and join their games.</li> 
		<li>BETTER HOSTING - Choose the block and player sizes when you host, and give your server a name!</li>
		<li>A PROPER GAME LIST - Hehe :)</li>

		<p></p>
		<h1>Client performance</h1>
		<p>Instead of using several HTML5 Canvas elements for different rendering layers, PIX employs a smart rendering system. 
		This results in less GPU processing and memory load.</p>
		
		<p>The smart rendering system allows PIX to only rerender areas that need it. 
		This allows PIX to handle any block and game size with very little CPU. Infinite scalability!</p>
		
		<p>Keep in mind that a small block size will increase the time it takes to build the game scene. 
		Since PIX needs to rerender everything when the page comes into focus, a small block size will result in a noticeable loading time when refocusing the page.</p>
		
		<p></p>
		<h1>Server performance</h1>
		<p>Since there is no need for validation, PIX can offload most work to the clients. The PIX server only functions as a matchmaker. 
		No large computations or storing occurs on the server. Thus, PIX multiplayer is pseudo-P2P, where the server acts as a relay. 
		This means the server requires very little memory and CPU. It is only bottlenecked by your connection speed.</p>
		

	</article>
</div>
<!-- end -->
