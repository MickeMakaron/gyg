<a id='above-nukebox' href='/nukebox'>Nukebox</a>
<?php if($userSession->isLoggedIn()): ?>
	<a id='above-logout' href='/playhouse/user/logout'>Logout</a>
	<a id='above-user' href='/playhouse/user/<?=$userSession->get()['key']?>'><?=$userSession->get()['name']?></a>
<?php else: ?>
	<a id='above-login' href='/playhouse/user/login'>Login</a>
	<a id="above-register" href="/playhouse/user">Register</a> 
<?php endif; ?>