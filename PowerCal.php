
<?php

include '../Auth/connection.php';

?>



<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta name="description" content="Register">
	<link href="../Style/css.css" rel="stylesheet">
	<title>Lapa</title>
	</head>
	<body>	
	    <?php
		
		
		$filename = "map.csv"; 
		$csvData = file_get_contents($filename);
		$lines = explode(PHP_EOL, $csvData);
		$array = array();
		foreach ($lines as $line) {
		$array[] = str_getcsv($line);
		}
		$ySize = sizeof($array)-3;
		$xSize = max( array_map('count', $array) )-2;

        $image = imagecreatetruecolor(720, 360);
		
		$y = 90;
		$x = -180;
		$sign = 1; 	
	
		for ($x = 2; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
		{
			for ($y = 0; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
			{ 		
				if($array[$x][$y] != 0)
				{   
					if($array[$x][$y] * 200 - 25 > 255 ) $tone = 255; 
					else $tone = ($array[$x][$y] * 180);  
					$black = imagecolorallocate($image, $tone, $tone*0.3, $tone);	
					imagesetpixel($image, round($y),  round($x), $black);
				}
				else
				{
					$white = imagecolorallocate($image, 47, 87, 141);
					imagesetpixel($image, round($y),  round($x),  $white);
				}	
			}
		}
	
        ob_start();
        imagepng($image);
        printf('<img src="data:image/png;base64,%s"/>', 
        base64_encode(ob_get_clean()));
        imagedestroy($image);
		?>
	</body>
</html>
