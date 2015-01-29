<?php 
$renderData['title'] = "gyg";

$renderData['lang'] = 'en';

$renderData['templatePath'] = __DIR__ . "/home.tpl.php";
$renderData['favicon'] = $gyg->path2url(__DIR__ . "/../../img/faviconOizo.png");

$renderData['above'] = "";
$renderData['header'] = "<img src =".$gyg->path2url(__DIR__ . "/../../img/gyg.jpg").">";
$renderData['menu'] = "";
$renderData['content'] = "";
$renderData['contentNav'] = "";
$renderData['footer'] = "";

$renderData['style'] = null;
$renderData['stylesheet'] = $gyg->path2url(__DIR__ . "/../../style/style.css");

$gyg->render($renderData['templatePath'], $renderData);