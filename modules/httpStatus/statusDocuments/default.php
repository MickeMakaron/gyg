<!--
/****************************************************************
 ****************************************************************
 * 
 * gyg-framework - Basic framework for web development
 * Copyright (C) 2014 Mikael Hernvall (mikael.hernvall@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ****************************************************************
 ****************************************************************/
-->
<!-- gyg-framework 404 Error document. -->
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html>
<head>
	<title><?=$title?></title>
</head>
<body>
<div id="content">
	<h1><?=$title?></h1>
	<p>The requested URL <?=$_SERVER['REQUEST_URI']?> was not found on this server.</p>
	<?php if(isset($message)): ?>
		<p><?=$message?></p>
	<?php endif; ?>
<div>

<address></address>
</body>
</html>