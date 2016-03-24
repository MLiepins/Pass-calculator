<?php
session_start();
include '../Auth/connection.php';
include '../Auth/functions.php';
$filename = "";
$log_message1 = "";
$log_message2 = "";
$log_message3 = "";
$log_message4 = "";
$result_1 = "";
$result_2 = "";
$Cord_Result = "";
$LatA = "";
$LonA = "";
$LatB = "";
$LonB = ""; 
$InputsResult = ""; 
if(isset($_GET['Refractivity'])){
	if(empty($_GET['File1'])) $log_message1 = "Please input file name.";
	else
	{
	$filename = $_GET['File1']; 
	$csvData = file_get_contents($filename);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
		$array[] = str_getcsv($line);
	}
	$ySize = sizeof($array) -2;
	$xSize = max( array_map('count', $array) ) - 1;
	for ($i = 1; $i <= $ySize-1; $i++) {
		for ($x = 1; $x <= $xSize-3; $x++) {
			//echo "Lat = ".$array[$i][$xSize]." Long = ".$array[$ySize][$x]. " Value = ".$array[$i][$x]."<br>"; 
			$Lat =  mysqli_real_escape_string($conn,$array[$i][$xSize] );
			$Long =  mysqli_real_escape_string($conn,$array[$ySize][$x]);
			$value =  mysqli_real_escape_string($conn,$array[$i][$x]);
			$sql = "INSERT INTO `refractivity`(`Lat`, `Lon`, `Refr`) VALUES ($Lat, $Long, $value)";
			$result = $conn->query($sql);
		}
	}
	header("Location: AddToDb.php");
	}
}
if(isset($_GET['RainZone'])){
	if(isset($_GET['File96']) == false) $log_message2 = "Please choose file.";
	else
	{
	$filename = $_GET['File96']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-1;
			$xSize = max( array_map('count', $array) )-1; 
			for( $y = 0; $y < $ySize; $y++)
			{
			$Zone =  mysqli_real_escape_string($conn,	$array[$y][0] );
			$Value =  mysqli_real_escape_string($conn,	$array[$y][1]);
			$sql = "INSERT INTO `rainzone` (`Zone`, `Value`) VALUES ('$Zone', '$Value')";
			$result = $conn->query($sql);
			}
	}
}
if(isset($_GET['Refractivity-data']))
{
	$lat = $_GET['Lat1'];
	$lon = $_GET['Lon1'];
	if(empty($lon) || empty($lat)) $result_1 = "ERROR";
	else
	{
		$sql = "SELECT Refr FROM refractivity WHERE Lat LIKE '$lat' and Lon LIKE '$lon'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$data = mysqli_fetch_array($result);
			$result_1 = $data[0];
		}
		else
		{
			$result_1 = "NOT FOUND";
		}
	}
	
}
if(isset($_GET['Gtopo-data']))
{
	$lat = $_GET['Lat1'];
	$lon = $_GET['Lon1'];
	if(empty($lon) || empty($lat)) $result_1 = "ERROR";
	else
	{
		$sql = "SELECT Gtopo FROM gtopo WHERE Lat LIKE '$lat' and Lon LIKE '$lon'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$data = mysqli_fetch_array($result);
			$result_2 = $data[0];
		}
		else
		{
			$result_1 = "NOT FOUND";
		}
	}
	
}
if(isset($_GET['Gtopo'])){
	if(isset($_GET['File2']) == false) $log_message2 = "Please choose file.";
	else
	{
	$filename = $_GET['File2']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-3;
			$xSize = max( array_map('count', $array) )-1;
			//echo $array[0][3];
			for ($x = 2; $x <= $ySize-2; $x++) 
			{
				for ($y = 1; $y <= $xSize-1; $y++)
				{ 
					//echo "Lat = ".$array[$x][$xSize]." Long = ".$array[$ySize][$y]. " Value = ".$array[$x][$y]."<br>"; 
					$Lat =  mysqli_real_escape_string($conn,$array[$x][$xSize] );
					$Long =  mysqli_real_escape_string($conn,$array[$ySize][$y]);
					$value =  mysqli_real_escape_string($conn,$array[$x][$y]);
					$sql = "INSERT INTO `gtopo`(`Lat`, `Lon`, `Gtopo`) VALUES ($Lat, $Long, $value)";
					$result = $conn->query($sql);
				}
			}

	}
}
if(isset($_GET['Gtopo3'])){
	if(isset($_GET['File3']) == false) $log_message3 = "Please choose file.";
	else
	{
	$filename = $_GET['File3']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-4;
			$xSize = max( array_map('count', $array) )-1;
			//echo $array[0][3];
			for ($x = 2; $x <= $ySize-2; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 4; $y <= $xSize-1; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					//echo "Lat = ".$array[$x][$xSize]." Long = ".$array[$ySize][$y]. " Value = ".$array[$x][$y]."<br>"; 
					$Lat =  mysqli_real_escape_string($conn,$array[$x][$xSize] );
					$Long =  mysqli_real_escape_string($conn,$array[$ySize][$y]);
					$value =  mysqli_real_escape_string($conn,$array[$x][$y]);
					$sql = "INSERT INTO `gtopo`(`Lat`, `Lon`, `Gtopo`) VALUES ($Lat, $Long, $value)";
					$result = $conn->query($sql);
				}
			}

	}
}
if(isset($_GET['Gtopo4'])){
	if(isset($_GET['File4']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File4']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-4;
			$xSize = max( array_map('count', $array) )-1;
			//echo $array[0][3];
			for ($x = 2; $x <= $ySize-2; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 4; $y <= $xSize-4; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					//echo "Lat = ".$array[$x][$xSize]." Long = ".$array[$ySize][$y]. " Value = ".$array[$x][$y]."<br>"; 
					$Lat =  mysqli_real_escape_string($conn,$array[$x][$xSize] );
					$Long =  mysqli_real_escape_string($conn,$array[$ySize][$y]);
					$value =  mysqli_real_escape_string($conn,$array[$x][$y]);
					$sql = "INSERT INTO `gtopo`(`Lat`, `Lon`, `Gtopo`) VALUES ($Lat, $Long, $value)";
					$result = $conn->query($sql);
				}
			}

	}
}
if(isset($_GET['Regression'])){
if(isset($_GET['File95']) == false) $log_message4 = "Please choose file.";
else
	{
	$filename = $_GET['File95']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )-1;
			//echo $array[0][3];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
					//echo "Lat = ".$array[$x][0]." ".$array[$x][1]." ".$array[$x][2]."  ".$array[$x][3]." ".$array[$x][4]."<br>"; 
					$frequency =  mysqli_real_escape_string($conn,$array[$x][0]);
					$kh =  mysqli_real_escape_string($conn,$array[$x][1]);
					$kv =  mysqli_real_escape_string($conn,$array[$x][2]);
					$ah =  mysqli_real_escape_string($conn,$array[$x][3]);
					$av =  mysqli_real_escape_string($conn,$array[$x][4]);
					$sql = "INSERT INTO `regression_coeficients`(`Frequency`, `kh`, `kv`, `ah`, `av`) VALUES ('$frequency', '$kh','$kv','$ah','$av')";
					$result = $conn->query($sql);
			}

	}
}
//Integrated!
if(isset($_GET['AnthenaGains'])){
	if(isset($_GET['File99']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File99']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-3;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 1; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if($array[$x][$y] != "NA")
					{
						echo "Freq = ".$array[0][$y]." Diameter = ".$array[$x][0]. " DBI = ".$array[$x][$y]."<br>"; 
						$Freq =  mysqli_real_escape_string($conn,$array[0][$y]);
						$Diameter =  mysqli_real_escape_string($conn,$array[$x][0]);
						$DBI =  mysqli_real_escape_string($conn,$array[$x][$y]);
						$sql = "INSERT INTO `anthenagains` (`Manuf_ID`, `Frequence`, `Diameter`, `Gain`) VALUES ('1', $Freq, $Diameter, $DBI)";
						$result = $conn->query($sql);
					}
				}
			}

	}
}
//Andrew, LEAX arcivator, Tongyu, Grante, RFS
if(isset($_GET['AnthenaGains2'])){
	if(isset($_GET['File98']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File98']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 1; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if($array[$x][$y] != "NA")
					{
						//echo "Freq = ".$array[0][$y]." Diameter = ".$array[$x][0]. " DBI = ".$array[$x][$y]."<br>"; 
						$Freq =  mysqli_real_escape_string($conn,$array[0][$y]);
						$Diameter =  mysqli_real_escape_string($conn,$array[$x][0]);
						$DBI =  mysqli_real_escape_string($conn,$array[$x][$y]);
						$sql = "INSERT INTO `anthenagains` (`Manuf_ID`, `Frequence`, `Diameter`, `Gain`) VALUES ('6', $Freq, $Diameter, $DBI)";
						$result = $conn->query($sql);
					}
				}
			}

	}
}
if(isset($_GET['AnthenaGains3'])){
	if(isset($_GET['File97']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File97']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-3;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 1; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if($array[$x][$y] != "NA")
					{
						//echo "Freq = ".$array[0][$y]." Diameter = ".$array[$x][0]. " DBI = ".$array[$x][$y]."<br>"; 
						$Freq =  mysqli_real_escape_string($conn,$array[0][$y]);
						$Diameter =  mysqli_real_escape_string($conn,$array[$x][0]);
						$DBI =  mysqli_real_escape_string($conn,$array[$x][$y]);
						$sql = "INSERT INTO `anthenagains` (`Manuf_ID`, `Frequence`, `Diameter`, `Gain`) VALUES ('3', $Freq, $Diameter, $DBI)";
						$result = $conn->query($sql);
					}
				}
			}
	}
}

