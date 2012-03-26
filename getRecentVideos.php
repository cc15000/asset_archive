<?php

function getRecentVideos($videos) {

$i = 0;
$show = 100;

foreach($videos as &$video)
{
		if($i == $show) break; else ++$i;
		$video = $video . ' - ' . date('D, d M y H:i:s', filemtime($video)) . '<br />' . "\n";
}


$array = array();
$array = array_slice($videos, 0, 100);
$recentVideos = shuffleVideos($array);
return $recentVideos;
}

function shuffleVideos($array) {
  $copy = array();
  while (count($array)) {
    $element = array_rand($array);
    $copy[$element] = $array[$element];
    unset($array[$element]);
  }
  return $copy;
}

?>
