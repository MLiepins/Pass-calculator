$(document).ready(function()
{
//Elementu slēpšana.
	$('.field').hide();
	//$('.odu').hide();
	$('.AntennasAm').hide();
	$('.ExtraFreq').hide();
	$('.field3').hide();
	$('.prod3').hide();
	$('.prod4').hide();
	$('.ExtraAntennas').hide();
	$('.Antenna_Coupler').hide();
	$('.Coupler').hide();
	
	
	
	
	$('.AntennasAm').change(function()
	{
		var antennas = $('.AntennasAm').val();
		switch(antennas)
		{
			case '1':
				$('.field3').show();
				$('.ExtraAntennas').show(); 
				break;
		}
	});
	$('.Antenna_Coupler').change(function()
	{
		var value = $('.Antenna_Coupler').val();
		switch(value)
		{
			case '1':
				$('.Coupler').show();
				$('.ExtraAntennas').hide();
				$('.HSB_hide').hide(); 
				$('.HSB_Show').hide(); 				
				break;
			case '2':
				$('.Coupler').hide();
				$('.ExtraAntennas').show(); 
				break;
		}
	});
	
});

function manageVersionChange(el)
{
		var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); //Iegūst galvenā div elementa ID.  
		//console.log(site);
		//console.log($('.Version').val());
		var version = $('.'+site+'').find('select[name = Version]').val();
		$('.'+site+'').find('.AntennasAm').hide();
		switch(version)
		{
			case '1':
				$('.'+site+'').find('.odu').hide();
				$('.'+site+'').find('.Antenna_Coupler').hide();
				$('.'+site+'').find('.field').hide();
				$('.'+site+'').find('.AntennasAm').hide();
				$('.'+site+'').find('.ExtraFreq').hide();
				$('.'+site+'').find('.prod4').hide();	
				$('.'+site+'').find('.prod3').hide();
				$('.'+site+'').find('.ExtraAntennas').hide();

				$('.'+site+'').find('.Coupler').hide();
				$('.'+site+'').find('.HSB_hide').hide(); 
				$('.'+site+'').find('.HSB_Show').hide(); 					
				break;
			case '4':
				$('.'+site+'').find('.field').show();
				$('.'+site+'').find('.AntennasAm').show();
				$('.'+site+'').find('.ExtraFreq').show();
				$('.'+site+'').find('.odu').show();
				$('.'+site+'').find('.prod4').show();	
				$('.'+site+'').find('.prod3').hide();	
				$('.'+site+'').find('.ExtraAntennas').hide(); 
				$('.'+site+'').find('.Antenna_Coupler').hide();
				$('.'+site+'').find('.Coupler').hide();
				$('.'+site+'').find('.HSB_hide').hide(); 
				$('.'+site+'').find('.HSB_Show').hide(); 					
				break;
			case '3':
				$('.'+site+'').find('.field').show();
				$('.'+site+'').find('.odu').show();
				$('.'+site+'').find('.prod3').show();
				$('.'+site+'').find('.ExtraAntennas').show(); 
				$('.'+site+'').find('.Antenna_Coupler').hide();
				$('.'+site+'').find('.AntennasAm').hide();
				$('.'+site+'').find('.ExtraFreq').hide();
				$('.'+site+'').find('.prod4').hide();	
				$('.'+site+'').find('.Coupler').hide();
				$('.'+site+'').find('.HSB_hide').hide(); 
				$('.'+site+'').find('.HSB_Show').hide(); 	
				break;
			case '2':
				$('.'+site+'').find('.odu').show();
				$('.'+site+'').find('.Antenna_Coupler').show();
				$('.'+site+'').find('.field').hide();
				$('.'+site+'').find('.AntennasAm').hide();
				$('.'+site+'').find('.ExtraFreq').hide();
				$('.'+site+'').find('.prod4').hide();	
				$('.'+site+'').find('.prod3').hide();
				$('.'+site+'').find('.ExtraAntennas').hide(); 
				$('.'+site+'').find('.Coupler').hide();
				$('.'+site+'').find('.HSB_hide').hide(); 
				$('.'+site+'').find('.HSB_Show').hide(); 	
			break;
		}
}
	
	
function verChange(el)
{
		var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); //Iegūst galvenā div elementa ID.  
		var prodSelect = $('.'+site+'').find('select[name=Productx1]').empty();
		var version = $('.'+site+'').find('select[name = Version]').val();	
		//console.log($(el).val());
		//console.log(version); 
		$.post( "AjaxFunctions.php", { Version: version}, function(response){	
			//console.log(response);
			var Data = $.parseJSON(response);
			$.each(Data, function(i, item) {
			$('<option value="' + item.id +'">'+item.name+'</option>').appendTo(prodSelect);
			});
			manageVersionChange(el);
			//recalculate(site); 
		});
}

