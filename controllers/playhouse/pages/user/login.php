<?php

$renderData['style'] = "@import url('/file/playhouse/pages/user/style/login.css');";

ob_start();
include('templates/loginForm.tpl.php');
$renderData['content'] .= ob_get_clean();


// Meta
$renderData['title'] = 'User login';
$renderData['description'] = 'Log in to create posts and view your profile.';
$renderData['keywords'] = array_merge($renderData['keywords'], ['log in', 'user']);