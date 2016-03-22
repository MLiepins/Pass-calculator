<?php
include 'connection.php';
include 'functions.php';

if(isset($_POST['Version']))
{
	$version = $_POST['Version'];
	if($version == 1)
	{		
			$object[1] = array('id' => 1, 'name' => "Integra");
			$object[2] = array('id' => 2, 'name' => "Integra W");
			$object[3] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[4] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[5] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[6] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 2)
	{
			$object[1] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[2] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[3] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[4] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 3 || $version == 4)
	{
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
	$sql = "SELECT `TX_MinPower`,`TX_MaxPower` FROM `tx_power` WHERE `Product_ID` = $ProdID AND `Modulation` = '$Modulation' AND `FrequencyBand` = $Frequency LIMIT 1";	
	$result = $conn->query($sql);
	while($row = mysqli_fetch_assoc($result))
	{
		$object[] = array(
		'MinPower' => $row['TX_MinPower'],
		'MaxPower' => $row['TX_MaxPower']
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
function  getThreshold_Echo($conn, $ProdID, $Frequency, $Bandwidth, $Standart, $FEC, $Modulation)
{
	$Threshold = getThreshold($conn, $Frequency, $Bandwidth, $Standart, $FEC, $Modulation, $ProdID);
	echo json_encode($Threshold);
}
function  getFadeMargin_Echo($Coupler, $Prim_Site_A, $Prim_Site_B, $Stand_Site_A, $Stand_Site_B, $Frequency, $Temperature, $LatA, $LatB, $distance, $version, $AntennaA, $AntennaB, $Threshold, $Transmitter, $Losses)
{
	$attenuation = Attenuation($Frequency, $Temperature , $LatA, $LatB, $AntennaA, $AntennaB, $distance);
	$FadeMargin = getFadeMargin($Coupler, $version, $distance, $Frequency, $Transmitter, $AntennaA, $Losses, $AntennaB, $Threshold, $attenuation, $Prim_Site_A , $Prim_Site_B , $Stand_Site_A , $Stand_Site_B );
	$RSSI = RSSI($FadeMargin,$version);
	
	$object[] = array(
	'FadeMargin ' => $FadeMargin['FM106'],
	'SignalLevel' => $FadeMargin['RX'], 
	'RSSI' => $RSSI['RSSI']
	);
	echo json_encode($object);
}
function getPrev_Calculations($conn, $ProdID, $Version, $Antenna_A_Diameter, $Antenna_B_Diameter, $Antenna_Height_A, $Antenna_Height_B, $Transmitter, $Extra_diameter_A, $Extra_diameter_B, $Antenna_Menu, $LatA, $LatB, $LonA, $LonB, $Frequency, $Losses, $Stand_Site_A, $Stand_Site_B, $Prim_Site_A, $Prim_Site_B, $Bandwidth, $Standart, $FEC, $Modulation, $Temperature, $Manufacturer, $Frequency_FD, $Transmit_FD)
{
	$params_AntennaA = GetAnthenaParams($conn, $Manufacturer, $Frequency, $Antenna_A_Diameter);
	$params_AntennaB = GetAnthenaParams($conn, $Manufacturer, $Frequency, $Antenna_B_Diameter);
	$Extra_params_AntennaA = 0;
	$Extra_params_AntennaB = 0; 
	if($Version == 4 || $Version == 3 || ($Version == 2 && $Antenna_Menu == 2))
	{
		$Extra_params_AntennaA = GetAnthenaParams($conn, $Manufacturer, $Frequency, $Extra_diameter_A);
		$Extra_params_AntennaB = GetAnthenaParams($conn, $Manufacturer, $Frequency, $Extra_diameter_B);
	}
	
	$Distance = calculateDistance($LatA, $LonA, $LatB, $LonB);
	$Threshold = getThreshold($conn, $Frequency, $Bandwidth, $Standart, $FEC, $Modulation, $ProdID);
	$a = Attenuation($Frequency, $Temperature , $LatA, $LatB, $Antenna_Height_A, $Antenna_Height_B, $Distance); 
	$FadeMargin = getFadeMargin($Antenna_Menu, $Version, $Frequency_FD, $Transmit_FD, $Distance, $Frequency, $Transmitter, $params_AntennaA, $Losses, $params_AntennaB, $Extra_params_AntennaA, $Extra_params_AntennaA, $Threshold, $a, $Prim_Site_A, $Prim_Site_B, $Stand_Site_A , $Stand_Site_B);
	$RSSI = RSSI($FadeMargin, $Version);
	if($Version != 4 && $Version != 3) $EIRP = max($params_AntennaA, $params_AntennaB) + $Transmitter;
	else $EIRP = max($params_AntennaA, $params_AntennaB, $Extra_params_AntennaA, $Extra_params_AntennaB) + $Transmitter;
	
	if($Version == 2 && $Antenna_Menu == 1)
	{
		$result[] = array(
		'RXThreshold' => $Threshold, 
		'FadeMargin_HSB' => round($FadeMargin['FM_HSB'], 2),
		'FadeMargin_Main' => round($FadeMargin['FM_Main'], 2),		
		'RSSI' => round($RSSI['RSSI'], 2),
		'RSSI_HSB' => round($RSSI['RSSI_HSB'], 2),
		'RX' => round($FadeMargin['RX'], 2), 
		'RX_HSB' => round($FadeMargin['RX_HSB'], 2),
		'EIRP' => $EIRP
		);	
	}
	else if($Version == 2 && $Antenna_Menu == 2)
	{
		$result[] = array(
		'RXThreshold' => $Threshold, 
		'FadeMargin' => round($FadeMargin['FM_HSB'], 2),
		'Rec_Sig_Level' => round($FadeMargin['RX'], 2),
		'RSSI' => round($RSSI['RSSI'], 2),
		'EIRP' => $EIRP
		);
	}
	else if($Version != 2)
	{
		$result[] = array(
		'RXThreshold' => $Threshold, 
		'FadeMargin' => round($FadeMargin['FM106'], 2),
		'Rec_Sig_Level' => round($FadeMargin['RX'], 2),
		'RSSI' => round($RSSI['RSSI'], 2),
		'EIRP' => $EIRP
		);	
	}
	echo json_encode($result);
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
		
		case 'Threshold': getThreshold_Echo($conn, $_POST['ProdID'], $_POST['Frequency'], $_POST['Bandwidth'], $_POST['Standart'], $_POST['FEC'], $_POST['Modulation']);
		break; 
		
		case 'FadeMargin': getFadeMargin_Echo($_POST['Coupler'], $_POST['Prim_Site_A'], $_POST['Prim_Site_B'], $_POST['Stand_Site_A'], $_POST['Stand_Site_B'], $_POST['Frequency'], $_POST['Temperature'], $_POST['LatA'], $_POST['LatB'], $_POST['distance'], $_POST['version'], $_POST['AntennaA'], $_POST['AntennaB'], $_POST['Threshold'], $_POST['Transmitter'], $_POST['Losses']);
		break;
		
		case 'prev_calc': getPrev_Calculations($conn, $_POST['ProdID'], $_POST['version'], $_POST['diameter_a'], $_POST['diameter_b'], $_POST['AntennaHeightA'], $_POST['AntennaHeightB'], $_POST['Transmitter'], $_POST['Extra_diameter_A'], $_POST['Extra_diameter_B'], $_POST['Antenna_Menu'], $_POST['LatA'], $_POST['LatB'],$_POST['LonA'], $_POST['LonB'], $_POST['Frequency'], $_POST['Losses'], $_POST['Stand_Site_A'], $_POST['Stand_Site_B'], $_POST['Prim_Site_A'],$_POST['Prim_Site_B'], $_POST['Bandwidth'], $_POST['Standart'], $_POST['FEC'], $_POST['Modulation'], $_POST['Temperature'], $_POST['Manufacturer'], $_POST['Frequency_FD'], $_POST['Transmit_FD']);	
		break; 
		
		case 'calc_res':
				$Distance = calculateDistance($_POST['LatA'], $_POST['LonA'], $_POST['LatB'], $_POST['LonB']);
				$result = array(
				'distance' => $Distance,
				'Product' => $_POST['ProdID'],
				'LatA' => $_POST['LatA'],
				'LonA' => $_POST['LonA'],
				'LatB' => $_POST['LatB'],
				'LonB' => $_POST['LonB'],
				'Antenna_Menu' => $_POST['Antenna_Menu'],
				'Version' => $_POST['version'],
				'Extra_diameter_A' => $_POST['Extra_diameter_A'],
				'Extra_diameter_B' => $_POST['Extra_diameter_B'],
				'Diameter_A' => $_POST['diameter_a'],
				'Diameter_B' => $_POST['diameter_b'],
				'Frequency' => $_POST['Frequency'],
				'Temperature' => $_POST['Temperature'],
				'Antenna_Height_A' => $_POST['AntennaHeightA'],
				'Antenna_Height_B' => $_POST['AntennaHeightB'],
				'Manufacturer' => $_POST['Manufacturer'],
				'TransmitPow' => $_POST['Transmitter'],
				'Band_Width' => $_POST['Bandwidth'], 
				'Standart' => $_POST['Standart'], 
				'FEC' => $_POST['FEC'], 
				'Modulation' => $_POST['Modulation'],
				'Frequency_FD' => $_POST['Frequency_FD'],
				'Transmit_FD' => $_POST['Transmit_FD'],
				'Losses' => $_POST['Losses'],
				'Prim_Site_A' => $_POST['Prim_Site_A'],
				'Prim_Site_B' => $_POST['Prim_Site_B'],
				'Stand_Site_A' => $_POST['Stand_Site_A'],
				'Stand_Site_B' => $_POST['Stand_Site_B'],
				'Temp_Rain_Zone' => $_POST['RainZone'],
				'SD_Sep_A' => $_POST['SD_Sep_A'],
				'SD_Sep_B' => $_POST['SD_Sep_B'],
				'Main_Freq' => $_POST['Main_Freq'],
				'Div_Freq' => $_POST['Div_Freq']		
				);
				Calculate_MainBlock($conn, $result); 
		break;
	}
}