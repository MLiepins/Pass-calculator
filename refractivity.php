<?php
session_start();
include 'connection.php';
include 'functions.php';
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

//Galvenais aprēķinu bloks. 
if(!empty($_GET))
{
if(isset($_GET['Odu'])) $Odu = $_GET['Odu']; 
else $Odu = 0;

if(empty($_GET['Antenna_Coupler'])) $Antenna_Coupler = 0;
else $Antenna_Coupler = $_GET['Antenna_Coupler'];

if(empty($_GET['MainFreq'])) $MainFreq = 0;//
else $MainFreq = $_GET['MainFreq'];
		
if(empty($_GET['DivFreq'])) $DivFreq = 0;//
else $DivFreq = $_GET['DivFreq'];		
		
if(isset($_GET['FrequencyFD'])) $FrequencyFD = $_GET['FrequencyFD'];//
else $FrequencyFD = 0; 
		
if(isset($_GET['TransmitterFD']))$TransmitPowFD = $_GET['TransmitterFD'];//
else $TransmitPowFD = 0; 
		
if(empty($_GET['prim_SiteA'])) $prim_SiteA = 0;//
else $prim_SiteA = $_GET['prim_SiteA'];

if(empty($_GET['prim_SiteB'])) $prim_SiteB = 0;//
else $prim_SiteB = $_GET['prim_SiteB'];
		
if(empty($_GET['stand_SiteA'])) $stand_SiteA = 0;
else $stand_SiteA = $_GET['stand_SiteA'];
		
if(empty($_GET['stand_SiteB'])) $stand_SiteB = 0;
else $stand_SiteB = $_GET['stand_SiteB'];

$BandwidthTMP =  explode('|',($_GET['Bandwidth'])); 

/*function Print_Product_ID($_GET['Productx1'], $Odu)
{
	$Product = ChooseProduct($_GET['Productx1'] , $Odu);
	echo $Product; 
}*/

$Variables = array(
	"Version" => $_GET['Version'],
	"Odu" => $Odu,
	"Product" => ChooseProduct($_GET['Productx1'] , $Odu),
	"LatA" => $_GET['LatitudeA'],
	"LonA" => $_GET['LongitudeA'],
	"LatB" => $_GET['LatitudeB'],
	"LonB" => $_GET['LongitudeB'], 
	"Temperature" => $_GET['Temperature'],
	"Antenna_Coupler" => $Antenna_Coupler,
	"Antenna_A" => $_GET['AnthenaA'],
	"Antenna_B" => $_GET['AnthenaB'],
	"Manufacturer" => $_GET['Manufacturer'],
	"Diameter_A_1" => $_GET['diameter1'],
	"Diameter_B_1" => $_GET['diameter2'],
	"Diameter_A_2" => $_GET['diameter3'],
	"Diameter_B_2" => $_GET['diameter4'],
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

}



function Calculate_MainBlock($conn, &$var)
{
	$distance = calculateDistance($var['LatA'], $var['LonA'], $var['LatB'], $var['LonB']);
	$Product = $var['Product']; 
	$PointRefractGrad = getPointRefractGrad($var['LatA'], $var['LonA'], $var['LatB'], $var['LonB']); 
	
	if($var['Antenna_Coupler'] == 2) 
	{
		$var['Diameter_A_2'] = $var['Diameter_A_1'];
		$var['Diameter_B_2'] = $var['Diameter_B_1'];
	}
	$a = Attenuation($var['Frequency'], $var['Temperature'] , $var['LatA'], $var['LatB'], $var['Antenna_A'],$var['Antenna_B'], $distance); 
	$sa = Sa($conn, $var['LatA'], $var['LonA'], $var['LatB'], $var['LonB']);
	$Antenna_A = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_A_1']);
	$Antenna_B = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_B_1']);
	$Antenna_ASD = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_A_2']);
	$Antenna_BSD = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_B_2']);
	if($var['Version'] != 4) $EIRP = max($Antenna_A, $Antenna_B) + $var['TransmitPow']; 
	else $EIRP = max($Antenna_A, $Antenna_B, $Antenna_ASD, $Antenna_BSD) + $var['TransmitPow'];
	
	$RXThreshold = getThreshold($conn, $var['Frequency'], $var['Band_Width'], $var['Standart'], $var['FEC'], $var['Modulation'], $Product);
	$FadeMargin = getFadeMargin($var['Antenna_Coupler'], $var['Version'], $distance, $var['Frequency'], $var['TransmitPow'], $Antenna_A, $var['Losses'], $Antenna_B, $RXThreshold, $a, $var['Prim_Site_A'] , $var['Prim_Site_B'] , $var['Stand_Site_A'] , $var['Stand_Site_B']);
	$Geoclimatic = Geoclimatic($PointRefractGrad, $sa, $var['Antenna_A'], $var['Antenna_B'], $distance);
	$SelectiveOutage = SelectiveOutage($var['Version'], $var['LatA'], $var['LatB'], $Geoclimatic, $distance, $var['Frequency'], $FadeMargin );
	$Selective = Selective($SelectiveOutage, $distance, $conn, $var['Band_Width'], $var['Modulation'], $Product );
	$RainRate = RainRate($conn, $var['Version'], $var['Frequency'], $var['Temp_Rain_Zone'], $distance, $FadeMargin, $SelectiveOutage ); 
	$PlotRain = PlotRain($RainRate);
	$RSSI = RSSI($FadeMargin, $var['Version']);
	$MaxTransmitterPower = MaxTransmitterPower($conn, $Product, $var['Frequency'], $var['Modulation']);
	$GetMaxCap = GetMaxCap($conn, $Product, $var['Modulation'], $var['Band_Width'], $var['Standart'], $var['FEC']);
	
		if($var['Version'] == 2) $FM = $FadeMargin['FM_Main'];
		else $FM = $FadeMargin['FM106'];
		
		//echo $FM; 
		if($FM > 0)
		{
			if($var['Version'] == 1)
			{
				if($Product == 1 || $Product == 2 || $Product == 3 || $Product == 4 || $Product == 6 || $Product = 8)
				{
					$TotalMultipath = TotalMultipath($SelectiveOutage, $Selective); 
					$MultipathRain = MultipathRain($RainRate, $TotalMultipath);
					$RainAvailVert = $PlotRain['PlotRain100']['Plot2V'];
					$MRainAvailVert = $MultipathRain['m3p2v'];
					$RainAvailHor = $PlotRain['PlotRain100']['Plot2H'];
					$MRainAvailHor = $MultipathRain['m3p2h'];
					$ErroredTime = ErroredTime($MRainAvailVert, $MRainAvailHor);
					$Output_ErroredTimeV = $ErroredTime['V_Hours'].":".$ErroredTime['V_Mins'];
					$Output_ErroredTimeH = $ErroredTime['H_Hours'].":".$ErroredTime['H_Mins'];
					if($TotalMultipath['TotalPath106'] > 0) $MPAvailabilityVert = $TotalMultipath['TotalPath106'];
					else $MPAvailabilityVert = "NA";
				}
				else echo "Not available";
			}	
			if($var['Version'] == 3)
			{
				if($Product == 4 || $Product == 5 || $Product == 6 || $Product == 7 || $Product == 8 || $Product = 9)
				{
					$SD = SD($var['Frequency_FD'], $var['Amount_Of_Antenas'], $var['Version'], $var['Main_Freq'], $var['Div_Freq'], $Selective, $FadeMargin, $var['SD_Sep_A'], $var['SD_Sep_B'], $Antenna_A, $Antenna_ASD, $Antenna_B, $Antenna_BSD, $var['Frequency'], $distance, $SelectiveOutage); 
					$TotalMultipath = TotalMultipath($SelectiveOutage, $Selective); 
					$MultipathRain = MultipathRain11($SD, $RainRate);
					$RainAvailVert = $PlotRain['PlotRain100']['Plot2V'];
					$MRainAvailVert =  $MultipathRain['mp2v'];
					$RainAvailHor = $PlotRain['PlotRain100']['Plot2H'];
					$MRainAvailHor =  $MultipathRain['mp2h'];
					$ErroredTime = ErroredTime($MRainAvailVert, $MRainAvailHor);
					$Output_ErroredTimeV = $ErroredTime['V_Hours'].":".$ErroredTime['V_Mins'];
					$Output_ErroredTimeH = $ErroredTime['H_Hours'].":".$ErroredTime['H_Mins'];
					if($SD['Pt3'] > 0 ) $MPAvailabilityVert = $SD['Pt3'];
					else $MPAvailabilityVert = "NA";
				}
				else echo "Not available";
			}	
			if($var['Version'] == 4)
			{
					if($Product == 4 || $Product == 5 || $Product == 6 || $Product == 7 || $Product == 8 || $Product = 9)
					{
						$SD = SD($var['Frequency_FD'] , $var['Amount_Of_Antenas'], $var['Version'], $var['Main_Freq'], $var['Div_Freq'], $Selective, $FadeMargin, $var['SD_Sep_A'], $var['SD_Sep_B'], $Antenna_A, $Antenna_ASD, $Antenna_B, $Antenna_BSD, $var['Frequency'], $distance, $SelectiveOutage); 
						$TotalMultipath = TotalMultipath($SelectiveOutage, $Selective); 
						$MultipathRain = MultipathRain11($SD, $RainRate);
						$RainAvailVert = $PlotRain['PlotRain100']['Plot2V'];
						$MRainAvailVert =  $MultipathRain['mp2v'];
						$RainAvailHor = $PlotRain['PlotRain100']['Plot2H'];
						$MRainAvailHor =  $MultipathRain['mp2h'];
						$ErroredTime = ErroredTime($MRainAvailVert, $MRainAvailHor);
						$Output_ErroredTimeV = $ErroredTime['V_Hours'].":".$ErroredTime['V_Mins'];
						$Output_ErroredTimeH = $ErroredTime['H_Hours'].":".$ErroredTime['H_Mins'];
						if($SD['Pt3'] > 0 ) $MPAvailabilityVert = $SD['Pt3'];
						else $MPAvailabilityVert = "NA";
					}
					else echo "Not available";							
			} 
			if($var['Version'] == 2)
			{
				if($Product == 4 || $Product == 5 || $Product == 6 || $Product == 7 || $Product == 8 || $Product == 9)
				{ 
					$TotalMultipath = TotalMultipath($SelectiveOutage, $Selective); 
					$MultipathRain = MultipathRain($RainRate, $TotalMultipath);
					$RainAvailVert = $PlotRain['PlotRain100']['Plot2V'];
					$MRainAvailVert = $MultipathRain['m3p2v'];
					$RainAvailHor = $PlotRain['PlotRain100']['Plot2H'];
					$MRainAvailHor = $MultipathRain['m3p2h'];
					$ErroredTime = ErroredTime($MRainAvailVert, $MRainAvailHor);
					$ErroredTime_Standby = ErroredTime($MultipathRain['m3p1v'], $MultipathRain['m3p1h']);
					$Output_ErroredTimeV = $ErroredTime['V_Hours'].":".$ErroredTime['V_Mins'];
					$Output_ErroredTimeH = $ErroredTime['H_Hours'].":".$ErroredTime['H_Mins'];
					$Output_ErroredTimeV_Standby = $ErroredTime_Standby['V_Hours'].":".$ErroredTime_Standby['V_Mins'];
					$Output_ErroredTimeH_Standby = $ErroredTime_Standby['H_Hours'].":".$ErroredTime_Standby['H_Mins'];
					if($TotalMultipath['TotalPath106'] > 0 ) $MPAvailabilityVert =$TotalMultipath['TotalPath106'];
					else $MPAvailabilityVert = "NA";
				}
				else echo "Not available";
			}
			$MpthVert = $MPAvailabilityVert; 
			$MpthHor = $MPAvailabilityVert;	
		}
		if(!isset($Output_ErroredTimeV_Standby) && !isset($Output_ErroredTimeH_Standby)) 
		{
			$Output_ErroredTimeV_Standby = 0; 
			$Output_ErroredTimeH_Standby = 0; 
		}
		else 
		{	
			$Output_ErroredTimeV = 'NA';
			$Output_ErroredTimeH = 'NA';
			$MpthVert = 'NA';
			$MpthHor = 'NA';
			$RainAvailVert = 'NA';
			$MRainAvailVert = 'NA';
			$RainAvailHor = 'NA';
			$MRainAvailHor = 'NA'; 
		}
		//$Output_Threshold = $RXThreshold; 
		$InputsResult = $FadeMargin['FSL']." ".$FadeMargin['RX']; 
		$Cord_Result = $var['LatA']." ".$var['LonA']." ".$var['LatB']." ".$var['LonB']." Distance: ".$distance;
		
		$out['Output_ErroredTimeV'] = $Output_ErroredTimeV; 
		$out['Output_ErroredTimeH'] = $Output_ErroredTimeH;
		$out['Output_ErroredTimeV_Standby'] = $Output_ErroredTimeV_Standby; 
		$out['Output_ErroredTimeH_Standby'] = $Output_ErroredTimeH_Standby; 
		
		$out['MpthVert'] = $MpthVert; 
		$out['MpthHor'] = $MpthHor;
		$out['RainAvailVert'] = $RainAvailVert;
		$out['MRainAvailVert'] = $MRainAvailVert;
		$out['RainAvailHor'] = $RainAvailHor;
		$out['MRainAvailHor'] = $MRainAvailHor; 
		$out['RXThreshold'] = $RXThreshold;
		$out['Fade_Margin_FSL'] = $FadeMargin['FSL'];
		$out['Fade_Margin_RX'] = $FadeMargin['RX'];
		$out['Distance'] = $distance; 
		$out['Max_Capacity'] = $GetMaxCap; 
		$out['Rain_Rate'] = $RainRate['RainRate'];
		$out['Max_Transmitter_Power'] = $MaxTransmitterPower;
		$out['Product'] = $Product;
		$out['Fade_Margin'] = $FadeMargin;
		$out['RS_SI'] = $RSSI; 
		$out['Version'] = $var['Version']; 
		$out['EIRP'] = $EIRP; 
		$out['Total_Multipath'] = $TotalMultipath; 
		$out['Plot_Rain'] = $PlotRain;
		$out['Multipath_Rain'] = $MultipathRain; 
		
 		return $out; 
		
}

if( isset( $_REQUEST['Dec'] ))
{
	$results = Calculate_MainBlock($conn, $Variables);
	
}

?>	
<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta name="description" content="Register">
	<link href="../Style/css.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="ajax.js"></script>
	<title>Lapa</title>
	</head>
	<body>
	<form class="form-DecDeg" method="GET">
	<style> 
		table 
		{
			border-collapse: collapse;
			width: 30%;
		}

		td 
		{
			height: 10px;
		}
	</style>
	<table border = "1">
	<tr>
		<td><h4>Version: </h4></td>
		<td>
			<select name = "Version" id = "Version" class = "Version">
				<option value="0">Please choose version</option>
				<option value="1">1+0</option>
				<option value="2">1+1 HSB</option>
				<option value="3">1+1 SD</option>
				<option value="4">1+1 FD</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><h4>Select Product: </h4></td>
		<td>
			<select name = "Productx1" id = "Products" class = "Products">
				<option value="0">First choose version</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><h4 class = "odu">Odu type</h4></td>
		<td>
			<select name = "Odu" class = "odu">
				<option value="0">Choose odu type</option>
				<option value="1">Phoenix ODU</option>
				<option value="2">R Odu</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><h4>A Lat/Lon</h4></td>
		<td><input type="text" class ="LatitudeA" name="LatitudeA" placeholder="Latitude A" size="10" value ="<?php if(isset($_GET['LatitudeA'])){ echo $_GET['LatitudeA'];} else{ echo 1;} ?>"></td>
		<td><input type="text" class ="LongitudeA" name="LongitudeA" placeholder="Longitude A" size="10" value ="<?php if(isset($_GET['LongitudeA'])){ echo $_GET['LongitudeA'];} else{ echo 1;} ?>"></td>
	</tr>
	<tr>
		<td><h4>B Lat/Lon</h4></td>
		<td><input type="text" class ="LatitudeB" name="LatitudeB" placeholder="Latitude B" size="10" value ="<?php if(isset($_GET['LatitudeB'])){ echo $_GET['LatitudeB'];} else{ echo 1;} ?>"></td>
		<td><input type="text" class ="LongitudeB" name="LongitudeB" placeholder="Longitude B" size="10" value ="<?php if(isset($_GET['LongitudeB'])){ echo $_GET['LongitudeB'];} else{ echo 1;} ?>"></td>
	</tr>
	
	
	<tr>
		<td><h4>Radio modulation</h4></td>
		<td>
			<select name = "rModulation" class = "rModulation">
			<option value="4QAM">4QAM</option>
			<option value="16QAM">16QAM</option>
			<option value="32QAM">32QAM</option>
			<option value="64QAM">64QAM</option>
			<option value="128QAM">128QAM</option>
			<option value="256QAM">256QAM</option>
			<option value="512QAM">512QAM</option>
			<option value="1024QAM">1024QAM</option>	
		</select></td>
	</tr>
	
	</table> 


	<br>Result: <?php if(!empty($_GET)) echo htmlspecialchars($results['Distance'])?>
	<br><br><br><br>
	<h2>Inputs</h2>
		<table border="1" style="width:30%" >
		<tr>
		<td><h4> FEC </h4></td>
		<td><select name = "FEC" method = "GET" > 
			<option value="0">Strong FEC</option>
			<option value="1">Weak FEC</option>
		</select></td>
		</tr>
		<tr>
		<td><h4>Rainzone </h4></td>
		<td><select name = "Rainzone" method = "GET">
			<option value="A">A</option>
			<option value="B">B</option>
			<option value="C">C</option>
			<option value="D">D</option>
			<option value="E">E</option>
			<option value="F">F</option>
			<option value="G">G</option>
			<option value="H">H</option>
			<option value="J">J</option>
			<option value="K">K</option>
			<option value="L">L</option>
			<option value="M">M</option>
			<option value="N">N</option>
			<option value="P">P</option>
			<option value="Q">Q</option>
		</select></td>
		</tr>
		<tr>
		<td><h4>Temperature</h4></td> 
		<td><input type="text" name="Temperature" placeholder="Temperature" value ="<?php if(isset($_GET['Temperature'])){ echo $_GET['Temperature'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr>
		<td><h4>Transmitter power</h4></td>
		<td><input type="text" name="Transmitter" placeholder="Transmitter Power" value ="<?php if(isset($_GET['Transmitter'])){ echo $_GET['Transmitter'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr>
		<td><h4>Frequency</h4></td>
		<td><select name = "Frequency" method = "GET">
			<option value="6">6</option>	
			<option value="11">11</option>
			<option value="15">15</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="26">26</option>
			<option value="38">38</option>
			<option value="42">42</option>
		</select></td>
		</tr>
		<tr class = "prod4">
		<td><h4>Main frequency: </h4></td>
		<td><input type="text" name="MainFreq" placeholder="Main frequency" value ="<?php if(isset($_GET['MainFreq'])){ echo $_GET['MainFreq'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "prod4">
		<td><h4>Diversity frequency</h4></td>
		<td><input type="text" name="DivFreq" placeholder="Diversity frequency" value ="<?php if(isset($_GET['DivFreq'])){ echo $_GET['DivFreq'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "AntennasAm">
			<td><h4>Amount of antennas: </h4></td>
			<td><select name = "AntennasAmount" id = "AntennasAm">
				<option value="0">2 antennas</option>	
				<option value="1">4 antennas</option>
			</select></td>
		</tr>
		<tr class = "Antenna_Coupler">
			<td><h4>Amount of antennas: </h4></td>
			<td><select name = "Antenna_Coupler" id = "Antenna_Coupler">
				<option value="0">Please choose</option>	
				<option value="1">Using 2 antennas and coupler</option>	
				<option value="2">Using 4 antennas</option>
			</select></td>
		</tr>	
		<tr class = "Coupler">
		<td><h4>Coupler attenuations:<br>Primary link: </h4></td>
		<td>Site A: <input type="text" name="prim_SiteA" value ="<?php if(isset($_GET['prim_SiteA'])){ echo $_GET['prim_SiteA'];} else{ echo 1;} ?>"></td>
		<td>Site B: <input type="text" name="prim_SiteB" value ="<?php if(isset($_GET['prim_SiteB'])){ echo $_GET['prim_SiteB'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "Coupler">
		<td><h4>Coupler attenuations:<br>Standby link: </h4></td>
		<td>Site A: <input type="text" name="stand_SiteA" value ="<?php if(isset($_GET['prim_SiteA'])){ echo $_GET['prim_SiteA'];} else{ echo 1;} ?>"></td>
		<td>Site B: <input type="text" name="stand_SiteB" value ="<?php if(isset($_GET['prim_SiteB'])){ echo $_GET['prim_SiteB'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "field3">
		<td><h4>Transmitter power FD: </h4></td>
		<td><input type="text" name="TransmitterFD" placeholder="Transmitter Power" value ="<?php if(isset($_GET['Transmitter'])){ echo $_GET['Transmitter'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "field3">
		<td><h4>Frequency FD: </h4></td>
		<td><select name = "FrequencyFD" method = "GET">
			<option value="6">6</option>	
			<option value="11">11</option>
			<option value="15">15</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="26">26</option>
			<option value="38">38</option>
			<option value="42">42</option>
		</select></td>
		</tr>		
		<tr>
		<td><h4>Channel bandwidth</h4></td>
		<td><select name = "Bandwidth">
			<option value="3.5|ETSI">3.5(ETSI)</option>
			<option value="5|FCC">5(FCC)</option>
			<option value="7|ETSI">7(ETSI)</option>
			<option value="10|FCC">10(FCC)</option>
			<option value="14|ETSI">14(ETSI)</option>
			<option value="20|ETSI">20(ETSI)</option>
			<option value="20|FCC">20(FCC)</option>
			<option value="25|FCC">25(FCC)</option>
			<option value="28|ETSI">28(ETSI)</option>
			<option value="30|FCC">30(FCC)</option>
			<option value="40|ETSI">40(ETSI)</option>
			<option value="40|FCC">40(FCC)</option>
			<option value="50|FCC">50(FCC)</option>
			<option value="56|ETSI">56(ETSI)</option>
			<option value="60|FCC">60(FCC)</option>
			<option value="80|FCC">80(FCC)</option>
			<option value="100|FCC">100(FCC)</option>
			<option value="112|ETSI">112(ETSI)</option>
		</select></td>
		</tr>
		<tr>		
		<td><h4>Manufacturer </h4></td>
		<td><select name = "Manufacturer" method = "GET">
			<option value="1">Integrated</option>
			<option value="2">Andrew</option>
			<option value="3">LEAX Arkivator</option>
		</select></td>
		</tr>
		<tr>
		<td>Antenna heights</td>
		<td>A: <input type="text" name="AnthenaA" placeholder="AnthenaA" value ="<?php if(isset($_GET['AnthenaA'])){ echo $_GET['AnthenaA'];} else{ echo 1;} ?>"><br>B: <input type="text" name="AnthenaB" placeholder="AnthenaB" value ="<?php if(isset($_GET['AnthenaB'])){ echo $_GET['AnthenaB'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr>
		<td><h4>Anthena A diameter</h4></td>
		<td><select name = "diameter1" method = "GET">
			<option value="0.2">0.2</option>
			<option value="0.3">0.3</option>
			<option value="0.6">0.6</option>
			<option value="0.9">0.9</option>
			<option value="1">1</option>
			<option value="1.2">1.2</option>
			<option value="1.8">1.8</option>
			<option value="2.4">2.4</option>
			<option value="3">3</option>
			<option value="3.7">3.7</option>
			<option value="4.6">4.6</option>
		</select></td>
		</tr>
		<tr>
		<td><h4>Anthena B diameter</h4></td>
		<td><select name = "diameter2" method = "GET">
			<option value="0.2">0.2</option>
			<option value="0.3">0.3</option>
			<option value="0.6">0.6</option>
			<option value="0.9">0.9</option>
			<option value="1">1</option>
			<option value="1.2">1.2</option>
			<option value="1.8">1.8</option>
			<option value="2.4">2.4</option>
			<option value="3">3</option>
			<option value="3.7">3.7</option>
			<option value="4.6">4.6</option>
		</select></td>	
		</tr>
		<tr class = "ExtraAntennas">
		<td><h4>Anthena A SD diameter</h4></td>
		<td><select name = "diameter3" method = "GET">
			<option value="0.2">0.2</option>
			<option value="0.3">0.3</option>
			<option value="0.6">0.6</option>
			<option value="1">1</option>
			<option value="1.2">1.2</option>
			<option value="1.8">1.8</option>
			<option value="2.4">2.4</option>
			<option value="3">3</option>
			<option value="3.7">3.7</option>
			<option value="4.6">4.6</option>
		</select></td>	
		</tr>
		<tr class = "ExtraAntennas" >
		<td><h4>Anthena B SD diameter</h4></td>
		<td><select name = "diameter4" method = "GET">
			<option value="0.2">0.2</option>
			<option value="0.3">0.3</option>
			<option value="0.6">0.6</option>
			<option value="1">1</option>
			<option value="1.2">1.2</option>
			<option value="1.8">1.8</option>
			<option value="2.4">2.4</option>
			<option value="3">3</option>
			<option value="3.7">3.7</option>
			<option value="4.6">4.6</option>
		</select></td>	
		</tr>
		<tr>
		<td><h4>Losses</h4></td>
		<td><input type="text" name="Losses" placeholder="Losses" value ="<?php if(isset($_GET['Losses'])){ echo $_GET['Losses'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "prod3">
		<td><h4>SD separation for A:</h4></td>
		<td><input type="text" name="SDsepA" placeholder="SDsepA" value ="<?php if(isset($_GET['SDsepA'])){ echo $_GET['SDsepA'];} else{ echo 1;} ?>"></td>
		</tr>
		<tr class = "prod3">
		<td><h4>SD separation for B:</h4></td>
		<td><input type="text" name="SDsepB" placeholder="SDsepB" value ="<?php if(isset($_GET['SDsepB'])){ echo $_GET['SDsepB'];} else{ echo 1;} ?>"></td>
		</tr>
		</table>
		<input type="submit" name = "Dec" value="Get Value">
		<br>Result: <?php if(!empty($_GET)) echo htmlspecialchars($results['Fade_Margin_FSL']." ".$results['Fade_Margin_RX'])?>
		</form>
		<br><br><br><hr>Final results<br>
		 <table border="1" style="width:30% ">
		<tr>
			<td>For capacities up to:: </td>
			<td> <?php echo htmlspecialchars($results['Max_Capacity'])." Mbps"?></td>
		</tr>	
		 <tr>
			<td>Rain zone: </td>
			<td> <?php echo htmlspecialchars($results['Rain_Rate'])." mm/h"?></td>
		</tr>	
		 <tr>
			<td>Max. Transmit. Power:</td>
			<td>
			<?php 
				if($results['Product'] != 5 && $results['Product'] != 7 && $results['Product'] != 9)
				{
					if($results['Max_Transmitter_Power']['MinTXforSP'] != NULL && $results['Max_Transmitter_Power']['MinTXforSP'] != 0 ) echo " ".htmlspecialchars($results['Max_Transmitter_Power']['MinTXforSP'])."  ";
					if($results['Max_Transmitter_Power']['MaxTXforSP'] != NULL && $results['Max_Transmitter_Power']['MaxTXforSP'] != 0) echo " Max. Tx power for SP radio: ".htmlspecialchars($results['Max_Transmitter_Power']['MaxTXforSP'])."  ";
					if($results['Max_Transmitter_Power']['MinTXforHP'] != NULL && $results['Max_Transmitter_Power']['MinTXforHP'] != 0) echo " ".htmlspecialchars($results['Max_Transmitter_Power']['MinTXforHP'])."  ";
					if($results['Max_Transmitter_Power']['MaxTXforHP'] != NULL && $results['Max_Transmitter_Power']['MaxTXforHP'] != 0) echo " Max TX power for HP radio:".htmlspecialchars($results['Max_Transmitter_Power']['MaxTXforHP'])."  ";
				}
				else
				{
					echo "Max. Tx power for SP radio: ".htmlspecialchars($results['Max_Transmitter_Power']['MaxTXforHP'])."  ";
				}
			?>
			</td>
		</tr>		
		<tr class = "HSB_hide">
			<td>Received signal level:</td>
			<td> <?php echo htmlspecialchars(round($results['Fade_Margin']['RX'],2))?></td>
		</tr>
		<tr class = "HSB_hide">
			<td>RSSI:</td>
			<td> <?php echo htmlspecialchars(round($results['RS_SI']['RSSI'],2))?></td>
		</tr>
		<tr class = "HSB_hide">
			<td>Fade margin:</td>
			<td> <?php 
					if($results['Version'] != 2)echo htmlspecialchars(round($results['Fade_Margin']['FM106'],2));
					else echo htmlspecialchars(round($results['Fade_Margin']['FM_Main'],2))
				?></td>
		</tr>
		 <tr class = "HSB_hide" >
			<td>Rx Threshold:</td>
			<td> <?php echo htmlspecialchars($results['RXThreshold'])?></td>
		</tr>
		<tr  class = "HSB_hide" >
			<td>EIRP:</td>
			<td> <?php echo htmlspecialchars($results['EIRP'])?></td>
		</tr>
		<tr class = "HSB_hide">
			<td>Multipath Availability </td>
			<td>vert: <?php echo htmlspecialchars($results['MpthVert'])?></td>
			<td>hor: <?php echo htmlspecialchars($results['MpthHor'])?></td>
		</tr >
		<tr class = "HSB_hide">
			<td>Rain Availability</td>
			<td>vert: <?php echo htmlspecialchars($results['RainAvailVert'])?></td>
			<td>hor: <?php echo htmlspecialchars($results['RainAvailHor'])?></td>
		</tr>
		<tr class = "HSB_hide">
			<td>Multipath+Rain Availability (%):</td>
			<td>vert: <?php echo htmlspecialchars($results['MRainAvailVert'])?></td>
			<td>hor: <?php echo htmlspecialchars($results['MRainAvailHor'])?></td>
		</tr>
		<tr class = "HSB_hide">
			<td>Errored time per year(Hours:Minutes):</td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeV'])?></td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeH'])?></td>
		</tr>
	</table>
	<hr><br><br><br><br>
	<table border="1" style="width:30% ">
		<tr class = "HSB_Show">
			<td>Received signal level:</td>
			<td>Primary Link:  <?php echo htmlspecialchars(round($results['Fade_Margin']['RX'],2))?></td>
			<td>Standby Link:  <?php echo htmlspecialchars(round($results['Fade_Margin']['RX_HSB'],2))?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>RSSI:</td>
			<td> Primary Link: <?php echo htmlspecialchars(round($results['RS_SI']['RSSI'],2))?></td>
			<td> Standby Link: <?php echo htmlspecialchars(round($results['RS_SI']['RSSI_HSB'],2))?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>Fade margin:</td>
			<td> Primary Link: <?php echo htmlspecialchars(round($results['Fade_Margin']['FM_Main'],2))?></td>
			<td> Standby Link: <?php echo htmlspecialchars(round($results['Fade_Margin']['FM_HSB'],2))?></td>
		</tr>
		 <tr class = "HSB_Show" >
			<td>Rx Threshold:</td>
			<td> <?php echo htmlspecialchars($results['RXThreshold'])?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>EIRP:</td>
			<td> <?php echo htmlspecialchars($results['EIRP'])?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>Multipath Availability </td>
			<td>Primary link: vert: <?php echo htmlspecialchars($results['Total_Multipath']['Availability106'])?></td>
			<td>Primary link: hor: <?php echo htmlspecialchars($results['Total_Multipath']['Availability106'])?></td>
			<td>Standby link: vert: <?php echo htmlspecialchars($results['Total_Multipath']['Availability103'])?></td>
			<td>Standbylink: hor: <?php echo htmlspecialchars($results['Total_Multipath']['Availability103'])?></td>
		</tr >	
		<tr class = "HSB_Show">
			<td>Rain Availability</td>
			<td>Primary link: vert: <?php echo htmlspecialchars($results['Plot_Rain']['PlotRain100']['Plot2V'])?></td>
			<td>Primary link: hor: <?php echo htmlspecialchars($results['Plot_Rain']['PlotRain100']['Plot2H'])?></td>
			<td>Standby link: vert: <?php echo htmlspecialchars($results['Plot_Rain']['PlotRain100']['Plot1V'])?></td>
			<td>Standby link: hor: <?php echo htmlspecialchars($results['Plot_Rain']['PlotRain100']['Plot1H'])?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>Multipath+Rain Availability (%):</td>
			<td>Primary link: vert: <?php echo htmlspecialchars($results['Multipath_Rain']['m3p2v'])?></td>
			<td>Primary link: hor: <?php echo htmlspecialchars($results['Multipath_Rain']['m3p2h'])?></td>
			<td>Standby link: vert: <?php echo htmlspecialchars($results['Multipath_Rain']['m3p1v'])?></td>
			<td>Standby link: hor: <?php echo htmlspecialchars($results['Multipath_Rain']['m3p1h'])?></td>
		</tr>
		<tr class = "HSB_Show">
			<td>Errored time per year(Hours:Minutes):</td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeV'])?></td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeH'])?></td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeV_Standby'])?></td>
			<td> <?php echo htmlspecialchars($results['Output_ErroredTimeH_Standby'])?></td>				
		</tr>
		</table> 
	</body>
</html>
