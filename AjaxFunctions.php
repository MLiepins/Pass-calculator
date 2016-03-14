<?php
include 'connection.php';
include 'functions.php';
	
/*if(isset($_GET['Version'])) {
	$version = $_GET['Version'];
	if($version == 1)
	{
		$sql = "SELECT * FROM `products` WHERE `id` in (1, 2, 3, 4, 6, 8)";
		$result = $conn->query($sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$object[] = array(
			'id' => $row['id'],
			'name' => $row['Name'],
			);
		}
	}
	if($version == 2)
	{
		$sql = "SELECT * FROM `products` WHERE `id` in (1, 2, 3, 4, 6, 8)";
		$result = $conn->query($sql);
		while($row = mysqli_fetch_assoc($result))
		{
			$object[] = array(
			'id' => $row['id'],
			'name' => $row['Name'],
			);
		}	
	}
	echo json_encode($object);
}*/

if(isset($_POST['Version']))
{
	$version = $_POST['Version'];
	if($version == 1)
	{		$object[0] = array('id' => 0, 'name' => "Choose product");
			$object[1] = array('id' => 1, 'name' => "Integra");
			$object[2] = array('id' => 2, 'name' => "Integra W");
			$object[3] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[4] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[5] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[6] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 2)
	{
			$object[0] = array('id' => 0, 'name' => "Choose product");
			$object[1] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[2] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[3] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[4] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 3 || $version == 4)
	{
			$object[0] = array('id' => 0, 'name' => "Choose product");
			$object[1] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[2] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[3] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	echo json_encode($object);
}


function echoProduct($prod, $odu)
{
	$product = ChooseProduct($prod, $odu);
	echo json_encode($product); 
}
function getFrequence($conn, $ProdID)
{
	$sql = "SELECT `FrequencyBand` FROM `tx_power` WHERE `Product_ID` = $ProdID GROUP BY `FrequencyBand` ORDER BY `FrequencyBand` ASC";
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'value' => $row['FrequencyBand']
		);
	}
	echo json_encode($object);
}
function getBandwidth($conn, $ProdID, $Frequency)
{
	$sql = "SELECT `BandWidth`, `Standart` FROM `rx_treshold` WHERE `ProductID` = $ProdID and `FrequencyBand` = $Frequency GROUP BY `BandWidth` ASC";
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'Bandwidth' => $row['BandWidth'],
		'Standart' => $row['Standart']
		);
	}
	echo json_encode($object);
}

function getModulation($conn, $ProdID, $Frequency, $Bandwidth, $Standart, $FEC)
{
	$sql = "SELECT `Modulation` FROM `rx_treshold` WHERE `ProductID` = $ProdID AND `Standart` = '$Standart' AND `BandWidth` = $Bandwidth AND `FrequencyBand` = $Frequency AND `FEC` = $FEC GROUP BY `Modulation` DESC";
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'Modulation' => $row['Modulation']
		);
	}
	echo json_encode($object);
}
function getCapacity($conn, $Product, $Modulation, $Bandwidth, $Standart, $FEC)
{
	$sql = "SELECT `Capacity` FROM `rx_treshold` WHERE `Modulation` LIKE '$Modulation' AND `BandWidth` = $Bandwidth AND `Standart` LIKE '$Standart' AND `ProductID` = $Product AND `FEC` = $FEC LIMIT 1";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'capacity' => $row['Capacity']
		);
	}
	echo json_encode($object);
}
function getRainZone($conn, $RainZone)
{
	$sql = "SELECT Value FROM `rainzone` WHERE Zone = '$RainZone'";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'Zone' => $row['Value']
		);
	}
	echo json_encode($object);
}
function getTransmitter($conn, $ProdID, $Modulation, $Frequency)
{
	$sql = "SELECT `TX_MaxPower` FROM `tx_power` WHERE `Product_ID` = $ProdID AND `Modulation` = '$Modulation' AND `FrequencyBand` = $Frequency GROUP BY `TX_MaxPower` ASC";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'transmitterPower' => $row['TX_MaxPower']
		);
	}
	echo json_encode($object);
}
function getDistance($LatA, $LatB, $LonA, $LonB)
{
	$distance = calculateDistance($LatA, $LonA, $LatB, $LonB);
	$rounededDistance = round($distance, 3);
	echo json_encode($rounededDistance);
}

if(isset($_POST['func']) && !empty($_POST['func']))
{
	$func = $_POST['func'];
	switch($func)
	{
		case 'product':  echoProduct($_POST['Prod'], $_POST['Odu']); 
		break;
		
		case 'frequence':  getFrequence($conn, $_POST['ProdID']); 
		break;
	
		case 'bandwidth':  getBandwidth($conn, $_POST['ProdID'], $_POST['Frequency']); 
		break;
		
		case 'Modulation':  getModulation($conn, $_POST['ProdID'], $_POST['Frequency'], $_POST['Bandwidth'], $_POST['Standart'], $_POST['FEC']); 
		break;	

		case 'Capacity':  getCapacity($conn, $_POST['ProdID'], $_POST['Modulation'], $_POST['Bandwidth'], $_POST['Standart'], $_POST['FEC']); 
		break;	
		
		case 'RainZone':  getRainZone($conn, $_POST['Zone']);		
		break;	
		
		case 'Transmitter':  getTransmitter($conn, $_POST['ProdID'], $_POST['Modulation'], $_POST['Frequency']);		
		break;	
		case 'Distance':  getDistance($_POST['LatA'], $_POST['LatB'], $_POST['LonA'], $_POST['LonB']);		
		break;		
	}	
}




?>