function prodChange(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); //Iegūst galvenā div elementa ID. 
	var product = $('.'+site+'').find('select[name = Productx1]').val();
	var version = $('.'+site+'').find('select[name = Version]').val();
	var ProdID = $('.'+site+'').find('div[name = ProdID]').empty();
	if(version == 1)
	{
		var odu = 0; 
		$.ajax
		({
			url: 'AjaxFunctions.php',
			data: {func: 'product', Prod: product, Odu: odu},
			type: 'post',
			success: function(result)
			{
				getFrequency(el, result);
				$(ProdID).val(result);
			}
		});	
	}
}

function oduChange(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); //Iegūst galvenā div elementa ID. 
	var product = $('.'+site+'').find('select[name = Productx1]').val();
	var version = $('.'+site+'').find('select[name = Version]').val();
	var odu = $('.'+site+'').find('select[name = Odu]').val();
	var ProdID = $('.'+site+'').find('div[name = ProdID]').empty();
	if(version != 1)
	{
		$.ajax
		({
			url: 'AjaxFunctions.php',
			data: {func: 'product', Prod: product, Odu: odu},
			type: 'post',
			success: function(result)
			{
				getFrequency(el, result);
				$(ProdID).val(result);
			}
		});	
	}	
}
function getFrequency(el, ProdID)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var freqSelect = $('.'+site+'').find('select[name=Frequency]').empty();
	$.post( "AjaxFunctions.php", { func: 'frequence', ProdID: ProdID}, function(response)
	{	
		var Data = $.parseJSON(response);
		$('<option value="0">Please select frequency</option>').appendTo(freqSelect);
		$.each(Data, function(i, item)
		{
			$('<option value="' + item.value + '">' + item.value + '</option>').appendTo(freqSelect);
		});
	});
}

function FreqChange(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID =  $('.'+site+'').find('div[name = ProdID]').val();
	var bandSelect = $('.'+site+'').find('select[name=Bandwidth]').empty();
	var freqSelect = $('.'+site+'').find('select[name=Frequency]').val();
	getAntennaManuf(el);
	$.post( "AjaxFunctions.php", { func: 'bandwidth', ProdID: ProdID, Frequency: freqSelect}, function(response)
	{	
		var Data = $.parseJSON(response);
		$('<option value="0">Please select Bandwidth</option>').appendTo(bandSelect);
		$.each(Data, function(i, item)
		{
			$('<option value="'+item.Bandwidth+'|'+item.Standart+'">'+item.Bandwidth+' ('+item.Standart+')</option>').appendTo(bandSelect);
		});
	});	
}

