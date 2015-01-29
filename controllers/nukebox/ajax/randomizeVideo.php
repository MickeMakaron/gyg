<?php

$videos = scandir(__DIR__ . "/../vids");

$videos = array_splice($videos, 2);

if(count($videos) === 0)
	throw new Exception('There are no videos.');


$index = rand(0, count($videos) - 1);

$video = $videos[$index];
$type = mime_content_type(__DIR__ . "/../vids/{$video}");


// Deliver the response, as a JSON object containing the name of the user.
header('Content-type: application/json');
echo json_encode(["path" => $video, 'type' => $type]);