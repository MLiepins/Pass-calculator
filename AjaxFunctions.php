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
function getAntennaManuf($conn, $ProdID, $Frequency)
{
	$sql = "SELECT table1.`Manuf_ID`, table1.`Manuf_Name` FROM anthenas table1, anthena_product table2, anthenagains table3 WHERE table2.`Product_ID` = $ProdID AND table3.`Frequence` = $Frequency AND table1.Manuf_ID = table2.Anthena_ID AND table1.Manuf_ID = table3.`Manuf_ID` GROUP BY table1.`Manuf_ID` ASC";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'ID' => $row['Manuf_ID'],
		'Name' => $row['Manuf_Name']
		);
	}
	echo json_encode($object);
}
function getAntennaDiameter($conn, $AntennaManuf,$Frequency)
{
	$sql = "SELECT `Diameter` FROM `anthenagains` WHERE `Frequence` = $Frequency AND `Manuf_ID` = $AntennaManuf GROUP BY `Diameter` ASC";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'Diameter' => $row['Diameter']
		);
	}
	echo json_encode($object);
	
}
function getAntennaGain($conn, $AntennaManuf, $Frequency, $Diameter)
{
	$Gain = GetAnthenaParams($conn, $AntennaManuf, $Frequency, $Diameter);
	echo json_encode($Gain);
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

		case 'antennaManuf':  getAntennaManuf($conn, $_POST['ProdID'], $_POST['Frequency']);		
		break;

		case 'antennaDiameter':  getAntennaDiameter($conn, $_POST['antennaManuf'], $_POST['Frequency']);		
		break;	
		
		case 'antenna_DBI':  getAntennaGain($conn, $_POST['antennaManuf'], $_POST['Frequency'], $_POST['Diameter']);		
		break;		
		
		/*case 'calculate':
			
			$Distance = calculateDistance($_POST['LatA'], $_POST['LonA'], $_POST['LatB'], $_POST['LonB']); 
			$Variables = array(
				"distance" => $Distance,
				"Product" =>  $_POST['ProdID'];
				"LatA" => $_POST['LatA'],
				"LonA" =>  $_POST['LonA'],
				"LatB" =>  $_POST['LatB'],
				"LonB" =>  $_POST['LonB'], 
				"Temperature" => $_POST['Temperature'],
				//"Antenna_Coupler" => $Antenna_Coupler,
				"Antenna_A" => $_POST['AntennaHeightA'],
				"Antenna_B" => $_POST['AntennaHeightB'],
				//"Manufacturer" => $_GET['Manufacturer'],
				"Diameter_A_1" => $_GET['diameter1'],
				"Diameter_B_1" => $_GET['diameter2'],
	//"Diameter_A_2" => $_GET['diameter3'],
	//"Diameter_B_2" => $_GET['diameter4'],
	"Temp_Rain_Zone" => $_GET['Rainzone'], 
	"Frequency" => $_GET['Frequency'],
	"TransmitPow" => $_GET['Transmitter'],
	"Main_Freq" => $_GET['MainFreq'],
	"Div_Freq" => $_GET['DivFreq'],
	"Frequency_FD" => $_GET['FrequencyFD'],
	"Transmitter_FD" => $_GET['TransmitterFD'],
	"Prim_Site_A" => $_GET['prim_SiteA'],
	"Prim_Site_B" => $_GET['prim_SiteB'],
	"Stand_Site_A" => $_GET['stand_SiteA'],
	"Stand_Site_B" => $_GET['stand_SiteB'],
	"Amount_Of_Antenas" => $_GET['AntennasAmount'],
	"SD_Sep_A" => $_GET['SDsepA'],
	"SD_Sep_B" => $_GET['SDsepB'],
	"Losses" => $_GET['Losses'],
	"Modulation" => $_GET['rModulation'],
	"FEC" => $_GET['FEC'],
	"Band_Width" => $BandwidthTMP[0],
	"Standart" => $BandwidthTMP[1],
);

		break; 
		$.post( "AjaxFunctions.php", { func: 'calculate', ProdID: ProdID, Frequency: Frequency, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Temperature: Temperature, Modulation: Modulation, Rainzone: RainzoneTMP, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Losses: Losses, AntennaA_tmp: AntennaA_tmp, AntennaB_tmp: AntennaB_tmp}, function(response)
	*/
	}	
}




?>