function changeFEC(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID = ProdID =  $('.'+site+'').find('div[name = ProdID]').val();
	var modSelect = $('.'+site+'').find('select[name=rModulation]').empty();
	var FEC =  $('.'+site+'').find('select[name=FEC]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var BandwidthTMP = $('.'+site+'').find('select[name=Bandwidth]').val();
	var BandSplit = BandwidthTMP.split("|");
	var Bandwidth = BandSplit[0];
	var Standart = BandSplit[1]; 
	//alert(ProdID+"  "+FEC+" "+Frequency+" "+Bandwidth+" "+Standart);
	
	
	$.post( "AjaxFunctions.php", { func: 'Modulation', ProdID: ProdID, Frequency: Frequency, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC }, function(response)
	{	
		var Data = $.parseJSON(response);
		$('<option value="0">Please select modulation</option>').appendTo(modSelect);
		$.each(Data, function(i, item)
		{
			$('<option value="'+item.Modulation+'">'+item.Modulation+'</option>').appendTo(modSelect);
		});
	});		
}
function changeModulation(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID = ProdID = $('.'+site+'').find('div[name = ProdID]').val();
	var Modulation = $('.'+site+'').find('select[name=rModulation]').val();
	var FEC =  $('.'+site+'').find('select[name=FEC]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var BandwidthTMP = $('.'+site+'').find('select[name=Bandwidth]').val();
	var BandSplit = BandwidthTMP.split("|");
	var Bandwidth = BandSplit[0];
	var Standart = BandSplit[1]; 
	
	var capacity = $('.'+site+'').find('span[name=capacity]').text('');
	var transmitterSelect = $('.'+site+'').find('select[name=Transmitter]').empty();
		
	//alert(site+" "+ProdID+" "+Modulation+" "+Bandwidth+" "+Standart+" "+FEC);
	
	$.post( "AjaxFunctions.php", { func: 'Capacity', ProdID: ProdID, Modulation: Modulation, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC }, function(response)
	{	
		var Data = $.parseJSON(response);
		//alert(response);
		$.each(Data, function(i, item)
		{
		$(capacity).text('For capacities up to: '+item.capacity+'Mbps');
		}); 
	});	
	$.post( "AjaxFunctions.php", { func: 'Transmitter', ProdID: ProdID, Modulation: Modulation, Frequency: Frequency}, function(response)
	{	
		var Data = $.parseJSON(response);
		$.each(Data, function(i, item)
		{
			$('<option value="0">Please select transmitter power</option>').appendTo(transmitterSelect);
			$.each(Data, function(i, item)
			{
				$('<option value="'+item.transmitterPower+'">'+item.transmitterPower+'</option>').appendTo(transmitterSelect);
			});
		}); 
	});	
}
function changeRainzone(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var RainZone = $(el).val(); 
	var RainZoneValue = $('.'+site+'').find('span[name=RainZoneResult]').text('');
	var RainZoneTMP = $('.'+site+'').find('div[name=RainZoneTMP]').empty();
	console.log(RainZoneValue);
	$.post( "AjaxFunctions.php", { func: 'RainZone', Zone: RainZone}, function(response)
	{	
		var Data = $.parseJSON(response);
		$.each(Data, function(i, item)
		{
			$(RainZoneValue).text(''+item.Zone+'mm/h');
			$(RainZoneTMP).val('+item.Zone+');
		}); 
	});		
}

function changeCoordinates(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var LatA = $('.'+site+'').find('input[name=LatA]').val();
	var LatB = $('.'+site+'').find('input[name=LatB]').val();	
	var LonA = $('.'+site+'').find('input[name=LonA]').val();
	var LonB = $('.'+site+'').find('input[name=LonB]').val();
	var distanceResult = $('.'+site+'').find('span[name=DistanceResult]').text('');
	
	if(LatA && LatB && LonA && LonB)
	{
		console.log("Execute");
		$.post( "AjaxFunctions.php", { func: 'Distance', LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB }, function(response)
		{	
			var Data = $.parseJSON(response);
				$(distanceResult).text('Distance: '+Data+'km');
		});		
	}
}
function getAntennaManuf(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID =  $('.'+site+'').find('div[name = ProdID]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').empty();
	if(ProdID != 0)
	{
		$.post( "AjaxFunctions.php", { func: 'antennaManuf', ProdID: ProdID, Frequency: Frequency}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="0">Please select antenna manufacturer</option>').appendTo(antennaManuf);
			$.each(Data, function(i, item)
			{
				$('<option value="'+item.ID+'">'+item.Name+'</option>').appendTo(antennaManuf);
			});
		});	
		
		
	}
}
function changeAntennaManuf(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var diameter_a = $('.'+site+'').find('select[name = diameter_A]').empty();
	var diameter_b = $('.'+site+'').find('select[name = diameter_B]').empty();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	if(antennaManuf != 0)
	{
		$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="0">Please select antenna A diameter</option>').appendTo(diameter_a);
			$('<option value="0">Please select antenna B diameter</option>').appendTo(diameter_b);
			$.each(Data, function(i, item)
			{
				$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
				$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
			});
		});	
	}
}

