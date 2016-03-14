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
	console.log(RainZoneValue);
	$.post( "AjaxFunctions.php", { func: 'RainZone', Zone: RainZone}, function(response)
	{	
		var Data = $.parseJSON(response);
		$.each(Data, function(i, item)
		{
			$(RainZoneValue).text(''+item.Zone+'mm/h');
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
/*function recalculate(siteid)
{
	$('.' + siteid).each('input') // ..
	// check  if dropdown
	// if value = 0 -> return
	// check if numeric input
	// if empty return
	
	// call recalculate
}*/
