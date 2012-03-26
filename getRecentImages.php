<?php

function getRecentImages($images) {

$i = 0;
$show = 10;

foreach($images as &$image)
{
		if($i == $show) break; else ++$i;
		$image = $image . ' - ' . date('D, d M y H:i:s', filemtime($image)) . '<br />' . "\n";
}


$array = array();
$array = array_slice($images, 0, 10);
$recentImages = shuffleImages($array);
return $recentImages;
}

function shuffleImages($array) {
  $copy = array();
  while (count($array)) {
    $element = array_rand($array);
    $copy[$element] = $array[$element];
    unset($array[$element]);
  }
  return $copy;
}

?>