function change_A_Diameter(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_A]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dA]').text('');
	var AntennaA_tmp = $('.'+site+'').find('div[name=AntennaA_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
			$(DiameterResult).text(''+Data+'dBi');
			$(AntennaA_tmp).val(Data);
	});	
}
function change_B_Diameter(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_B]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dB]').text('');
	var AntennaB_tmp = $('.'+site+'').find('div[name=AntennaB_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
		$(DiameterResult).text(''+Data+'dBi');
		$(AntennaB_tmp).val(Data);
	});	
}

function calculate(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID = ProdID = $('.'+site+'').find('div[name = ProdID]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var BandwidthTMP = $('.'+site+'').find('select[name=Bandwidth]').val();
	var BandSplit = BandwidthTMP.split("|");
	var Bandwidth = BandSplit[0];
	var Standart = BandSplit[1]; 
	var FEC =  $('.'+site+'').find('select[name=FEC]').val();
	var Temperature = $('.'+site+'').find('input[name=Temperature]').val();
	var Modulation = $('.'+site+'').find('select[name=rModulation]').val();
	var RainZoneTMP = $('.'+site+'').find('div[name=RainZoneTMP]').val();
	var LatA = $('.'+site+'').find('input[name=LatA]').val();
	var LatB = $('.'+site+'').find('input[name=LatB]').val();	
	var LonA = $('.'+site+'').find('input[name=LonA]').val();
	var LonB = $('.'+site+'').find('input[name=LonB]').val();
	var AntennaHeightA = $('.'+site+'').find('input[name=AntennaA]').val();
	var AntennaHeightB = $('.'+site+'').find('input[name=AntennaB]').val();
	var Losses = $('.'+site+'').find('input[name=Losses]').val();
	var AntennaA_tmp = $('.'+site+'').find('div[name=AntennaA_tmp]').val();
	var AntennaB_tmp = $('.'+site+'').find('div[name=AntennaB_tmp]').val();
	if(ProdID && Frequency && Bandwidth && Standart && FEC && Temperature && Modulation && RainZoneTMP && LatA && LatB && LonA && LonB && AntennaHeightA && AntennaHeightB && Losses && AntennaA_tmp && AntennaB_tmp )
	{
		$.post( "AjaxFunctions.php", { func: 'calculate', ProdID: ProdID, Frequency: Frequency, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Temperature: Temperature, Modulation: Modulation, Rainzone: RainzoneTMP, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Losses: Losses, AntennaA_tmp: AntennaA_tmp, AntennaB_tmp: AntennaB_tmp}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="0">Please select antenna manufacturer</option>').appendTo(antennaManuf);
			$.each(Data, function(i, item)
			{
				$('<option value="'+item.ID+'">'+item.Name+'</option>').appendTo(antennaManuf);
			});
		});	
	}	
	else
	{
		console.log("Not Executed. ");
		if(!ProdID) console.log("ProdID is missing");
		if(!Frequency) console.log("Frequency is missing");
		if(!Bandwidth) console.log("Bandwidth is missing");
		if(!Standart) console.log("Standart is missing");
		if(!FEC) console.log("FEC is missing");
		if(!Temperature) console.log("Temperature is missing");
		if(!Modulation) console.log("Modulation is missing");
		if(!RainZoneTMP) console.log("RainZoneTMP is missing");
		if(!LatA) console.log("LatA is missing");
		if(!LatB) console.log("LatB is missing");
		if(!LonA) console.log("LonA is missing");
		if(!LonB) console.log("LonB is missing");
		if(!AntennaHeightA) console.log("AntennaHeightA is missing");
		if(!AntennaHeightB) console.log("AntennaHeightB is missing");
		if(!Losses) console.log("Losses is missing");
		if(!AntennaA_tmp) console.log("AntennaA_tmp is missing");
		if(!AntennaB_tmp) console.log("AntennaB_tmp is missing");
	}
}




/*function recalculate(siteid)
{
	$('.' + siteid).each('input') // ..
	// check  if dropdown
	// if value = 0 -> return
	// check if numeric input
	// if empty return
	
	// call recalculate
}*/
