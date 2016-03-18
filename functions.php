<?php
include 'connection.php';

function findRef($cord1, $cord2)
{
		include '../Auth/connection.php';
		$cord1Ref = findClosest($cord1);
		$cord2Ref = findClosest($cord2);
		$sql = "SELECT Refr FROM refractivity WHERE Lat =  '$cord1Ref' and Lon = '$cord2Ref'";
		$result = $conn->query($sql);
		$data = mysqli_fetch_array($result);
		$result = $data[0];
		return $result; 
}
function findClosestLatitude($num)
{
	$prev = 0; 
	if($num == 0) return $num; 
	if($num < 0)
	{
		$prev = -90; 
		for($i = -90; $i<=0; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
				//echo " ".$i." ".$num." "; 
				$temp1 = $num - $i;
				$temp2 = ($prev + ($num*-1));
				//echo $temp1. " ".$temp2." "; 
				if($temp1 > $temp2) 
				{
					$num = $i;
					break;
				}
				else
				{
					$num = $prev; 
					break; 
				}
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
	else
	{
		$prev = 0; 
		for($i = 0; $i <= 90; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
				$temp1 = $i - $num;
				$temp2 = $num - $prev;
				if($temp1 < $temp2) 
				{
					$num = $i;
					break;
				}
				else
				{
					$num = $prev; 
					break; 
				}
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
    return $num;
}
function findClosest($num)
{
	$prev = 0; 
	if($num == 0) return $num; 
	if($num < 0)
	{
		$prev = -180; 
		for($i = -180; $i<=0; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
				//echo " ".$i." ".$num." "; 
				$temp1 = $num - $i;
				$temp2 = ($prev + ($num*-1));
				//echo $temp1. " ".$temp2." "; 
				if($temp1 > $temp2) 
				{
					$num = $i;
					break;
				}
				else
				{
					$num = $prev; 
					break; 
				}
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
	else
	{
		$prev = 0; 
		for($i = 0; $i <= 180; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
				$temp1 = $i - $num;
				$temp2 = $num - $prev;
				if($temp1 < $temp2) 
				{
					$num = $i;
					break;
				}
				else
				{
					$num = $prev; 
					break; 
				}
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
    return $num;
}

function findClosestLower($num)
{
	$prev = 0; 
	if($num == 0) return $num; 
	if($num < 0)
	{
		$prev = -180; 
		for($i = -180; $i<=0; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
					$num = $prev; 
					break; 
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
	else
	{
		$prev = 0; 
		for($i = 0; $i <= 180; $i = $i + 1.5)
		{
			if($num > $prev && $num < $i)
			{
					$num = $prev; 
					break; 
			}
			else
			{
				$prev = $i;
			}
		}
		
	}
    return $num;
}

function convertDegToDec($Deg, $Min, $Sec)
{
	$result = number_format(($Deg + $Min/60 + $Sec/3600)*2/2,7);
	return $result;	
}
function calculateDistance($LatA, $LonA, $LatB, $LonB)
{
	$Rmax = 6378.135;//AX4
	$Rmin = 6356.75;//AX6
	$latFrom = deg2rad($LatA);
	$lonFrom = deg2rad($LonA);
	$latTo = deg2rad($LatB);
	$lonTo = deg2rad($LonB);
	//echo $latTo." ".$lonTo; 
	//echo $LatA." ".$LonA." ".$LatB." ".$LonB;  
	$AY9 = ($latFrom+$latTo)/2;
	$dLon = $lonTo - $lonFrom; 
	$Radius  = pow($Rmax, 2)/(sqrt(pow(($Rmax * cos($AY9)), 2) + pow(($Rmin * sin($AY9)),2)));
	$a = pow(cos($latTo) * sin($dLon), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($dLon), 2);
	$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($dLon);
	$angle = atan2(sqrt($a), $b);
	$distance = $angle * $Radius; 
	return $distance; 
}
function getPointRefractGrad($LatA, $LonA, $LatB, $LonB)
{	
	include '../Auth/connection.php';
	$vLat = (($LatA + $LatB)/2);
	$vLon = (($LonA + $LonB)/2);  
	//echo $vLat."  ".$vLon; 
	$stepLat = $vLat/1.5;
	$stepLon = $vLon/1.5;
	$point1Lat = ceil(abs($stepLat)) - 1;
	$point2Lat = ceil(abs($stepLat));
	$point1Lon = ceil(abs($stepLon)) - 1;
	$point2Lon = ceil(abs($stepLon));
	$addr11 = 0;
	$addr12 = 0;
	$addr13 = 0;
	$addr14 = 0;
	$addr21 = 0;
	$addr22 = 0;
	$addr23 = 0;
	$addr24 = 0;
	$result1 = 0;
	$result2 = 0; 
	
	if($vLat > 0)
	{
		$addr14 = 61 - $point1Lat;
		$addr24 = 61 - $point2Lat; 
		$addr11 = $addr24;
		$addr21 = $addr14; 
		$result1 = 61 - abs($stepLat);
	}
	else
	{
		$result1 = 61 + abs($stepLat);
		$addr14 = 61 + $point1Lat; 
		$addr24 = 61 + $point2Lat; 
		$addr11 = $addr14;
		$addr21 = $addr24; 
	}
	if($vLon > 0)
	{
		$addr13 = $point1Lon + 1;
		$addr23 = $point2Lon + 1; 
		$addr12 = $addr13;
		$addr22 = $addr23; 
		$result2 = abs($stepLon) + 1; 
	}
	else
	{
		$addr13 = 241 - $point1Lon;
		$addr23 = 241 - $point2Lon; 
		$addr12 = $addr23;
		$addr22 = $addr13; 
		$result2 = 241 - abs($stepLon);
	}

	
	$MidLat = findClosestLatitude($vLat);
	$MidLon = findClosestLower($vLon);
	//echo $MidLat." ".$MidLon; 
	//echo $vLat;
	if($MidLon == -180) $MidLon = $MidLon * -1; 
	else $MidLat = findClosestLatitude($vLat + 1);
	//echo $MidLat."  ".$MidLon;
	$tmp1 = $MidLat; 
	$tmp2 = $MidLon; 
	$result = $conn->query("SELECT Refr FROM refractivity WHERE Lat =  '$MidLat' and Lon = '$MidLon'");
	$v1 = mysqli_fetch_array($result); 	
	//echo " v1 ".$v1[0]." "; 

	if($MidLon == 180) $tmp2 = findClosest(($MidLon * (-1)) + 1);
	else $tmp2 = findClosest($tmp2 + 1);
	$result = $conn->query("SELECT Refr FROM refractivity WHERE Lat =  '$tmp1' and Lon = '$tmp2'");
	$v2 = mysqli_fetch_array($result); 
	$tmp2 = $MidLon; 
	//echo " v2 ".$v2[0]." ";
	
	$tmp1 = findClosest($tmp1 - 1); 
	$result = $conn->query("SELECT Refr FROM refractivity WHERE Lat =  '$tmp1' and Lon = '$tmp2'");
	$v3 = mysqli_fetch_array($result);
	//echo " v3 ".$v3[0]." ";	
	
	if($MidLon == 180) $tmp2 = findClosest(($MidLon * (-1)) + 1);
	else $tmp2 = findClosest($tmp2 + 1);
	$result = $conn->query("SELECT Refr FROM refractivity WHERE Lat =  '$tmp1' and Lon = '$tmp2'");
	$v4 = mysqli_fetch_array($result); 
	//echo " v4 ".$v4[0]." ";
	
	$tmp1 = $MidLat; 
	$PointRefractGrad = $v1[0] * ($addr21 - $result1) * ($addr22 - $result2)+ $v3[0] * ($result1 -$addr11)* ($addr22 - $result2) + $v2[0] * ($addr21 - $result1) * ($result2 - $addr12) + $v4[0] * ($result1 - $addr11) * ($result2 - $addr12);
	//echo $PointRefractGrad; 
	return $PointRefractGrad; 
}
function getFadeMargin($Antenna_Menu, $Version, $Frequency_FD, $Transmit_FD,  $distance, $Frequency, $TransmitPow, $AntennaA, $Losses, $AntennaB, $Antenna_A_Extra, $Antenna_B_Extra, $RXThreshold, $a, $prim_SiteA , $prim_SiteB , $stand_SiteA , $stand_SiteB)
{
	$FSL = 92.4 + 20 * log10($distance) + 20 * log10($Frequency);//BC3 
	if($Version == 1)
	{
		$RX = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB;//BD3  
		$FM103 = 0; //$RX - $Ref;
		$FM106 = $RX - $RXThreshold; //BG3
		//echo $FM106; 
		$out['FM103'] = $FM103;
		$out['FM106'] = $FM106; 
		$out['RX'] = $RX;
		//echo $FSL." ".$RX." ".$FM106;
	}
	if($Version == 2)
	{
		if($Antenna_Menu == 1)
		{
			$RX = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB - $prim_SiteA - $prim_SiteB;  
			$RX_HSB = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB - $stand_SiteA - $stand_SiteB;	
			
			$out['RX'] = $RX;
			$out['RX_HSB'] = $RX_HSB;
			//echo $RX." ".$RX_HSB." ".$FSL; 
			$FM_HSB = $RX_HSB - $RXThreshold;
			$FM_Main = $RX - $RXThreshold;
			$out['FM_HSB'] = $FM_HSB;
			$out['FM_Main'] = $FM_Main;
		}
		if($Antenna_Menu == 2)
		{
			$RX = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB;  
			$RX_HSB = $TransmitPow + $Antenna_A_Extra - $FSL - $a - $Losses + $Antenna_B_Extra;
			$out['RX'] = $RX;
			$out['RX_HSB'] = $RX_HSB;
			$FM_HSB = $RX_HSB - $RXThreshold;
			$FM_Main = $RX - $RXThreshold;
			$out['FM_HSB'] = $FM_HSB;
			$out['FM_Main'] = $FM_Main;
		}
	}
	if($Version == 3)
	{
		
		$RX = $TransmitPow + $Antenna_A_Extra - $FSL - $a - $Losses + $AntennaB;//BD3  
		$FM103 = 0; //$RX - $Ref;
		$FM106 = $RX - $RXThreshold; //BG3
		//echo $FM106; 
		$out['FM103'] = $FM103;
		$out['FM106'] = $FM106; 
		$out['RX'] = $RX;
		//echo $FSL." ".$RX." ".$FM106;
	}
	if($Version == 4)
	{
		if($Antenna_Menu == 1)
		{
			$FSL = 92.4 + 20 * log10($distance) + 20 * log10($Frequency);
			$RX = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB;
			$FM103 = 0; //$RX - $Ref;
			$FM106 = $RX - $RXThreshold; //BG3
			$out['FM103'] = $FM103;
			$out['FM106'] = $FM106; 
			$out['RX'] = $RX;
		}
		if($Antenna_Menu == 2)
		{
			$FSL = 92.4 + 20 * log10($distance) + 20 * log10($Frequency);
			$FSL_FD = 92.4 + 20 * log10($distance) + 20 * log10($Frequency_FD);
			
			$RX = $TransmitPow + $AntennaA - $FSL - $a - $Losses + $AntennaB;
			$RX_FD = $Transmit_FD + $Antenna_A_Extra - $FSL - $a - $Losses + $Antenna_B_Extra;			
			
			$FM103 = 0; //$RX - $Ref;
			$out['RX'] = $RX; 
			$out['RXThreshold'] = $RXThreshold;
			$FM106 = $RX - $RXThreshold; //BG3		

			$FM103_FD = 0; 
			$FM106_FD = $RX_FD - $RXThreshold;
			
			if($Frequency < $Frequency_FD) $out['FM106'] = $FM106;
			else $out['FM106'] = $FM106_FD; 
			$out['FM103'] = $FM103;
			$out['RX'] = $RX;
			$out['FM106_FD'] = $FM106_FD;
		}
	}
	$out['FSL'] = $FSL;
	return $out; 
}
function Geoclimatic($PointRefractGrad, $sa, $Antenna1, $Antenna2, $distance)
{
	$SaTemp = 0; 
	if($sa == 0) $SaTemp = 1;
	else $SaTemp = $sa; 
	
	$k = pow(10,(-3.9) - 0.003 * $PointRefractGrad) * pow($SaTemp, -0.42); 
	//echo $PointRefractGrad." ".$SaTemp; 
	
	$PathInclination = abs($Antenna1 - $Antenna2)/$distance;
	if($Antenna1 < $Antenna2)
	{
		$AntennaLH = $Antenna1;
	}
	else $AntennaLH = $Antenna2; 
	//echo $k." ".$PathInclination." ".$AntennaLH;
	$out['k'] = $k; 
	$out['PathInclination'] = $PathInclination; 
	$out['AntennaLH'] = $AntennaLH; 
	//echo " K:".$k." Path inclination: ".$PathInclination." Lower Antenna height: ".$AntennaLH;
	return $out; 
}
function SelectiveOutage($Version, $LatA, $LatB, $Geoclimatic, $distance, $Frequency, $FadeMargin )
{
	$dLat = ($LatA + $LatB)/2;//BR2
	//echo " ".$dLat." "; 
	if(abs($dLat) <= 45) $sign = 1;//BR3
	else $sign = -1; //BR3
	if($Version != 2)
	{
		$Fade1 = $FadeMargin['FM106'];	
		$Fade2 = $FadeMargin['FM103'];
	}
	else
	{
		$Fade1 = $FadeMargin['FM_Main'];
		$Fade2 = $FadeMargin['FM_HSB'];
	}
	//echo $Fade1; 
	$BER106pw = $Geoclimatic['k'] * pow($distance,3.2) * pow((1+$Geoclimatic['PathInclination']), -0.97) * pow(10, (0.032 * $Frequency - 0.00085 * $Geoclimatic['AntennaLH'] - ($Fade1/10)));//BS6
	$BER103pw = $Geoclimatic['k'] * pow($distance,3.2) * pow((1+$Geoclimatic['PathInclination']), -0.97) * pow(10, (0.032 * $Frequency - 0.00085 * $Geoclimatic['AntennaLH'] - ($Fade2/10)));//BS6
	
	$dG1 = 10.5 - 5.6 * log10(1.1 + $sign * pow((abs(cos(2 * $dLat * pi()/180 ))),0.7)) - 2.7 * log10($distance) + 1.7 * log10(1+abs($Geoclimatic['PathInclination']));
	if($dG1 > 10.8) {$dG1 = 10.8;}
	$Pns106 = (pow(10, ((-$dG1/10)))) * $BER106pw;//BS13
	$Pns103 = (pow(10, ((-$dG1/10)))) * $BER103pw;//BS13
	
	$PwA0 = $Geoclimatic['k'] * pow($distance, 3.2) * pow(1 + $Geoclimatic['PathInclination'], (-0.97)) * pow(10, (0.032 * $Frequency - 0.00085 * $Geoclimatic['AntennaLH'])); 
	//echo $dLat." ".$sign." ".$BER106pw." ".$Geoclimatic['k']; 
	//echo $BER106pw;
	//echo $Geoclimatic['k']." ".$distance." ".$Geoclimatic['PathInclination']." ".$Geoclimatic['AntennaLH']." ".$Frequency; 
	
	
	
	$out['dLat'] =  $dLat; 
	$out['Pns106'] = $Pns106;
	$out['Pns103'] = $Pns103;	
	$out['BER106pw'] = $BER106pw; 
	$out['BER103pw'] = $BER103pw; 
	$out['dG'] = $dG1; 
	$out['PwA0'] = $PwA0; 
	$out['sign'] = $sign; 
	//echo $dLat." ".$sign." ".$BER106pw." ".$Pns106." ".$PwA0;
	
	//echo $Pns106." ".$Pns103;
	return $out; 
}	
function Selective($SelectiveOutage, $distance, $conn, $Bandwidth, $Modulation, $Product )
{
	$MultipathActivity = 1 - exp(-0.2* pow((($SelectiveOutage['PwA0'])/100), 0.75));//BR21
	$tm = 0.7 * pow(($distance/50), 1.3);//BR22 
	$sql = "SELECT Width, minDepth, nonMinDepth FROM `signature_cfip` WHERE `BandWidth` =  $Bandwidth and `Modulation` LIKE '$Modulation' AND `Productid` = $Product";
	$result = $conn->query($sql);
	$params = mysqli_fetch_array($result);
	$width106 = $params[0]; //BS24
	$minDepth106 = $params[1];//BS25
	$nonDepth106 = $params[2];//BS26
	$Ps106 = 2.15 * $MultipathActivity * ($width106 * pow(10, (-$minDepth106/20)) * (pow($tm, 2)/6.3) + $width106 * pow(10, (-$nonDepth106/20)) * (pow($tm, 2)/6.3));
	$width103 = 0;
	$minDepth103 = 0;
	$nonDepth103 = 0;
	$Ps103 = 0; 
	$PsNext103 = pow(10, ((-$SelectiveOutage['dG'])/10)) * ($Ps103 * 100);
	$PsNext106 = pow(10, ((-$SelectiveOutage['dG'])/10)) * ($Ps106 * 100);
	$PsNextNext = $PsNext106 + $SelectiveOutage['Pns106'];//Te nesakrīt rezultāts.
	$negativePsNextNext = 100 - $PsNextNext;
	$out['PsNext106'] = $PsNext106;
	$out['PsNext103'] = $PsNext103;
	$out['PsNextNext'] = $PsNextNext;
	$out['negativePsNextNext'] = $negativePsNextNext;
	$out['MultipathActivity'] = $MultipathActivity;
	$out['tm'] = $tm;
	$out['Ps106'] = $Ps106;
	$out['Ps103'] = $Ps103;
	//echo $Ps106; 
	//echo "  Selective:".$PsNext106; 
	return $out; 
}
function TotalMultipath($SelectiveOutage, $Selective)
{
	$Pt106 = $SelectiveOutage['Pns106'] + $Selective['PsNext106'];
	//echo $Pt106;
	//echo "<br> Total Multipath:  ".$SelectiveOutage['Pns106']." ".$Selective['PsNext106']." <br>";
	
	
	$Pt103 = $SelectiveOutage['Pns103'] + $Selective['PsNext103'];
	$Availability106 = 100 - $Pt106; 
	$Availability103 = 100 - $Pt103; 
	$TotalPath106 = 100 - 2 * $Pt106; 
	$TotalPath103 = 100 - 2 * $Pt103; 
	$out['Pt106'] = $Pt106; 
	$out['Pt103'] = $Pt103; 
	$out['Availability106'] = $Availability106; 
	$out['Availability103'] = $Availability103; 
	$out['TotalPath106'] = $TotalPath106; 
	$out['TotalPath103'] = $TotalPath103; 
	
	//echo $Pt106;
	
	return $out;
}
function getThreshold($conn, $Frequency, $Bandwidth, $Standart, $FEC, $Modulation, $Product)
{
	$sql = "SELECT `RXTreshold` FROM `rx_treshold` WHERE `ProductID` LIKE $Product and `Modulation` LIKE '$Modulation' and `FEC` = $FEC  and `FrequencyBand` = $Frequency and `BandWidth` = $Bandwidth and `Standart` LIKE '$Standart'";
	$result = $conn->query($sql);
	$params = mysqli_fetch_array($result);
	if(mysqli_num_rows($result) > 0) {
		return $params[0]; 
	}
	else {
		return "Value not found"; 
	} 
}
function RainRate($conn,$Version, $Frequency, $TempRzone, $distance, $FadeMargin, $SelectiveOutage )
{
	$sql = "SELECT `kh`,`kv`,`ah`,`av` FROM `regression_coeficients` ORDER BY ABS( Frequency - '$Frequency') limit 1 ";
	$result = $conn->query($sql);
	$params = mysqli_fetch_array($result);
	$kh = $params[0];
	$kv = $params[1];
	$ah = $params[2];
	$av = $params[3]; 
	$sql = "SELECT `Value` FROM `rainzone` WHERE `Zone` like '$TempRzone';";
	$result = $conn->query($sql);
	$RainZone = mysqli_fetch_array($result);
	$RainRate = $RainZone[0]; 
	
	$Yh = $kh * pow($RainRate, $ah);
	$Yv = $kv * pow($RainRate, $av);
	if($Version != 2)
	{
		$Fade1 = $FadeMargin['FM103'];	
		$Fade2 = $FadeMargin['FM106'];
	}
	else
	{
		$Fade1 = $FadeMargin['FM_HSB'];
		$Fade2 = $FadeMargin['FM_Main'];
	}
	
	if($RainRate <= 100) $d0tmp = $RainRate;
	else $d0tmp = 100; 
	
	$d0 = 35 * exp(-0.015 * $d0tmp);
	$r = 1/(1+($distance/$d0));
	$deff = $distance * $r; 
	$Ah = $Yh * $deff;
	$Av = $Yv * $deff;
	
	//<=30 N,s
	$p1 = array
	(
		'p1h' => pow(10, ((sqrt(1.73323 - LOG10($Fade1/(0.12 * $Ah)))-1.31652)/SQRT(0.043))),
		'p2h' => pow(10, ((sqrt(1.73323 - LOG10($Fade2/(0.12 * $Ah)))-1.31652)/SQRT(0.043))),
		'p1v' => pow(10, ((sqrt(1.73323 - LOG10($Fade1/(0.12 * $Av)))-1.31652)/SQRT(0.043))),
		'p2v' => pow(10, ((sqrt(1.73323 - LOG10($Fade2/(0.12 * $Av)))-1.31652)/SQRT(0.043))),
	);
	
	//<30 N,s
	$p2 = array
	(
		'p1h' => pow(10, ((sqrt(1.31479 - log10($Fade1/(0.07 * $Ah))) - 1.14664)/sqrt(0.139))),
		'p2h' => pow(10, ((sqrt(1.31479 - log10($Fade2/(0.07 * $Ah))) - 1.14664)/sqrt(0.139))),
		'p1v' => pow(10, ((sqrt(1.31479 - log10($Fade1/(0.07 * $Av))) - 1.14664)/sqrt(0.139))),
		'p2v' => pow(10, ((sqrt(1.31479 - log10($Fade2/(0.07 * $Av))) - 1.14664)/sqrt(0.139))),
	);
	if(abs($SelectiveOutage['dLat']) >= 30 )
	{
		$p = array(
			'p1h' => $p1['p1h'],
			'p2h' => $p1['p2h'],
			'p1v' => $p1['p1v'],
			'p2v' => $p1['p2v'],
		);
		//echo $p['p2v'];
		if(is_nan($p['p1h']) || is_infinite($p['p1h'])) $p['p1h'] = 0;
		if(is_nan($p['p2h']) || is_infinite($p['p2h'])) $p['p2h'] = 0;
		if(is_nan($p['p1v']) || is_infinite($p['p1v'])) $p['p1v'] = 0;
		if(is_nan($p['p2v']) || is_infinite($p['p2v'])) $p['p2v'] = 0;
	}
	else
	{
		$p = array(
			'p1h' => $p2['p1h'],
			'p2h' => $p2['p2h'],
			'p1v' => $p2['p1v'],
			'p2v' => $p2['p2v'],	
		);	
		if(is_nan($p['p1h']) || is_infinite($p['p1h'])) $p['p1h'] = 0;
		if(is_nan($p['p2h']) || is_infinite($p['p2h'])) $p['p2h'] = 0;
		if(is_nan($p['p1v']) || is_infinite($p['p1v'])) $p['p1v'] = 0;
		if(is_nan($p['p2v']) || is_infinite($p['p2v'])) $p['p2v'] = 0;
	}
	$out['p1h'] = $p['p1h'];
	$out['p2h'] = $p['p2h'];
	$out['p1v'] = $p['p1v'];
	$out['p2v'] = $p['p2v'];	
	$out['RainRate'] = $RainRate;
	//echo $RainRate; 
	//echo $p['p1h']." ".$p['p2h']." ".$p['p1v']." ".$p['p2v'];
	return $out;
}
function PlotRain($RainRate)
{
	$PlotRain100 = array
	(
		'Plot1H' => 100 - $RainRate['p1h'],
		'Plot2H' => 100 - $RainRate['p2h'],
		'Plot1V' => 100 - $RainRate['p1v'],
		'Plot2V' => 100 - $RainRate['p2v'],	
	);
	$PlotRain200 =  array
	(
		'Plot1H' => 100 - 2 * $RainRate['p1h'],
		'Plot2H' => 100 - 2 * $RainRate['p2h'],
		'Plot1V' => 100 - 2 * $RainRate['p1v'],
		'Plot2V' => 100 - 2 * $RainRate['p2v'],		
	); 
	//echo "<br>";
	//print_r($PlotRain100);
	$out['PlotRain100'] = $PlotRain100;
	$out['PlotRain200'] = $PlotRain200;
	return $out; 
}
function MultipathRain($RainRate, $TotalMultipath)
{
	$MultipathRain1 = array
	(
		'mp1h' => $RainRate['p1h'] + $TotalMultipath['Pt103'],
		'mp2h' => $RainRate['p2h'] + $TotalMultipath['Pt106'],
		'mp1v' => $RainRate['p1v'] + $TotalMultipath['Pt103'],
		'mp2v' => $RainRate['p2v'] + $TotalMultipath['Pt106'],	
	);
	//print_r($MultipathRain1);
	$MultipathRain2 = array
	(
		'm2p1h' => 100 - $MultipathRain1['mp1h'],
		'm2p2h' => 100 - $MultipathRain1['mp2h'],
		'm2p1v' => 100 - $MultipathRain1['mp1v'],
		'm2p2v' => 100 - $MultipathRain1['mp2v'],	
	);
	$MultipathRain3 = array
	(
		'm3p1h' => 100 - (2 * $TotalMultipath['Pt103'] + $RainRate['p1h']),
		'm3p2h' => 100 - (2 * $TotalMultipath['Pt106'] + $RainRate['p2h']),
		'm3p1v' => 100 - (2 * $TotalMultipath['Pt103'] + $RainRate['p1v']),
		'm3p2v' => 100 - (2 * $TotalMultipath['Pt106'] + $RainRate['p2v']),	
	);	
	//echo $MultipathRain3['m3p2h']." ".$TotalMultipath['Pt106']."  ".$TotalMultipath['Pt103']."         ".$RainRate['p2h']; 
	//echo $RainRate['p2v']." ".$TotalMultipath['Pt106'];
	//echo $MultipathRain1['mp1h']."  ".$MultipathRain1['mp2h']." ".$MultipathRain1['mp1v']." ".$MultipathRain1['mp2v'];
	//echo $MultipathRain2['m2p1h']." ".$MultipathRain2['m2p2h']." ".$MultipathRain2['m2p1v']." ".$MultipathRain2['m2p2v'];
	//echo $MultipathRain3['m3p1h']." ".$MultipathRain3['m3p2h']." ".$MultipathRain3['m3p1v']." ".$MultipathRain3['m3p2v'];
	//echo $MultipathRain3['m3p2v'];
	return $MultipathRain3;	
}
function RSSI($FadeMargin,$Version)
{
	if($Version != 2)
	{
		if($FadeMargin['RX'] > -90 && $FadeMargin['RX'] < -20)
		{
			$RSSI = 0.02 * ($FadeMargin['RX'] + 90);
		}
		else $RSSI = "N/A";
		$out['RSSI'] = $RSSI; 
	}
	else 
	{
		if($FadeMargin['RX'] > -90 && $FadeMargin['RX'] < -20)
		{
			$RSSI = 0.02 * ($FadeMargin['RX'] + 90);
		}
		else $RSSI = "N/A";
		$out['RSSI'] = $RSSI;
		if($FadeMargin['RX_HSB'] > -90 && $FadeMargin['RX_HSB'] < -20)
		{
			$RSSI_HSB = 0.02 * ($FadeMargin['RX_HSB'] + 90);
		}
		else $RSSI_HSB = "N/A";				
		$out['RSSI_HSB'] = $RSSI_HSB;
	}
	return $out; 
}
function GetAnthenaParams($conn,$Manufacturer, $Frequency, $Diameter)
{
	$sql = "SELECT `Gain` FROM `anthenagains` WHERE `Frequence` = $Frequency and `Diameter` LIKE $Diameter and `Manuf_ID` = $Manufacturer limit 1";
	$result = $conn->query($sql);
	$data = mysqli_fetch_array($result);
	return $data[0]; 
}
function ErroredTime($MRainAvailVert, $MRainAvailHor)
{
	$V_Overallmins = 525600 - (5256 * $MRainAvailVert);
	$H_Overallmins = 525600 - (5256 * $MRainAvailHor);
	
	$V_Hours = floor($V_Overallmins/60); 
	$H_Hours = floor($H_Overallmins/60);

	$V_Mins = sprintf('%02s', round($V_Overallmins - $V_Hours * 60, 0));
	
	$H_Mins = sprintf('%02s', round($H_Overallmins - $H_Hours * 60, 0));
	//echo " ".$V_Mins." ";
	//echo " ".$H_Mins." ";
	$out['V_Hours'] = $V_Hours;
	$out['H_Hours'] = $H_Hours;
	$out['V_Mins'] = $V_Mins;
	$out['H_Mins'] = $H_Mins;
	
	//echo $V_Overallmins." ".$H_Overallmins." ".$V_Hours." ".$H_Hours."  ".$V_Mins." ".$H_Mins; 
	return $out; 
}
function MaxTransmitterPower($conn, $Product, $Frequency, $Modulation)
{
		$out['MinTXforSP'] = NULL;
		$out['MaxTXforSP'] = NULL; 
		$out['MinTXforHP'] = NULL;
		$out['MaxTXforHP'] = NULL;
		$sql = "SELECT `TX_MinPower`,`TX_MaxPower`,`HTX_MinPower`, `HTX_MaxPower` FROM `tx_power` WHERE `Product_ID` = $Product AND `Modulation` LIKE '$Modulation' AND `FrequencyBand` = $Frequency";	
		$result = $conn->query($sql);
		$data = mysqli_fetch_array($result);	
		if($Product == 1 || $Product == 2)
		{
			$out['MaxTXforSP'] = $data[1]; 
		}
		if($Product == 3 || $Product == 4 || $Product == 6 || $Product == 8)
		{
			$out['MinTXforSP'] = $data[0];
			$out['MaxTXforSP'] = $data[1]; 
			if($data[2] != NULL)
			{
				$out['MinTXforHP'] = $data[2];
			}
			if($data[3] != NULL)
			{
				$out['MaxTXforHP'] = $data[3];
			}
		}
		if($Product == 5 || $Product == 7 || $Product == 9)
		{
			$out['MinTXforHP'] = $data[2];
			$out['MaxTXforHP'] = $data[3];
		}
	return $out; 
}
function GetMaxCap($conn, $Product, $Modulation, $Bandwidth, $Standart, $FEC)
{
	$sql = "SELECT `Capacity` FROM `rx_treshold` WHERE `Modulation` LIKE '$Modulation' AND `BandWidth` = $Bandwidth AND `Standart` LIKE '$Standart' AND `ProductID` = $Product AND `FEC` = $FEC LIMIT 1";	
	$result = $conn->query($sql);
	$data = mysqli_fetch_array($result);
	return $data[0]; 
}
function Attenuation($Frequency,$Temperature,$LatA,$LatB,$Antenna1,$Antenna2,$distance)
{
	$latitude=abs($LatA+$LatB)/2;
	//echo $latitude;
	$season=1;
	$h=($Antenna1+$Antenna2)/2*0.001;
	if(($latitude<=22))
	{
		$p=1012.0306-109.0338*$h+3.6316*$h*$h;
		$Ro=19.6542*exp(-0.2313*$h-0.1122*pow($h,2)+0.01351*pow($h,3)-0.0005923*pow($h,4));
	}
	elseif(($season == 1) and ($latitude>22) and ($latitude<=45))
	{
		$p=1012.8186-111.5569*$h+3.8646*$h*$h;
		$Ro=14.3542*exp(-0.4174*$h-0.0229*pow($h,2)+0.001007*pow($h,3));
	}
	elseif(($season == 2) and ($latitude>22) and ($latitude<=45))
	{
		$p=1018.8627-1224.2954*$h+4.8307*$h*$h;
		$Ro=3.4742*exp(-0.3614*$h-0.005402*pow($h,2)+0.0004489*pow($h,3));
	}
	elseif(($season == 1) and ($latitude>45) and ($latitude<=90))
	{
		$p=1008.0278-113.2494*$h+3.9408*$h*$h;
		$Ro=8.988*exp(-0.3614*$h-0.005402*pow($h,2)-0.001955*pow($h,3));
	}
	elseif(($season == 2) and ($latitude>45) and ($latitude<=90))
	{
		$p=1010.8828-112.2411*$h+4.554*$h*$h;
		$Ro=1.2319*exp(0.07481*$h-0.0981*pow($h,2)+0.00281*pow($h,3));
	}
	$rp=$p/1013;
	$rt=288/(273+$Temperature);
 
	$w1=0.9544*$rp*pow($rt,0.69)+0.0061*$Ro;
	$w2=0.95*$rp*pow($rt,0.64)+0.0067*$Ro;
	$w3=0.9561*$rp*pow($rt,0.67)+0.0059*$Ro;
	$w4=0.9543*$rp*pow($rt,0.68)+0.0061*$Ro;
	$w5=0.955*$rp*pow($rt,0.68)+0.006*$Ro;
 
	$g22=1+pow($Frequency-22.235,2)/pow($Frequency+22.235,2);
	$g557=1+pow($Frequency-557,2)/pow($Frequency+557,2);
	$g752=1+pow($Frequency-752,2)/pow($Frequency+752,2);
 
	$n1=6.7665*pow($rp,-0.505)*pow($rt,0.5106)*exp(1.5663*(1-$rt))-1;
	$n2=27.8843*pow($rp,-0.4908)*pow($rt,0.8491)*exp(0.5496*(1-$rt))-1;
 
	$a=log($n2/$n1)/log(3.5);
	$b=pow(4,$a)/$n1;
 
	$y0_54=2.128*pow($rp,1.4954)*pow($rt,-1.6032)*exp(-2.528*(1-$rt));
	$y0dB_Km=(((7.34*pow($rp,2)*pow($rt,3))/(pow($Frequency,2)+0.36*pow($rp,2)*pow($rt,2)))+((0.3429*$b*$y0_54)/(pow((54-$Frequency),$a)+$b)))*pow($Frequency,2)*0.001;
	$yw=(3.13*pow(10,-2)*$rp*pow($rt,2)+1.76*pow(10,-3)*$Ro*pow($rt,8.5)+pow($rt,2.5)*(((3.84*$w1*$g22*exp(2.23*(1-$rt)))/(pow(($Frequency-22.235),2)+9.42*pow($w1,2)))
	+((10.48*$w2*exp(0.7*(1-$rt)))/(pow(($Frequency-183.31),2)+9.48*pow($w2,2)))
	+((0.078*$w3*exp(6.4385*(1-$rt)))/(pow(($Frequency-321.226),2)+6.29*pow($w3,2)))
	+((3.76*$w4*exp(1.6*(1-$rt)))/(pow(($Frequency-325.153),2)+9.22*pow($w4,2)))
	+((26.36*$w5*exp(1.09*(1-$rt)))/pow(($Frequency-380),2))
	+((17.87*$w5*exp(1.46*(1-$rt)))/pow(($Frequency-448),2))
	+((883.7*$w5*$g557*exp(0.17*(1-$rt)))/pow(($Frequency-557),2))
	+((302.6*$w5*$g752*exp(0.41*(1-$rt)))/pow(($Frequency-752),2))))*pow($Frequency,2)*$Ro*pow(10,-4);
	$ydB=$y0dB_Km+$yw;
	$result=$ydB*$distance;
	return $result;
} 
function sign( $number ) 
{ 
    return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 ); 
} 

function SelectFromGtopo($conn, $Lat, $Lon)
{
	$result = mysqli_fetch_array($conn->query("SELECT `Gtopo` FROM `gtopo` WHERE `Lat` = $Lat and `Lon` = $Lon LIMIT 1"));
	return $result[0]; 
}

function calc_points_for_SA($conn, $latitude, $longitude)
{ 
	if($latitude >= 0)
	{
		$rounded_latitude = ceil($latitude * 2) / 2; 
		$lower_latitude = $rounded_latitude - 0.5;
		$adrr_rounded_latitude = $rounded_latitude;
		$adrr_lower_latitude = $lower_latitude; 
	}
	else if($latitude < 0)
	{
		$rounded_latitude = floor($latitude * 2) / 2;
		$lower_latitude = $rounded_latitude + 0.5;
		//rounded un lower latitude apgrieztas vietām,
		$adrr_rounded_latitude = $lower_latitude;
		$adrr_lower_latitude = $rounded_latitude; 
	}
		
	if($longitude < 0)
	{
		$rounded_longitude = floor($longitude * 2) / 2;
	}
	else $rounded_longitude = ceil($longitude * 2) / 2;  
	
	if($rounded_longitude == -180)
	{
		$lower_longitude = ($rounded_longitude * -1) - 0.5;
	}
	else if($rounded_longitude >= 0 ) $lower_longitude = $rounded_longitude - 0.5; 
	else if($rounded_longitude < 0 && $rounded_longitude != -180) $lower_longitude = $rounded_longitude + 0.5; 

	$p3 = SelectFromGtopo($conn, $adrr_rounded_latitude, $rounded_longitude);
	$p2 = SelectFromGtopo($conn, $adrr_lower_latitude, $rounded_longitude);
	$p4 = SelectFromGtopo($conn, $adrr_lower_latitude, $lower_longitude);
	$p1 = SelectFromGtopo($conn, $adrr_rounded_latitude, $lower_longitude);
	
	$Point= $p4 * (($rounded_latitude - $latitude) * -2) * (($rounded_longitude - $longitude) * -2) +  
			$p1 * (($latitude - $lower_latitude) * -2) * (($rounded_longitude - $longitude) * -2) + 
			$p2 * (($rounded_latitude - $latitude) * -2) * (($longitude - $lower_longitude) * -2) + 
			$p3 * (($latitude - $lower_latitude) * -2) * (($longitude - $lower_longitude) * -2);	
	$out['Point'] = $Point;
	$out['rounded_latitude'] = $rounded_latitude;
	$out['lower_latitude'] = $lower_latitude;
	$out['rounded_longitude'] = $rounded_longitude;
	$out['lower_longitude'] = $lower_longitude; 
	$out['adrr_lower_latitude'] = $adrr_lower_latitude;
	$out['adrr_rounded_latitude'] = $adrr_rounded_latitude;
	
	$out['p1'] = $p1;
	$out['p2'] = $p2;
	$out['p3'] = $p3;
	$out['p4'] = $p4;	
	return $out; 
}

function Sa($conn, $LatA, $LonA, $LatB, $LonB)
{
	//Atrod viduspunktus platumam un augstumam. 
	$midLat = ($LatA + $LatB)/2;
	$midLon = ($LonA + $LonB)/2;
	
	
	//No platuma viduspunkta un augstuma atrod četrus punktus, kur augstums un platums ir lielāks un mazāks par vidējo. 
	$midLat = array($midLat + 0.5, $midLat - 0.5);
	$midLon = array($midLon + 0.5, $midLon - 0.5);

	
	//Atrodot iespējamo kļūdu izmantojot calc_points_for_SA funkcijas pirmajiem četriem 110km x 110km kvadrātiem. 
	$V1 = calc_points_for_SA($conn, $midLat[0], $midLon[0]);
	$V2 = calc_points_for_SA($conn, $midLat[0], $midLon[1]);
	$V3 = calc_points_for_SA($conn, $midLat[1], $midLon[0]);
	$V4 = calc_points_for_SA($conn, $midLat[1], $midLon[1]);	 
	
	//echo $V1['Point']." ".$V2['Point']." ".$V3['Point']." ".$V4['Point'];
	
	//110 km x 110km kvadrātā izvēlas četrus punktus no GTopo tabulas blakus augstuma un platuma viduspunktam. 
	$p1 = array(
		"v1" => SelectFromGtopo($conn, $V2['adrr_lower_latitude'], $V2['lower_longitude']),
		"v2" => SelectFromGtopo($conn, $V3['adrr_rounded_latitude'], $V2['rounded_longitude']),
		"v3" => SelectFromGtopo($conn, $V1['adrr_lower_latitude'], $V2['rounded_longitude']),
		"v4" => SelectFromGtopo($conn, $V3['adrr_rounded_latitude'], $V2['lower_longitude']),
	);

	//echo $p1['v1']." ".$p1['v2']." ".$p1['v3']." ".$p1['v4'];
	
	$V5 = $p1['v4'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * (($V2['rounded_longitude'] - $midLon[1]) * -2) +
			$p1['v1'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * (($V2['rounded_longitude'] - $midLon[1]) * -2) + 
			$p1['v2'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * (($midLon[1] - $V2['lower_longitude']) * -2) + 
			$p1['v3'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * (($midLon[1] - $V2['lower_longitude'])* -2); 
	//Izvēlas citus četrus punktus no Gtopo tabulas.
	$p2 = array(
		"v1" => SelectFromGtopo($conn, $V1['adrr_rounded_latitude'], $V2['rounded_longitude']),
		"v2" => SelectFromGtopo($conn, $V1['adrr_lower_latitude'], $V1['lower_longitude']),
		"v3" => SelectFromGtopo($conn, $V1['adrr_rounded_latitude'], $V1['lower_longitude']),
		"v4" => SelectFromGtopo($conn, $V2['adrr_lower_latitude'], $V2['rounded_longitude']),
	);

	//echo $p2['v1']." ".$p2['v2']." ".$p2['v3']." ".$p2['v4'];
	
	$V6 = $p2['v4'] * (($V2['rounded_latitude'] - $midLat[0]) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2)+
			$p2['v1'] * (($midLat[0] - $V2['lower_latitude']) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2) + 
			$p2['v2'] * (($V2['rounded_latitude'] - $midLat[0]) * -2) * ((($midLon[0] - 0.5) - $V2['rounded_longitude']) * -2) +
			$p2['v3'] * (($midLat[0] - $V2['lower_latitude']) * -2) * ((($midLon[0] - 0.5) - $V2['rounded_longitude']) * -2);

	//Izvēlas četrus punktus no Gtopo tabulas.	
	$p3 = array(
		"v1" => SelectFromGtopo($conn, $V2['adrr_lower_latitude'], $V1['lower_longitude']),
		"v2" => SelectFromGtopo($conn, $V3['adrr_rounded_latitude'], $V3['rounded_longitude']), 
		"v3" => SelectFromGtopo($conn, $V1['adrr_lower_latitude'], $V1['rounded_longitude']),
		"v4" => SelectFromGtopo($conn, $V3['adrr_rounded_latitude'], $V1['lower_longitude']),
	);
	$V7 = $p3['v4'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * (($V2['rounded_longitude'] - $midLon[1]) * -2) +
			$p3['v1'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * (($V2['rounded_longitude'] - $midLon[1]) * -2) +
			$p3['v2'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * (($midLon[1] - $V2['lower_longitude']) * -2) +
			$p3['v3'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * (($midLon[1] - $V2['lower_longitude']) * -2);
	//Izvēlas četrus punktus no Gtopo tabulas.
	$p4 = array(
		"v1" => SelectFromGtopo($conn, $V4['adrr_rounded_latitude'], $V4['rounded_longitude']),
		"v2" => SelectFromGtopo($conn, $V4['adrr_lower_latitude'], $V3['lower_longitude']), 
		"v3" => SelectFromGtopo($conn, $V4['adrr_rounded_latitude'], $V3['lower_longitude']),
		"v4" => SelectFromGtopo($conn, $V4['adrr_lower_latitude'], $V4['rounded_longitude']),
	); 
	$V8 = $p4['v4'] * (($V2['rounded_latitude'] - $midLat[0]) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2) +
			$p4['v1'] * (($midLat[0] - $V2['lower_latitude']) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2) +
			$p4['v2'] * (($V2['rounded_latitude'] - $midLat[0]) * -2) * ((($midLon[0] - 0.5) - $V2['rounded_longitude'])  * -2) +
			$p4['v3'] * (($midLat[0] - $V2['lower_latitude']) * -2) * ((($midLon[0] - 0.5) - $V2['rounded_longitude'])  * -2);
	//Izvēlas četrus Gtopo koeficientus no pirmajiem četriem kvadrātiem, no katra paņemot pa vienai vērtībai. 
	$p5 = array(
		"v1" => $V2['p2'],
		"v2" => $V3['p1'],
		"v3" => $V1['p4'],
		"v4" => $V4['p3'],
 	); 
	$V9 = 	$p5['v4'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2) + 
			$p5['v1'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * (($V3['lower_longitude'] - ($midLon[0] - 0.5)) * -2) +
			$p5['v2'] * (($V2['lower_latitude'] - ($midLat[0] - 0.5)) * -2) * ((($midLon[0]- 0.5) - $V2['rounded_longitude']) * -2) +
			$p5['v3'] * ((($midLat[0] - 0.5) - $V3['rounded_latitude']) * -2) * ((($midLon[0]- 0.5) - $V2['rounded_longitude']) * -2); 
	//Tiek izvilkts vidējais aritmētiskais no visiem deviņiem punktiem. 
	
	//echo " ".$V5." ".$V6."  ".$V7." ".$V8." ".$V9; 
	
	$Sum_Average = ($V1['Point'] + $V2['Point'] + $V3['Point'] + $V4['Point'] + $V5 + $V6 + $V7 + $V8 + $V9) / 9; 

	//Tiek aprēķināts Sa, jeb reljefa nelīdzenums (terrain roughness). 
	$Sa = sqrt((1/9) * (pow($V1['Point'] - $Sum_Average, 2) + pow($V2['Point'] - $Sum_Average, 2) + pow($V3['Point'] - $Sum_Average, 2) + 
			pow($V4['Point'] - $Sum_Average, 2) + pow($V5 - $Sum_Average, 2) + pow($V6 - $Sum_Average, 2) + pow($V7 - $Sum_Average, 2) + 
			pow($V8 - $Sum_Average, 2) + pow($V9 - $Sum_Average, 2))) * 1000;  
	//echo $Sa; 
	
	return $Sa;
}

function SD($FrequencyFD, $AntennasAmount, $Version, $MainFreq, $DivFreq, $Selective, $FadeMargin, $SDsepA, $SDsepB, $AntennaA, $AntennaASD, $AntennaB, $AntennaBSD, $Frequency, $distance, $SelectiveOutage)
{
	if($Version == 3)
	{
		$V1 = abs($AntennaASD - $AntennaA);
		$V2 = abs($AntennaBSD - $AntennaB);
	
		$I1 = (1 - exp(-0.04 * pow($SDsepA, 0.87) * pow($Frequency, -0.12) * pow($distance, 0.48) * pow($SelectiveOutage['PwA0'], -1.04))) * pow(10, (($FadeMargin['FM106'] - $V1)/10));
		$I2 = (1 - exp(-0.04 * pow($SDsepB, 0.87) * pow($Frequency, -0.12) * pow($distance, 0.48) * pow($SelectiveOutage['PwA0'], -1.04))) * pow(10, (($FadeMargin['FM106'] - $V2)/10));
	
		$Kns1 = 1 - ($I1 * ($SelectiveOutage['BER106pw'] / 100)) / $Selective['MultipathActivity'];
		$Kns2 = 1 - ($I2 * ($SelectiveOutage['BER106pw'] / 100)) / $Selective['MultipathActivity'];

		if($Kns1 > 0.26) $rw1 = 1 - 0.6921 * pow((1-$Kns1), 1.034);
		else $rw1 = 1 - 0.9746 * pow((1-$Kns1), 2.17);
		if($Kns2 > 0.26) $rw2 = 1 - 0.6921 * pow((1-$Kns2), 1.034);
		else $rw2 = 1 - 0.9746 * pow((1-$Kns2), 2.17);	//BY14
	
		if($rw1 <= 0.5) $Ks1 = 0.8238;
		else
		{	
			if($rw1 > 0.9628) $Ks1 = 1 - 0.3957 * pow((1-$rw1), 0.5136);
			else $Ks1 = 1 - 0.195 * pow((1 - $rw1), 0.109-0.13*log10(1-$rw1));
		}
	
		if($rw2 <= 0.5) $Ks2 = 0.8238;
		else
		{	
			if($rw2 > 0.9628) $Ks2 = 1 - 0.3957 * pow((1-$rw2), 0.5136);
			else $Ks2 = 1 - 0.195 * pow((1 - $rw2), 0.109-0.13*log10(1-$rw2));
		}	
	}
	if($Version == 4)
	{
		$dF_tmp = abs($MainFreq - $DivFreq);
		if($dF_tmp > 0.5)$dF = 0.5;
		else $dF = $dF_tmp;
		if($AntennasAmount == 1)
		{
			if($Frequency > $FrequencyFD ) $f = $FrequencyFD;
			else $f = $Frequency;		
		}
		else $f = $Frequency; 
			
		$I1 = (80 / ($f * $distance)) * ($dF / $f) * pow(10, ($FadeMargin['FM106']/10));
		$I2 = (80 / ($f * $distance)) * ($dF / $f) * pow(10, ($FadeMargin['FM106']/10));		
		
		if($I1 < 1 ) $I1 = 1;
		if($I2 < 1 ) $I2 = 1; 
		
		$Kns1 = 1 - ($I1 * ($SelectiveOutage['BER106pw']) / $Selective['MultipathActivity']);
		$Kns2 = 1 - ($I2 * ($SelectiveOutage['BER106pw']) / $Selective['MultipathActivity']);
		
		if($Kns1 > 0.26) $rw1 = 1 - 0.6921 * pow((1 - $Kns1), 1.034);
		else $rw1 = 1 - 0.9746 * pow((1 - $Kns1), 2.17);
		
		if($Kns2 > 0.26) $rw2 = 1 - 0.6921 * pow((1 - $Kns2), 1.034);
		else $rw2 = 1 - 0.9746 * pow((1 - $Kns2), 2.17);
		
		if($rw1 <= 0.5) $Ks1 = 0.8238;
		else
		{
			if($rw1 > 0.9623) $Ks1 = 1 - 0.3957 * pow((1 - $rw1), 0.5136);
			else $Ks1 = 1 - 0.195 * pow((1 - $rw1), (0.109 - 0.13 * log10(1 - $rw1)));
		}
		if($rw2 <= 0.5) $Ks2 = 0.8238;
		else
		{
			if($rw2 > 0.9623) $Ks2 = 1 - 0.3957 * pow((1 - $rw2), 0.5136);
			else $Ks2 = 1 - 0.195 * pow((1 - $rw2), (0.109 - 0.13 * log10(1 - $rw2)));
		}		
	}
	$Pdns1 = ($SelectiveOutage['BER106pw'] / 100 )/ $I1;
	$Pdns2 = ($SelectiveOutage['BER106pw'] / 100 )/ $I2;
	
	$Pds1 = pow($Selective['Ps106'], 2) / ($Selective['MultipathActivity'] * (1 - $Ks1));	
	$Pds2 = pow($Selective['Ps106'], 2) / ($Selective['MultipathActivity'] * (1 - $Ks2));
	$Pd1 = pow((pow($Pds1, 0.75) + pow($Pdns1, 0.75)), (4/3));
	$Pd2 = pow((pow($Pds2, 0.75) + pow($Pdns2, 0.75)), (4/3));
	$Pt1 = ($Pd1 + $Pd2) * 100;
	$Pt2 = pow(10, (-$SelectiveOutage['dG']/10)) * $Pt1; 
	$Pt3 = 100 - $Pt2; 
	$out['Pt1'] = $Pt1;
	$out['Pt2'] = $Pt2;
	$out['Pt3'] = $Pt3;	
	return $out; 
}

function ChooseProduct($ProductTMP, $Odu)
{
	$Product = "";
	if($ProductTMP == 1)
	{
		$Product = 1; 
	}
	if($ProductTMP == 2)
	{
		$Product = 2; 
	}
	if($ProductTMP == 3)
	{
		$Product = 3; 
	}
	if($ProductTMP == 4)
	{
		if($Odu == 1 || $Odu == 0) $Product = 4;
		else if($Odu == 2) $Product = 5;
	}
	if($ProductTMP == 5)
	{
		if($Odu == 1 || $Odu == 0) $Product = 6;
		else if($Odu == 2) $Product = 7; 
	}
	if($ProductTMP == 6)
	{
		if($Odu == 1 || $Odu == 0) $Product = 8;
		else if($Odu == 2) $Product = 9;
	} 
	return $Product; 
}
function MultipathRain11($SD, $RainRate)
{
	$MultiPathRain = array
	(
		'mp1h' => 0,
		'mp2h' => $SD['Pt3'] - $RainRate['p2h'],
		'mp1v' => 0,
		'mp2v' => $SD['Pt3'] - $RainRate['p2v'],	
	);
	//echo $SD['Pt3']." ".$RainRate['p2h'];
	return $MultiPathRain; 
}

function Calculate_MainBlock($conn, $var)
{
	$distance = $var['distance'];
	$Product = $var['Product']; 
	$PointRefractGrad = getPointRefractGrad($var['LatA'], $var['LonA'], $var['LatB'], $var['LonB']); 
	if($var['Version'] == 2 )
	{
		if($var['Antenna_Menu'] == 2) 
		{
			$var['Extra_diameter_A'] = $var['Diameter_A'];
			$var['Extra_diameter_A'] = $var['Diameter_B'];
		}
	}
	$a = Attenuation($var['Frequency'], $var['Temperature'] , $var['LatA'], $var['LatB'], $var['Antenna_Height_A'],$var['Antenna_Height_B'], $distance); 
	$sa = Sa($conn, $var['LatA'], $var['LonA'], $var['LatB'], $var['LonB']);
	$gain_Antenna_A = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_A']);
	$gain_Antenna_B = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Diameter_B']);
	$gain_Antenna_A_Extra = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Extra_diameter_A']);
	$gain_Antenna_B_Extra = GetAnthenaParams($conn, $var['Manufacturer'], $var['Frequency'], $var['Extra_diameter_B']);
	if($var['Version'] != 4) $EIRP = max($gain_Antenna_A, $gain_Antenna_B) + $var['TransmitPow']; 
	else $EIRP = max($gain_Antenna_A, $gain_Antenna_B, $gain_Antenna_A_Extra, $gain_Antenna_B_Extra) + $var['TransmitPow'];

	
	$RXThreshold = getThreshold($conn, $var['Frequency'], $var['Band_Width'], $var['Standart'], $var['FEC'], $var['Modulation'], $Product);
	$FadeMargin = getFadeMargin($var['Antenna_Menu'], $var['Version'], $var['Frequency_FD'], $var['Transmit_FD'], $distance, $var['Frequency'], $var['TransmitPow'], $gain_Antenna_A, $var['Losses'], $gain_Antenna_B, $gain_Antenna_A_Extra, $gain_Antenna_B_Extra, $RXThreshold, $a, $var['Prim_Site_A'] , $var['Prim_Site_B'] , $var['Stand_Site_A'] , $var['Stand_Site_B']);

	
	$Geoclimatic = Geoclimatic($PointRefractGrad, $sa, $var['Antenna_Height_A'], $var['Antenna_Height_B'], $distance);
	$SelectiveOutage = SelectiveOutage($var['Version'], $var['LatA'], $var['LatB'], $Geoclimatic, $distance, $var['Frequency'], $FadeMargin );
	$Selective = Selective($SelectiveOutage, $distance, $conn, $var['Band_Width'], $var['Modulation'], $Product );
	$RainRate = RainRate($conn, $var['Version'], $var['Frequency'], $var['Temp_Rain_Zone'], $distance, $FadeMargin, $SelectiveOutage ); 
	$PlotRain = PlotRain($RainRate);
	$RSSI = RSSI($FadeMargin, $var['Version']);
	$MaxTransmitterPower = MaxTransmitterPower($conn, $Product, $var['Frequency'], $var['Modulation']);
	$GetMaxCap = GetMaxCap($conn, $Product, $var['Modulation'], $var['Band_Width'], $var['Standart'], $var['FEC']);
	if($var['Version'] == 2) $FM = $FadeMargin['FM_Main'];
	else $FM = $FadeMargin['FM106'];
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
				$result[] = array(
				'MultipathVert' =>	$TotalMultipath['TotalPath106'],
				'MultipathHor' =>	$TotalMultipath['TotalPath106'],
				'Rain_Vert' => $RainAvailVert,
				'Rain_Hor' => $RainAvailHor,
				'Multipath_Rain_Vert' => $MRainAvailVert,
				'Multipath_Rain_Hor' => $MRainAvailHor, 
				'Error_Vert' => $Output_ErroredTimeV,
				'Error_Hor' => $Output_ErroredTimeH
				); 
			}
		}	
	}
	echo json_encode($result);
}
	
 ?>