if(isset($_GET['RxThresholds'])){
	if(isset($_GET['File1001']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1001']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 3; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 3; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if(!empty($array[$x][$y]))
					{
						$Pieces = explode(" ", $array[0][1]);
						//echo "Modulation: ".$array[$x][0]." FEC: ".$array[$x][1]." Capacity: ".$array[$x][2]." Ghz: ".substr( $array[2][$y], 0, -3 )." Value: ".$array[$x][$y]." Bandwidth: ". substr( $Pieces[0], 0, -3 )." Standart: ".$Pieces[1]." <br>" ;
						$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
						if($array[$x][1] == 'Strong') $FEC = 0; 
						else $FEC = 1; 
						$Capacity = mysqli_real_escape_string($conn, $array[$x][2]);
						$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[2][$y], 0, -3 ));
						$RXTreshold = mysqli_real_escape_string($conn, $array[$x][$y]);
						$BandWidth = mysqli_real_escape_string($conn, substr( $Pieces[0], 0, -3 ));
						$Standart = mysqli_real_escape_string($conn, $Pieces[1]);
						$sql = "INSERT INTO `rx_treshold` (`ProductID`, `Modulation`, `FEC`, `Capacity`, `FrequencyBand`, `BandWidth`, `Standart`, `RXTreshold`) VALUES ( 2, '$Modulation', $FEC, $Capacity, '$FrequencyBand', '$BandWidth', '$Standart', $RXTreshold)";
						$result = $conn->query($sql);
						echo $conn->error;

					}
				}
			}
	}
}
if(isset($_GET['TXpower'])){
	if(isset($_GET['File1002']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1002']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 2; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if(!empty($array[$x][$y]))
					{
						//echo " Modulation: ".$array[$x][0]." Frequence: ".$array[0][$y]." Power: ".$array[$x][$y]."<br>"; 																		
						$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
						$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[0][$y], 0, -3 ));
						$TX_Power = $array[$x][$y]; 
						$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`) VALUES ( 2,'$Modulation', $FrequencyBand, 0 ,$TX_Power)";
						$result = $conn->query($sql);
						echo $conn->error;
					}
				}
			}
	}
}
if(isset($_GET['TXpowerLumina'])){
	if(isset($_GET['File1004']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1004']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 1; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 2; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if(!empty($array[$x][$y]))
					{
						if (strpos($array[$x][$y], '...') == true) 
						{
							$Powers = explode("...",$array[$x][$y]);
							//echo " Modulation: ".$array[$x][0]." Frequence: ".$array[0][$y]." TXmin: ".$Powers[0]." TXmax: ".$Powers[1]."<br>"; 	
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[0][$y], 0, -3 ));
							$TXmin = mysqli_real_escape_string($conn, $Powers[0]);
							$TXmax = mysqli_real_escape_string($conn, $Powers[1]);
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`) VALUES ( 3,'$Modulation', $FrequencyBand, $TXmin, $TXmax)";
							$result = $conn->query($sql);
							echo $conn->error;
						}
						else
						{
							//echo " Modulation: ".$array[$x][0]." Frequence: ".$array[0][$y]." Power: ".$array[$x][$y]."<br>"; 																		
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[0][$y], 0, -3 ));
							$TX_Power = $array[$x][$y]; 
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`) VALUES ( 3,'$Modulation', $FrequencyBand, 0 ,$TX_Power)";
							$result = $conn->query($sql);
							echo $conn->error;
						}
					}
				}
			}
	}
}
if(isset($_GET['OduTX'])){
	if(isset($_GET['File1005']) == false) $log_message4 = "Please choose file.";
	else
	{
		$filename = $_GET['File1005']; 
		$csvData = file_get_contents($filename);
		//print_r ($csvData);
		$product = 9; 
		$lines = explode(PHP_EOL, $csvData);
		$array = array();
		foreach ($lines as $line) 
		{
			$array[] = str_getcsv($line);
		}
				$ySize = sizeof($array)-2;
				$xSize = max( array_map('count', $array) )- 1;
				//echo $array[$ySize][$xSize];
				for ($x = 2; $x <= 7; $x++) //$x = 2 norāda no kuras rindas sākt
				{
					for ($y = 2; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
					{ 
						if(!empty($array[$x][$y]))
						{
								$MinPower = 0; 
								echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." High Power MIN: ".$MinPower  ." High Power MAX: ".$array[$x][$y]. "<br>"; 		
								$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
								$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
								$HTX_MinPower = mysqli_real_escape_string($conn, $MinPower);
								$HTX_MaxPower = mysqli_real_escape_string($conn, $array[$x][$y]);
								$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $HTX_MinPower, $HTX_MaxPower)";
								$result = $conn->query($sql);
								echo $conn->error;
						}
					}
				}
	}
}
if(isset($_GET['TXpower2'])){
	if(isset($_GET['File1003']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1003']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$product = 8; 
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			//echo $array[$ySize][$xSize];
			for ($x = 2; $x <= 7; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 2; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if(!empty($array[$x][$y]) && !empty($array[$x+9][$y]))
					{
						if (strpos($array[$x][$y], '...') == true) 
						{
							$Powers = explode("...",$array[$x][$y]);
							$HighMinPower = $array[$x+9][$y] - ($Powers[1] - $Powers[0]);
							echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." TXmin: ".$Powers[0]." TXmax: ".$Powers[1]." High Min Powers: ".$HighMinPower." High Max Power: ".$array[$x+9][$y]."<br>"; 	
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
							$TXmin = mysqli_real_escape_string($conn, $Powers[0]);
							$TXmax = mysqli_real_escape_string($conn, $Powers[1]);
							$HTX_MinPower = mysqli_real_escape_string($conn, $HighMinPower);
							$HTX_MaxPower = mysqli_real_escape_string($conn, $array[$x+9][$y]);
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`, `HTX_MinPower`, `HTX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $TXmin, $TXmax, $HTX_MinPower, $HTX_MaxPower)";
							$result = $conn->query($sql);
							echo $conn->error;
						}
						else 																
						{
							$MinPower = 0; 
							$HighPowerMin = $array[$x+9][$y] - abs($array[$x][$y] - $MinPower);
							echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." MinPower: ". $MinPower. " Power: ".$array[$x][$y]." High Power Min: ". $HighPowerMin  ." High Power MAX: ".$array[$x+9][$y]. "<br>"; 		
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
							$TXmax = mysqli_real_escape_string($conn, $array[$x][$y]);
							$TXmin = mysqli_real_escape_string($conn, $MinPower);
							$HTX_MinPower = mysqli_real_escape_string($conn, $HighPowerMin);
							$HTX_MaxPower = mysqli_real_escape_string($conn, $array[$x+9][$y]);
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`, `HTX_MinPower`, `HTX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $TXmin, $TXmax, $HTX_MinPower, $HTX_MaxPower)";
							$result = $conn->query($sql);
							echo $conn->error;
						}
					}
					else if(!empty($array[$x][$y]) && empty($array[$x+9][$y]))
					{
						if (strpos($array[$x][$y], '...') == true) 
						{
							$Powers = explode("...",$array[$x][$y]);
							echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." TXmin: ".$Powers[0]." TXmax: ".$Powers[1]."<br>"; 	
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
							$TXmin = mysqli_real_escape_string($conn, $Powers[0]);
							$TXmax = mysqli_real_escape_string($conn, $Powers[1]);
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MinPower`, `TX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $TXmin, $TXmax)";
							$result = $conn->query($sql);
							echo $conn->error;
						}
						else																
						{
							echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." Power: ".$array[$x][$y]."<br>"; 		
							$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
							$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
							$TXmax = mysqli_real_escape_string($conn, $array[$x][$y]);
							$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`, `TX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $TXmax)";
							$result = $conn->query($sql);
							//echo $conn->error;
						}
					}
					else if(empty($array[$x][$y]) && !empty($array[$x+9][$y]))
					{
						$MinPower = 14; 
						echo " Modulation: ".$array[$x][0]." Frequence: ".$array[1][$y]." HP_min_Power: ".$MinPower. " HP_Max_Power: ".$array[$x+9][$y]."<br>"; 																		
						$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
						$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[1][$y], 0, -3 ));
						$HTX_MaxPower = mysqli_real_escape_string($conn, $array[$x+9][$y]);
						$HTX_MinPower = mysqli_real_escape_string($conn, $MinPower);
						$sql = "INSERT INTO `tx_power`(`Product_ID`, `Modulation`, `FrequencyBand`,`HTX_MinPower`, `HTX_MaxPower`) VALUES ( $product,'$Modulation', $FrequencyBand, $HTX_MinPower, $HTX_MaxPower)";
						$result = $conn->query($sql);
						echo $conn->error;
					}
				}
			}
	}
}
if(isset($_GET['Signatues'])){
	if(isset($_GET['File1009']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1009']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$prodid = 9; 
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) 
	{
		$element = explode(",",$line);
		if($element[0] != NULL)
		{
			if($element[0] == "Signature")
			{
				$Bandwidth=$element[2];
			}
			if(is_numeric($element[0]))
			{
				$sql="INSERT INTO signature_cfip(Productid,BandWidth,Modulation,TransfereSpeed,Width,minDepth,nonMinDepth)
				VALUE('$prodid','$Bandwidth','$element[4]','$element[0]','$element[1]','$element[2]','$element[3]')";
				$result = $conn->query($sql);
				echo $conn->error; 
			}
		}
	}
	}
}
if(isset($_GET['RxThresholdLumina'])){
	if(isset($_GET['File1010']) == false) $log_message4 = "Please choose file.";
	else
	{
	$filename = $_GET['File1010']; 
	$csvData = file_get_contents($filename);
	//print_r ($csvData);
	$lines = explode(PHP_EOL, $csvData);
	$array = array();
	foreach ($lines as $line) {
	$array[] = str_getcsv($line);
	}
			$ySize = sizeof($array)-2;
			$xSize = max( array_map('count', $array) )- 1;
			for ($x = 3; $x <= $ySize; $x++) //$x = 2 norāda no kuras rindas sākt
			{
				for ($y = 2; $y <= $xSize; $y++)//$y = 1 norāda no kuras kolonnas sākt. 
				{ 
					if(!empty($array[$x][$y]))
					{
						echo "Modulation: ".$array[$x][0]." FEC: ".$array[$x][1]." Capacity: ".$array[$x][2]." Ghz: ".substr( $array[2][$y], 0, -3 )." Value: ".$array[$x][$y]." <br>" ;
						//$Modulation =  mysqli_real_escape_string($conn, $array[$x][0]);
						//if($array[$x][1] == 'Strong') $FEC = 0; 
						//else $FEC = 1; 
						//$Capacity = mysqli_real_escape_string($conn, $array[$x][2]);
						//$FrequencyBand = mysqli_real_escape_string($conn, substr( $array[2][$y], 0, -3 ));
						//$RXTreshold = mysqli_real_escape_string($conn, $array[$x][$y]);
						//$BandWidth = mysqli_real_escape_string($conn, substr( $Pieces[0], 0, -3 ));
						//$Standart = mysqli_real_escape_string($conn, $Pieces[1]);
						//$sql = "INSERT INTO `rx_treshold` (`ProductID`, `Modulation`, `FEC`, `Capacity`, `FrequencyBand`, `BandWidth`, `Standart`, `RXTreshold`) VALUES ( 2, '$Modulation', $FEC, $Capacity, '$FrequencyBand', '$BandWidth', '$Standart', $RXTreshold)";
						//$result = $conn->query($sql);
						//echo $conn->error;
					}
				}
			}
	}
}
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
	<h2>Lumina Thresholds</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1010" placeholder="RX thresholds">
	<input type="submit" name = "RxThresholdLumina" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>Signatures</h2>
	<form class="Signatues" method="GET">
	<input type="text" name="File1009" placeholder="Signatures">
	<input type="submit" name = "Signatues" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>TX Power</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1002" placeholder="RX thresholds">
	<input type="submit" name = "TXpower" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>TX Power 2</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1003" placeholder="RX thresholds">
	<input type="submit" name = "TXpower2" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>Lumina TX Power</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1004" placeholder="RX thresholds">
	<input type="submit" name = "TXpowerLumina" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>R ODU TX Power</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1005" placeholder="RX thresholds">
	<input type="submit" name = "OduTX" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>Anthenna Gains</h2>
	<form class="Anth-Gains" method="GET">
	<input type="text" name="File99" placeholder="File name">
	<input type="submit" name = "AnthenaGains" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message1)?>
	</form>
	<h2>Anthenna Gains2</h2>
	<form class="Anth-Gains" method="GET">
	<input type="text" name="File98" placeholder="File name">
	<input type="submit" name = "AnthenaGains2" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message1)?>
	</form>
	<h2>Anthenna Gains3</h2>
	<form class="Anth-Gains" method="GET">
	<input type="text" name="File97" placeholder="File name">
	<input type="submit" name = "AnthenaGains3" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message1)?>
	</form>
	<h2>Refractivity</h2>
	<form class="form-Refrectivity" method="GET">
	<input type="text" name="File1" placeholder="File name">
	<input type="submit" name = "Refractivity" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message1)?>
	</form>
	<br> Select data:
	<form class="form-Refrectivity" method="GET">
	<input type="text" name="Lat1" placeholder="Lat">
	<input type="text" name="Lon1" placeholder="Lon">
	<input type="submit" name = "Refractivity-data" value="Find">
	result: <?php echo htmlspecialchars($result_1)?>
	</form>
	<h2>RainZone</h2>
	<form class="form-Gtopo1" method="GET">
	<input type="text" name="File96" placeholder="File name">
	<input type="submit" name = "RainZone" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message2)?>
	</form>
	<h2>Regression Coeficients</h2>
	<form class="form-Gtopo1" method="GET">
	<input type="text" name="File95" placeholder="File name">
	<input type="submit" name = "Regression" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message2)?>
	</form>
	<h2>Gtopo1</h2>
	<form class="form-Gtopo1" method="GET">
	<input type="text" name="File2" placeholder="File name">
	<input type="submit" name = "Gtopo" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message2)?>
	</form>
	<h2>Gtopo2</h2>
	<form class="form-Gtopo2" method="GET">
	<input type="text" name="File3" placeholder="File name">
	<input type="submit" name = "Gtopo3" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message3)?>
	</form>
	<h2>Gtopo3</h2>
	<form class="form-Gtopo3" method="GET">
	<input type="text" name="File4" placeholder="File name">
	<input type="submit" name = "Gtopo4" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	<h2>Thresholds</h2>
	<form class="form-Threshold" method="GET">
	<input type="text" name="File1001" placeholder="RX thresholds">
	<input type="submit" name = "RxThresholds" value="Upload">
	<br>Log: <?php echo htmlspecialchars($log_message4)?>
	</form>
	</body>
</html>
