<!DOCTYPE html>
<html lang="sv">
<head>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet/less" type="text/css" href="<?=$gyg->path2url(__DIR__ . '/../style/style.less')?>">
	<style>@import "<?=$gyg->path2url(__DIR__ . '/../style/stylesheet.css')?>"; <?php echo $pageStyle; ?></style> 
	<script src="<?=$gyg->path2url(__DIR__ . '/../js/less.min.js')?>"></script>
	<script src="<?=$gyg->path2url(__DIR__ . '/../js/modernizr.js')?>"></script>
	
	<link rel="shortcut icon" href="<?=$gyg->path2url(__DIR__ . '/../img/favicon_1.png')?>">
</head>

<body<?php if(isset($pageId)) echo " id='$pageId' "; ?>>
	<!-- Header -->	
	<header id="top">
		<p style="font-size: 10em; text-align: center;">PIX</p>
	</header>
	
	<!-- Navigation menu -->
	<header id="nav">
		<nav class="navmenu">
			<a id="about-"     	href="/pix/about">About</a>
			<a id="game-" 	href="/pix/game">Try it!</a>
			<a id="download-"  	href="/pix/download">Download</a>
		</nav>
	</header>
	
	<div id="main">
		<article class="justify border" style="width:80%; margin: auto; margin-bottom: 1em;">