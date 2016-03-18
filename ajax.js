$(document).ready(function()
{
	
});

function manageVersionChange(el)
{
		var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); //Iegūst galvenā div elementa ID.  
		//console.log(site);
		//console.log($('.Version').val());
		var version = $('.'+site+'').find('select[name = Version]').val();
		if(version == 1)
		{
			$('.'+site+'').find('.odu').hide();	
			$('.'+site+'').find('tr[class = h_AntennaMode]').hide();
			$('.'+site+'').find('td[class = extra_diameter]').hide();
			$('.'+site+'').find('tr[class = Coupler_Atten]').hide();
			$('.'+site+'').find('tr[class = Standby]').hide();
			$('.'+site+'').find('tr[class = Antenna_SD]').hide();
			$('.'+site+'').find('td[class = Antenna_SD]').hide();
			$('.'+site+'').find('tr[class = Diversity]').hide();
			$('.'+site+'').find('tr[class = Diversity]').hide();
			$('.'+site+'').find('td[class = Antenna_FD]').hide();
			$('.'+site+'').find('tr[class = FD_params]').hide();
		}
		if(version == 2)
		{
			$('.'+site+'').find('.odu').show();
			$('.'+site+'').find('tr[class = h_AntennaMode]').show();
			$('.'+site+'').find('td[class = extra_diameter]').hide();
			$('.'+site+'').find('tr[class = Coupler_Atten]').hide();
			$('.'+site+'').find('tr[class = Standby]').hide();
			$('.'+site+'').find('tr[class = Antenna_SD]').hide();
			$('.'+site+'').find('td[class = Antenna_SD]').hide();
			$('.'+site+'').find('tr[class = Diversity]').hide();
			$('.'+site+'').find('tr[class = Diversity]').hide();
			$('.'+site+'').find('td[class = Antenna_FD]').hide();
			$('.'+site+'').find('tr[class = FD_params]').hide();
		}
		if(version == 3)
		{
			$('.'+site+'').find('.odu').show();
			$('.'+site+'').find('tr[class = h_AntennaMode]').hide();
			$('.'+site+'').find('td[class = extra_diameter]').hide();
			$('.'+site+'').find('tr[class = Coupler_Atten]').hide();
			$('.'+site+'').find('tr[class = Standby]').hide();
			$('.'+site+'').find('tr[class = Antenna_SD]').show();
			$('.'+site+'').find('td[class = Antenna_SD]').show();
			$('.'+site+'').find('tr[class = Diversity]').hide();
			$('.'+site+'').find('td[class = Antenna_FD]').hide();
			$('.'+site+'').find('tr[class = FD_params]').hide();			
		}
		if(version == 4)
		{
			$('.'+site+'').find('.odu').show();
			$('.'+site+'').find('tr[class = h_AntennaMode]').hide();
			$('.'+site+'').find('td[class = extra_diameter]').hide();
			$('.'+site+'').find('tr[class = Coupler_Atten]').hide();
			$('.'+site+'').find('tr[class = Standby]').hide();
			$('.'+site+'').find('tr[class = Antenna_SD]').hide();
			$('.'+site+'').find('td[class = Antenna_SD]').hide();
			$('.'+site+'').find('tr[class = Diversity]').show();
			$('.'+site+'').find('td[class = Antenna_FD]').hide();
			$('.'+site+'').find('tr[class = FD_params]').hide();
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
			prev_calc(el);
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
				prev_calc(el);
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
				prev_calc(el);
				
			}
		});	
	}	
}
function getFrequency(el, ProdID)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var freqSelect = $('.'+site+'').find('select[name=Frequency]').empty();
	var version = $('.'+site+'').find('select[name = Version]').val();
	if(version != 4)
	{
		$.post( "AjaxFunctions.php", { func: 'frequence', ProdID: ProdID}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="">Please select frequency</option>').appendTo(freqSelect);
			$.each(Data, function(i, item)
			{
				$('<option value="' + item.value + '">' + item.value + '</option>').appendTo(freqSelect);
			});
		});
	}
	else 
	{
		var freqSelect_FD = $('.'+site+'').find('select[name=Frequency_FD]').empty();
		$.post( "AjaxFunctions.php", { func: 'frequence', ProdID: ProdID}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="">Please select frequency</option>').appendTo(freqSelect);
			$('<option value="">Please select frequency FD</option>').appendTo(freqSelect_FD);
			$.each(Data, function(i, item)
			{
				$('<option value="' + item.value + '">' + item.value + '</option>').appendTo(freqSelect);
				$('<option value="' + item.value + '">' + item.value + '</option>').appendTo(freqSelect_FD);
			});
		});
	}
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
		$('<option value="">Please select Bandwidth</option>').appendTo(bandSelect);
		$.each(Data, function(i, item)
		{
			$('<option value="'+item.Bandwidth+'|'+item.Standart+'">'+item.Bandwidth+' ('+item.Standart+')</option>').appendTo(bandSelect);
		});
		prev_calc(el);
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
		$('<option value="">Please select modulation</option>').appendTo(modSelect);
		$.each(Data, function(i, item)
		{
			$('<option value="'+item.Modulation+'">'+item.Modulation+'</option>').appendTo(modSelect);
		});
		prev_calc(el);
	});		
}
function changeModulation(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var version = $('.'+site+'').find('select[name = Version]').val();
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
	
	if(version != 4)
	{
		$.post( "AjaxFunctions.php", { func: 'Transmitter', ProdID: ProdID, Modulation: Modulation, Frequency: Frequency}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="">Please select transmitter power</option>').appendTo(transmitterSelect);
			$.each(Data, function(i, item)
			{
				var MinPower = item.MinPower;
				var MaxPower = item.MaxPower;
				//console.log("MinPower: "+MinPower+ " MaxPower: "+MaxPower)
				for(var i = MinPower; i <= MaxPower; i++)
				{
					$('<option value="'+i+'">'+i+'</option>').appendTo(transmitterSelect);
				}
			}); 
		});	
	}
	else
	{
		var transmitter_FD = $('.'+site+'').find('select[name = Transmitter_FD]').empty(); 
		$.post( "AjaxFunctions.php", { func: 'Transmitter', ProdID: ProdID, Modulation: Modulation, Frequency: Frequency}, function(response)
		{	
			var Data = $.parseJSON(response);
			$('<option value="">Please select transmitter power</option>').appendTo(transmitterSelect);
			$('<option value="">Please select transmitter power</option>').appendTo(transmitter_FD);
			$.each(Data, function(i, item)
			{
				var MinPower = item.MinPower;
				var MaxPower = item.MaxPower;
				//console.log("MinPower: "+MinPower+ " MaxPower: "+MaxPower)
				for(var i = MinPower; i <= MaxPower; i++)
				{
					$('<option value="'+i+'">'+i+'</option>').appendTo(transmitterSelect);
					$('<option value="'+i+'">'+i+'</option>').appendTo(transmitter_FD);
				}
			}); 
		});	
	}
	prev_calc(el);
}
function changeRainzone(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var RainZone = $(el).val(); 
	var RainZoneValue = $('.'+site+'').find('span[name=RainZoneResult]').text('');
	var RainZoneTMP = $('.'+site+'').find('div[name=RainZoneTMP]').empty();
	$.post( "AjaxFunctions.php", { func: 'RainZone', Zone: RainZone}, function(response)
	{	
		var Data = $.parseJSON(response);
		$.each(Data, function(i, item)
		{
			$(RainZoneValue).text(''+item.Zone+'mm/h');
			$(RainZoneTMP).val('+item.Zone+');
		}); 
		prev_calc(el);
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
	var distance_tmp = $('.'+site+'').find('div[name=Distance_tmp]').text('');

	if(LatA && LatB && LonA && LonB)
	{
		$.post( "AjaxFunctions.php", { func: 'Distance', LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB }, function(response)
		{	
			var Data = $.parseJSON(response);
				$(distanceResult).text('Distance: '+Data+'km');
				$(distance_tmp).val(Data);  
				prev_calc(el);
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
			$('<option value="">Please select antenna manufacturer</option>').appendTo(antennaManuf);
			$.each(Data, function(i, item)
			{
				$('<option value="'+item.ID+'">'+item.Name+'</option>').appendTo(antennaManuf);
			});
			prev_calc(el);
		});	
		
		
	}
}
function changeAntennaManuf(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class');
	var version = $('.'+site+'').find('select[name = Version]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var diameter_a = $('.'+site+'').find('select[name = diameter_A]').empty();
	var diameter_b = $('.'+site+'').find('select[name = diameter_B]').empty();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	if(antennaManuf != 0)
	{
		if(version == 1)
		{
			$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
			{	
				var Data = $.parseJSON(response);
				$('<option value="">Please select antenna A diameter</option>').appendTo(diameter_a);
				$('<option value="">Please select antenna B diameter</option>').appendTo(diameter_b);
				$.each(Data, function(i, item)
				{
					$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
					$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
				});
				prev_calc(el);
			});	
		}
		if(version == 2)
		{
			var mode = $('.'+site+'').find('select[name = AntennaMode]').val();
			if(mode == 1)
			{
				$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
				{	
					var Data = $.parseJSON(response);
					$('<option value="">Please select antenna A diameter</option>').appendTo(diameter_a);
					$('<option value="">Please select antenna B diameter</option>').appendTo(diameter_b);
					$.each(Data, function(i, item)
					{
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
					});
					prev_calc(el);
				});	
			}
			if(mode == 2)
			{
				var diameter_a2 = $('.'+site+'').find('span[name = diameter_A2]').empty();
				var diameter_b2 = $('.'+site+'').find('span[name = diameter_B2]').empty();
				$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
				{	
					var Data = $.parseJSON(response);
					$('<option value="0">Please select antenna A diameter</option>').appendTo(diameter_a);
					$('<option value="0">Please select antenna B diameter</option>').appendTo(diameter_b);
					//$('<option value="0">Please select antenna A2 diameter</option>').appendTo(diameter_a2);
					//$('<option value="0">Please select antenna B2 diameter</option>').appendTo(diameter_b2);
					$.each(Data, function(i, item)
					{
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
						//$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a2);
						//$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b2);
					});
					prev_calc(el);
				});
			}
		}
		if(version == 3)
		{
				var diameter_A_SD = $('.'+site+'').find('select[name = diameter_A_SD]').empty();
				var diameter_B_SD = $('.'+site+'').find('select[name = diameter_B_SD]').empty();
				$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
				{	
					var Data = $.parseJSON(response);
					$('<option value="">Please select antenna A diameter</option>').appendTo(diameter_a);
					$('<option value="">Please select antenna B diameter</option>').appendTo(diameter_b);
					$('<option value="">Please select antenna A SD diameter</option>').appendTo(diameter_A_SD);
					$('<option value="">Please select antenna B SD diameter</option>').appendTo(diameter_B_SD);
					$.each(Data, function(i, item)
					{
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_A_SD);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_B_SD);
					});
					prev_calc(el);
				});	
		}
		if(version == 4)
		{
			var amount = $('.'+site+'').find('select[name = Antenas_amount]').val();
			if(amount == 1)
			{
				$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
				{	
					var Data = $.parseJSON(response);
					$('<option value="">Please select antenna A diameter</option>').appendTo(diameter_a);
					$('<option value="">Please select antenna B diameter</option>').appendTo(diameter_b);
					$.each(Data, function(i, item)
					{
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
					});
					prev_calc(el);
				});		
			}
			if(amount == 2)
			{
				var diameter_A_FD = $('.'+site+'').find('select[name = diameter_A_FD]').empty();
				var diameter_B_FD = $('.'+site+'').find('select[name = diameter_B_FD]').empty();
				$.post( "AjaxFunctions.php", { func: 'antennaDiameter', antennaManuf: antennaManuf, Frequency: Frequency}, function(response)
				{	
					var Data = $.parseJSON(response);
					$('<option value="">Please select antenna A diameter</option>').appendTo(diameter_a);
					$('<option value="">Please select antenna B diameter</option>').appendTo(diameter_b);
					$('<option value="">Please select antenna A FD diameter</option>').appendTo(diameter_A_FD);
					$('<option value="">Please select antenna B FD diameter</option>').appendTo(diameter_B_FD);
					$.each(Data, function(i, item)
					{
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_a);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_b);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_A_FD);
						$('<option value="'+item.Diameter+'">'+item.Diameter+'</option>').appendTo(diameter_B_FD);
					});
					prev_calc(el);
				});	
			}
		}
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
	var version = $('.'+site+'').find('select[name = Version]').val();
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
			$(DiameterResult).text(''+Data+'dBi');
			$(AntennaA_tmp).val(Data);
			prev_calc(el);
		if(version == 2)
		{
			var diameter_a2 = $('.'+site+'').find('span[name = diameter_A2]').empty();
			var result_a2 = $('.'+site+'').find('span[name = result_dA2]').empty();
			$(diameter_a2).text(''+Diameter+'');
			$(diameter_a2).val(Diameter);
			$(result_a2).text(''+Data+' dBi');
		}
	});	
		
}

function change_A_Diameter_SD(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_A_SD]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dA_SD]').text('');
	var AntennaA_tmp = $('.'+site+'').find('div[name=AntennaASD_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
			$(DiameterResult).text(''+Data+'dBi');
			$(AntennaA_tmp).val(Data);
			prev_calc(el);
	});	
}
function change_A_Diameter_FD(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_A_FD]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dA_FD]').text('');
	var AntennaA_tmp = $('.'+site+'').find('div[name=AntennaAFD_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
			$(DiameterResult).text(''+Data+'dBi');
			$(AntennaA_tmp).val(Data);
			prev_calc(el);
	});	
}
function change_A2_Diameter(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('span[name = diameter_A2]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dA2]').text('');
	
	var AntennaA_tmp = $('.'+site+'').find('div[name=AntennaA2_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
			$(DiameterResult).text(''+Data+'dBi');
			$(AntennaA_tmp).val(Data);
			prev_calc(el);
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
	var version = $('.'+site+'').find('select[name = Version]').val();
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
		$(DiameterResult).text(''+Data+'dBi');
		$(AntennaB_tmp).val(Data);
		prev_calc(el);
		if(version == 2)
		{
			var diameter_b2 = $('.'+site+'').find('span[name = diameter_B2]').empty();
			var result_b2 = $('.'+site+'').find('span[name = result_dB2]').empty();
			$(diameter_b2).text(''+Diameter+'');
			$(diameter_b2).val(Diameter);
			$(result_b2).text(''+Data+' dBi');
		}
	});	
}
function change_B2_Diameter(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_B2]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dB2]').text('');
	var AntennaB_tmp = $('.'+site+'').find('div[name=AntennaB2_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
		$(DiameterResult).text(''+Data+'dBi');
		$(AntennaB_tmp).val(Data);
		prev_calc(el);
	});	
}
function change_B_Diameter_SD(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_B_SD]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dB_SD]').text('');
	var AntennaB_tmp = $('.'+site+'').find('div[name=AntennaBSD_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
		$(DiameterResult).text(''+Data+'dBi');
		$(AntennaB_tmp).val(Data);
		prev_calc(el);
	});	
}
function change_B_Diameter_FD(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Diameter = $('.'+site+'').find('select[name = diameter_B_FD]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var DiameterResult = $('.'+site+'').find('span[name=result_dB_FD]').text('');
	var AntennaB_tmp = $('.'+site+'').find('div[name=AntennaBFD_tmp]').empty();
	
	$.post( "AjaxFunctions.php", { func: 'antenna_DBI', antennaManuf: antennaManuf, Frequency: Frequency, Diameter: Diameter}, function(response)
	{	
		var Data = $.parseJSON(response);
		$(DiameterResult).text(''+Data+'dBi');
		$(AntennaB_tmp).val(Data);
		prev_calc(el);
	});	
}
function changeTrans(el)
{
	prev_calc(el);
}

function changeAntennaMode(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Mode_Value = $('.'+site+'').find('select[name = AntennaMode]').val();
	if(Mode_Value == 2)
	{
		$('.'+site+'').find('td[class = extra_diameter]').show();
		$('.'+site+'').find('tr[class = Coupler_Atten]').hide();
		$('.'+site+'').find('tr[class = Primary]').show();
		$('.'+site+'').find('tr[class = Standby]').hide();
	}
	if(Mode_Value == 1)
	{
		$('.'+site+'').find('tr[class = Coupler_Atten]').show();
		$('.'+site+'').find('td[class = extra_diameter]').hide();
		$('.'+site+'').find('tr[class = Primary]').hide();
		$('.'+site+'').find('tr[class = Standby]').show();
	}
}
function ChangeAmountofAntenas(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var Select_Value = $('.'+site+'').find('select[name = Antenas_amount]').val();
	if(Select_Value == 1)
	{
		$('.'+site+'').find('td[class = Antenna_FD]').hide();
		$('.'+site+'').find('tr[class = FD_params]').hide();
	}
	if(Select_Value == 2)
	{
		$('.'+site+'').find('td[class = Antenna_FD]').show();
		$('.'+site+'').find('tr[class = FD_params]').show();
	}	
}

function prev_calc(el)
{
	var site = $(el).parent().parent().parent().parent().parent().parent().attr('class'); 
	var ProdID = ProdID = $('.'+site+'').find('div[name = ProdID]').val();
	var version = $('.'+site+'').find('select[name = Version]').val();
	var Odu = $('.'+site+'').find('select[name = Odu]').val();
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var BandwidthTMP = $('.'+site+'').find('select[name=Bandwidth]').val();
	var BandSplit = BandwidthTMP.split("|");
	var Bandwidth = BandSplit[0];
	var Standart = BandSplit[1]; 
	
	var FEC =  $('.'+site+'').find('select[name=FEC]').val();
	var Temperature = $('.'+site+'').find('input[name=Temperature]').val();	
	var Modulation = $('.'+site+'').find('select[name=rModulation]').val();	
	var RainZone = $('.'+site+'').find('select[name=Rainzone]').val();
	var LatA = $('.'+site+'').find('input[name=LatA]').val();
	var LatB = $('.'+site+'').find('input[name=LatB]').val();	
	var LonA = $('.'+site+'').find('input[name=LonA]').val();
	var LonB = $('.'+site+'').find('input[name=LonB]').val();	
	var Transmitter = $('.'+site+'').find('select[name=Transmitter]').val();
	var Antenna_Amount = $('.'+site+'').find('select[name = Antenas_amount]').val();
	var AntennaHeightA = $('.'+site+'').find('input[name=AntennaA]').val();
	var AntennaHeightB = $('.'+site+'').find('input[name=AntennaB]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();	
	var diameter_a = $('.'+site+'').find('select[name = diameter_A]').val();
	var diameter_b = $('.'+site+'').find('select[name = diameter_B]').val();
	
	var diameter_a2 = $('.'+site+'').find('span[name = diameter_A2]').val();
	var diameter_b2 = $('.'+site+'').find('span[name = diameter_B2]').val();
	
	var Main_Freq = $('.'+site+'').find('input[name = Main_Freq]').val();
	var Div_Freq = $('.'+site+'').find('input[name = Div_Freq]').val();
	var Losses = $('.'+site+'').find('input[name=Losses]').val();
	
	var diameter_A_SD = $('.'+site+'').find('select[name = diameter_A_SD]').val();
	var diameter_B_SD = $('.'+site+'').find('select[name = diameter_B_SD]').val();
	var Stand_Site_A = $('.'+site+'').find('input[name = Stand_Site_A]').val();
	var Stand_Site_B = $('.'+site+'').find('input[name = Stand_Site_B]').val();
	var Prim_Site_A = $('.'+site+'').find('input[name = Prim_Site_A]').val();
	var Prim_Site_B = $('.'+site+'').find('input[name = Prim_Site_B]').val();
	var RXThresholdResult = $('.'+site+'').find('span[name = Rx_Threshold]').text('');

	var Signal_level = $('.'+site+'').find('span[name = signal_level]').text('');	
	var RSSI = $('.'+site+'').find('span[name = RSSI]').text('');	
	var Fade_Margin = $('.'+site+'').find('span[name = Fade_Margin]').text('');
	var EIRP = $('.'+site+'').find('span[name = EIRP]').text('');
	
	var	Antenna_A_FD = $('.'+site+'').find('select[name = diameter_A_FD]').val();
	var	Antenna_B_FD = $('.'+site+'').find('select[name = diameter_B_FD]').val();
	var SD_Sep_A = $('.'+site+'').find('input[name = SD_sep_A]').val();
	var SD_Sep_B = $('.'+site+'').find('input[name = SD_sep_B]').val();	
	var Frequency_FD = $('.'+site+'').find('select[name = Frequency_FD]').val();
	var Transmit_FD = $('.'+site+'').find('select[name = Transmitter_FD]').val();
	var suc_Button = $('.'+site+'').find('button[name = Calculate]');
	
	var M_Path_Vert = $('.'+site+'').find('span[name = M_path_vert]').text('');	
	var M_Path_Hori = $('.'+site+'').find('span[name = M_path_hori]').text('');
	var Rain_vert = $('.'+site+'').find('span[name = rain_vert]').text('');	
	var Rain_hori = $('.'+site+'').find('span[name = rain_hori]').text('');		
	var M_rain_vert = $('.'+site+'').find('span[name = M_rain_vert]').text('');	
	var M_rain_hori = $('.'+site+'').find('span[name = M_rain_hori]').text('');				
	var error_Time_Vert = $('.'+site+'').find('span[name = error_Time_Vert]').text('');	
	var error_Time_Hori = $('.'+site+'').find('span[name = error_Time_Hori]').text('');		
	if(version == 1)
	{
		if(ProdID && version && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && AntennaHeightA && AntennaHeightB && antennaManuf && diameter_a && diameter_b && Losses)
		{
			console.log("Executed Version 1"); 
			$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
			{	
				//alert(response);
				var Data = $.parseJSON(response);
				//console.log(response);
				$.each(Data, function(i, item)
				{
					//console.log(item.RXThreshold);
					$(RXThresholdResult).text(''+item.RXThreshold+' dBm');
					$(Signal_level).text(''+item.Rec_Sig_Level+' dBm');
					$(RSSI).text(''+item.RSSI+' V');
					$(Fade_Margin).text(''+item.FadeMargin+' dBm');
					$(EIRP).text(''+item.EIRP+' dBm');
				});
			});	
			$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );
		}
		else
		{
			$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );	
		}
	}	
	if(version == 2)
	{
		var mode = $('.'+site+'').find('select[name = AntennaMode]').val();
		var signal_level_prim = $('.'+site+'').find('span[name = signal_level_prim]').text('');
		var signal_level_stand = $('.'+site+'').find('span[name = signal_level_stand]').text('');
		var RSSI_prim = $('.'+site+'').find('span[name = RSSI_prim]').text('');		
		var RSSI_stand = $('.'+site+'').find('span[name = RSSI_stand]').text('');		
		var Fade_Margin_prim = $('.'+site+'').find('span[name = Fade_Margin_prim]').text('');		
		var Fade_Margin_stand = $('.'+site+'').find('span[name = Fade_Margin_stand]').text('');	
		if(mode == 1)
		{
			if(ProdID && version && Odu && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && AntennaHeightA && AntennaHeightB && diameter_a && Prim_Site_A && Prim_Site_B && Stand_Site_A && Stand_Site_B && Losses)
			{
				console.log("Executed Version 2 with Coupler.");
				$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: mode, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
				{	
					var Data = $.parseJSON(response);
					$.each(Data, function(i, item)
					{						
						$(RXThresholdResult).text(''+item.RXThreshold+' dB');			
						$(EIRP).text(''+item.EIRP+' dB');
						$(signal_level_prim).text(''+item.RX+' dBm');
						$(signal_level_stand).text(''+item.RX_HSB+' dBm');
						$(RSSI_prim).text(''+item.RSSI+' V');
						$(RSSI_stand).text(''+item.RSSI_HSB+' V');
						$(Fade_Margin_prim).text(''+item.FadeMargin_Main+' dB');
						$(Fade_Margin_stand).text(''+item.FadeMargin_HSB+' dB');
					});
				});	
				$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );
			}
			else
			{
				$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );
			}
		}
		if(mode == 2)
		{
			if(ProdID && version && Odu && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && AntennaHeightA && AntennaHeightB && mode &&antennaManuf && diameter_a && diameter_b && Losses)
			{
				diameter_a2 = diameter_a; 
				diameter_b2 = diameter_b; 
				console.log("Executed Version 2 with 4 antennas"); 
				//console.log("Diameter A: "+diameter_a2+" "+diameter_b2);
				$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_a2, Extra_diameter_B: diameter_b2, Antenna_Menu: mode, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
				{	
					var Data = $.parseJSON(response);
					console.log(response);
					//alert(response);
					$.each(Data, function(i, item)
					{						
						//console.log(item.RXThreshold);
						$(RXThresholdResult).text(''+item.RXThreshold+' dBm');
						$(Signal_level).text(''+item.Rec_Sig_Level+' dBm');
						$(RSSI).text(''+item.RSSI+' V');
						$(Fade_Margin).text(''+item.FadeMargin+' dBm');
						$(EIRP).text(''+item.EIRP+' dBm');
					});
				});	
				$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );				
			}
			else
			{
				$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );
				if(!ProdID) console.log("ProdID undefined"); 
				if(!version) console.log("version undefined");
				if(!Odu)console.log("Odu undefined");
				if(!Frequency) console.log("Frequency undefined");
				if(!Bandwidth) console.log("Bandwidth undefined");
				if(!FEC) console.log("FEC undefined");
				if(!Temperature) console.log("Temperature undefined");
				if(!Modulation) console.log("Modulation undefined");
				if(!RainZone) console.log("RainZone undefined");
				if(!LatA) console.log("LatA undefined");
				if(!LatB) console.log("LatB undefined"); 
				if(!LonA) console.log("LonA undefined");
				if(!LonB) console.log("LonB undefined");
				if(!Transmitter) console.log("Transmitter undefined");
				if(!AntennaHeightA) console.log("AntennaHeightA undefined");
				if(!AntennaHeightB) console.log("AntennaHEightB undefined");
				if(!mode) console.log("Mode undefined");
				if(!antennaManuf) console.log("Antenna Manuf undefined");
				if(!diameter_a) console.log("diameter_A undefined");
				if(!diameter_b) console.log("diameter_B undefined");
				if(!Losses) $(Losses).removeClass( "none" ).addClass( "has-error" );
			}
		}	
	}
	if(version == 3)
	{
		if(ProdID && version && Odu && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && AntennaHeightA && AntennaHeightB && antennaManuf && diameter_a && diameter_b && diameter_A_SD && diameter_B_SD && SD_Sep_A && SD_Sep_B && Losses)
		{
			console.log("Executed Version 3"); 
			$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
			{	
				var Data = $.parseJSON(response);
				//alert(response);
				$.each(Data, function(i, item)
				{						
					$(RXThresholdResult).text(''+item.RXThreshold+' dBm');
					$(Signal_level).text(''+item.Rec_Sig_Level+' dBm');
					$(RSSI).text(''+item.RSSI+' dBm');
					$(Fade_Margin).text(''+item.FadeMargin+' dBm');						
					$(EIRP).text(''+item.EIRP+' dBm');
				});
			});	
			$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );	
		}
		else
		{
			$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );
		}
	}
	if(version == 4)
	{
		if(Antenna_Amount == 1)
		{ 
			if(ProdID && version && Odu && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && Antenna_Amount && AntennaHeightA && AntennaHeightB && antennaManuf && diameter_a && diameter_b && Main_Freq && Div_Freq && Losses)
			{
				$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
				{	
					var Data = $.parseJSON(response);
					$.each(Data, function(i, item)
					{
						$(RXThresholdResult).text(''+item.RXThreshold+' dBm');
						$(Signal_level).text(''+item.Rec_Sig_Level+' dBm');
						$(RSSI).text(''+item.RSSI+' dBm');
						$(Fade_Margin).text(''+item.FadeMargin+' dBm');
						$(EIRP).text(''+item.EIRP+' dBm');
					});
				});	
				$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );	
			}
			else
			{
				$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );
			}
		}
		if(Antenna_Amount == 2)
		{
			if(ProdID && version && Odu && Frequency && Bandwidth && FEC && Temperature && Modulation && RainZone && LatA && LatB && LonA && LonB && Transmitter && Antenna_Amount &&Frequency_FD && Transmit_FD &&  AntennaHeightA && AntennaHeightB && antennaManuf && diameter_a && diameter_b && Antenna_A_FD && Antenna_B_FD && Main_Freq && Div_Freq && Losses)
			{
				console.log("Executed Version 4, 4 antennas"); 
				$.post( "AjaxFunctions.php", { func: 'prev_calc', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: Antenna_A_FD, Extra_diameter_B: Antenna_B_FD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD}, function(response)
				{	
					var Data = $.parseJSON(response);
					$.each(Data, function(i, item)
					{
						//console.log(item.RXThreshold);
						$(RXThresholdResult).text(''+item.RXThreshold+' dBm');
						$(Signal_level).text(''+item.Rec_Sig_Level+' dBm');
						$(RSSI).text(''+item.RSSI+' dBm');
						$(Fade_Margin).text(''+item.FadeMargin+' dBm');
						$(EIRP).text(''+item.EIRP+' dBm');
					});
				});
				$(suc_Button).removeClass( "btn btn-warning disabled" ).addClass( "btn btn-success" );	
			}
			else
			{
				$(suc_Button).removeClass( "btn btn-success" ).addClass( "btn btn-warning disabled" );
			}
		}
	}
}

function calcPath(el)
{
	var site = $(el).parent().parent().attr('class'); 
	var ProdID = ProdID = $('.'+site+'').find('div[name = ProdID]').val();
	var version = $('.'+site+'').find('select[name = Version]').val(); 
	var Frequency = $('.'+site+'').find('select[name=Frequency]').val();
	var BandwidthTMP = $('.'+site+'').find('select[name=Bandwidth]').val();
	var BandSplit = BandwidthTMP.split("|");
	var Bandwidth = BandSplit[0];
	var Standart = BandSplit[1]; 
	var FEC =  $('.'+site+'').find('select[name=FEC]').val();
	var Temperature = $('.'+site+'').find('input[name=Temperature]').val();	
	var Modulation = $('.'+site+'').find('select[name=rModulation]').val();	
	var RainZone = $('.'+site+'').find('select[name=Rainzone]').val();
	var LatA = $('.'+site+'').find('input[name=LatA]').val();
	var LatB = $('.'+site+'').find('input[name=LatB]').val();	
	var LonA = $('.'+site+'').find('input[name=LonA]').val();
	var LonB = $('.'+site+'').find('input[name=LonB]').val();	
	var Transmitter = $('.'+site+'').find('select[name=Transmitter]').val();
	var Antenna_Amount = $('.'+site+'').find('select[name = Antenas_amount]').val();
	var AntennaHeightA = $('.'+site+'').find('input[name=AntennaA]').val();
	var AntennaHeightB = $('.'+site+'').find('input[name=AntennaB]').val();
	var antennaManuf = $('.'+site+'').find('select[name=AntennaManuf]').val();	
	var diameter_a = $('.'+site+'').find('select[name = diameter_A]').val();
	var diameter_b = $('.'+site+'').find('select[name = diameter_B]').val();
	var diameter_a2 = $('.'+site+'').find('span[name = diameter_A2]').val();
	var diameter_b2 = $('.'+site+'').find('span[name = diameter_B2]').val();
	var Main_Freq = $('.'+site+'').find('input[name = Main_Freq]').val();
	var Div_Freq = $('.'+site+'').find('input[name = Div_Freq]').val();
	var Losses = $('.'+site+'').find('input[name=Losses]').val();	
	var diameter_A_SD = $('.'+site+'').find('select[name = diameter_A_SD]').val();
	var diameter_B_SD = $('.'+site+'').find('select[name = diameter_B_SD]').val();
	var Stand_Site_A = $('.'+site+'').find('input[name = Stand_Site_A]').val();
	var Stand_Site_B = $('.'+site+'').find('input[name = Stand_Site_B]').val();
	var Prim_Site_A = $('.'+site+'').find('input[name = Prim_Site_A]').val();
	var Prim_Site_B = $('.'+site+'').find('input[name = Prim_Site_B]').val();	
	var	Antenna_A_FD = $('.'+site+'').find('select[name = diameter_A_FD]').val();
	var	Antenna_B_FD = $('.'+site+'').find('select[name = diameter_B_FD]').val();
	var SD_Sep_A = $('.'+site+'').find('input[name = SD_sep_A]').val();
	var SD_Sep_B = $('.'+site+'').find('input[name = SD_sep_B]').val();	
	var Frequency_FD = $('.'+site+'').find('select[name = Frequency_FD]').val();
	var Transmit_FD = $('.'+site+'').find('select[name = Transmitter_FD]').val();

	var good = "#16E41A";
	var almost = "#E4B416";
	var bad = "#D9674B";
	
	if(version == 1)
	{		
		var M_Path_Vert = $('.'+site+'').find('span[name = M_path_vert]').text('');	
		var M_Path_Hori = $('.'+site+'').find('span[name = M_path_hori]').text('');
		var Rain_vert = $('.'+site+'').find('span[name = rain_vert]').text('');	
		var Rain_hori = $('.'+site+'').find('span[name = rain_hori]').text('');		
		var M_rain_vert = $('.'+site+'').find('span[name = M_rain_vert]').text('');	
		var M_rain_hori = $('.'+site+'').find('span[name = M_rain_hori]').text('');				
		var error_Time_Vert = $('.'+site+'').find('span[name = error_Time_Vert]').text('');	
		var error_Time_Hori = $('.'+site+'').find('span[name = error_Time_Hori]').text('');		

		
		Antenna_Amount = 0;
		diameter_A_SD = 0; 		
		diameter_B_SD = 0; 
		Stand_Site_A = 0; 
		Stand_Site_B = 0; 
		Prim_Site_A = 0;  
		Prim_Site_B = 0; 
		Frequency_FD = 0; 
		Transmit_FD = 0; 
		SD_Sep_A = 0;
		SD_Sep_B = 0; 
		Main_Freq = 0; 
		Div_Freq = 0; 
		
		
		console.log("Calculating for Version 1"); 
			$.post( "AjaxFunctions.php", { func: 'calc_res', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD, RainZone: RainZone, SD_Sep_A: SD_Sep_A, SD_Sep_B: SD_Sep_B, Main_Freq: Main_Freq, Div_Freq: Div_Freq}, function(response)
			{	
				//alert(response);
				var Data = $.parseJSON(response);
				$.each(Data, function(i, item)
				{
					
					$(M_Path_Vert).text(''+item.MultipathVert+'');
					$(M_Path_Hori).text(''+item.MultipathHor+'');
					$(Rain_vert).text(''+item.Rain_Vert+'');
					$(Rain_hori).text(''+item.Rain_Hor+'');
					$(M_rain_vert).text(''+item.Multipath_Rain_Vert+'');
					$(M_rain_hori).text(''+item.Multipath_Rain_Hor+'');
					if(item.Multipath_Rain_Vert >= 99.995)  $(M_rain_vert).css("background-color", good);
					if(item.Multipath_Rain_Vert >= 99.9 && item.Multipath_Rain_Vert < 99.995 )  $(M_rain_vert).css("background-color", almost);
					if(item.Multipath_Rain_Vert < 99.9)  $(M_rain_vert).css("background-color", bad);

					if(item.Multipath_Rain_Hor >= 99.995)  $(M_rain_hori).css("background-color", good);
					if(item.Multipath_Rain_Hor >= 99.9 && item.Multipath_Rain_Hor < 99.995 )  $(M_rain_hori).css("background-color", almost);
					if(item.Multipath_Rain_Hor < 99.9)  $(M_rain_hori).css("background-color", bad);
					
					$(error_Time_Vert).text(''+item.Error_Vert+'');
					$(error_Time_Hori).text(''+item.Error_Hor+'');
				});
			});	
	}
	if(version == 2)
	{		
		var mode = $('.'+site+'').find('select[name = AntennaMode]').val();
		console.log("Version == 2, Mode = "+mode);
		if(mode == 1)
		{
			var M_path_vert_Prim = $('.'+site+'').find('span[name = M_path_vert_Prim]').text('');
			var M_path_hori_Prim = $('.'+site+'').find('span[name = M_path_hori_Prim]').text('');
			var M_path_vert_Stand = $('.'+site+'').find('span[name = M_path_vert_Stand]').text('');
			var M_path_hori_Stand = $('.'+site+'').find('span[name = M_path_hori_Stand]').text('');
			var rain_vert_prim = $('.'+site+'').find('span[name = rain_vert_prim]').text('');
			var rain_hori_prim = $('.'+site+'').find('span[name = rain_hori_prim]').text('');
			var rain_vert_stand = $('.'+site+'').find('span[name = rain_vert_stand]').text('');
			var rain_hori_stand = $('.'+site+'').find('span[name = rain_hori_stand]').text('');
			var M_rain_vert_prim = $('.'+site+'').find('span[name = M_rain_vert_prim]').text('');
			var M_rain_hori_prim = $('.'+site+'').find('span[name = M_rain_hori_prim]').text('');
			var M_rain_vert_stand = $('.'+site+'').find('span[name = M_rain_vert_stand]').text('');
			var M_rain_hori_stand = $('.'+site+'').find('span[name = M_rain_hori_stand]').text('');			
			var error_Time_Vert_prim = $('.'+site+'').find('span[name = error_Time_Vert_prim]').text('');	
			var error_Time_Hori_prim = $('.'+site+'').find('span[name = error_Time_Hori_prim]').text('');	
			var error_Time_Vert_stand = $('.'+site+'').find('span[name = error_Time_Vert_stand]').text('');	
			var error_Time_Hori_stand = $('.'+site+'').find('span[name = error_Time_Hori_stand]').text('');	
			diameter_a2 = 0; 
			diameter_b2 = 0;  
			SD_Sep_A = 0;
			SD_Sep_B = 0; 
			Main_Freq = 0; 
			Div_Freq = 0; 
		
			console.log("Calculating for Version 2 mode "+mode); 
			$.post( "AjaxFunctions.php", { func: 'calc_res', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_a2, Extra_diameter_B: diameter_b2, Antenna_Menu: mode, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD, RainZone: RainZone, SD_Sep_A: SD_Sep_A, SD_Sep_B: SD_Sep_B, Main_Freq: Main_Freq, Div_Freq: Div_Freq}, function(response)
			{	
				console.log(response); 
				var Data = $.parseJSON(response);
				$.each(Data, function(i, item)
				{
					
					$(M_path_vert_Prim).text(''+item.MultipathVert_prim+'');
					$(M_path_hori_Prim).text(''+item.MultipathHor_prim+'');
					$(M_path_vert_Stand).text(''+item.MultipathVert_stand+'');
					$(M_path_hori_Stand).text(''+item.MultipathHor_stand+'');
					$(rain_vert_prim).text(''+item.RainAvailVert_prim+'');
					$(rain_hori_prim).text(''+item.RainAvailHor_prim+'');
					
					$(rain_vert_stand).text(''+item.RainAvailVert_stand+'');
					$(rain_hori_stand).text(''+item.RainAvailHor_stand+'');
					
					$(M_rain_vert_prim).text(''+item.Multipath_Rain_Vert_prim+'');
					$(M_rain_hori_prim).text(''+item.Multipath_Rain_Hor_prim+'');
					$(M_rain_vert_stand).text(''+item.Multipath_Rain_Vert_stand+'');
					$(M_rain_hori_stand).text(''+item.Multipath_Rain_Hor_stand+'');
					
					$(error_Time_Vert_prim).text(''+item.Error_Vert_prim+'');
					$(error_Time_Hori_prim).text(''+item.Error_Hor_prim+'');
					$(error_Time_Vert_stand).text(''+item.Error_Vert_stand+'');
					$(error_Time_Hori_stand).text(''+item.Error_Hor_stand+'');
							
					if(item.Multipath_Rain_Vert_prim >= 99.995)  $(M_rain_vert_prim).css("background-color", good);
					if(item.Multipath_Rain_Vert_prim >= 99.9 && item.Multipath_Rain_Vert_prim < 99.995 )  $(M_rain_vert_prim).css("background-color", almost);
					if(item.Multipath_Rain_Vert_prim < 99.9)  $(M_rain_vert_prim).css("background-color", bad);

					if(item.Multipath_Rain_Hor_prim >= 99.995)  $(M_rain_hori_prim).css("background-color", good);
					if(item.Multipath_Rain_Hor_prim >= 99.9 && item.Multipath_Rain_Hor_prim < 99.995 )  $(M_rain_hori_prim).css("background-color", almost);
					if(item.Multipath_Rain_Hor_prim < 99.9)  $(M_rain_hori_prim).css("background-color", bad);
					
					if(item.Multipath_Rain_Vert_stand >= 99.995)  $(M_rain_vert_stand).css("background-color", good);
					if(item.Multipath_Rain_Vert_stand >= 99.9 && item.Multipath_Rain_Vert_stand < 99.995 )  $(M_rain_vert_stand).css("background-color", almost);
					if(item.Multipath_Rain_Vert_stand < 99.9)  $(M_rain_vert_stand).css("background-color", bad);

					if(item.Multipath_Rain_Hor_stand >= 99.995)  $(M_rain_hori_stand).css("background-color", good);
					if(item.Multipath_Rain_Hor_stand >= 99.9 && item.Multipath_Rain_Hor_stand < 99.995 )  $(M_rain_hori_stand).css("background-color", almost);
					if(item.Multipath_Rain_Hor_stand < 99.9)  $(M_rain_hori_stand).css("background-color", bad);
				});
			});	
		}
		if(mode == 2)
		{	
			var M_Path_Vert = $('.'+site+'').find('span[name = M_path_vert]').text('');	
			var M_Path_Hori = $('.'+site+'').find('span[name = M_path_hori]').text('');
			var Rain_vert = $('.'+site+'').find('span[name = rain_vert]').text('');	
			var Rain_hori = $('.'+site+'').find('span[name = rain_hori]').text('');		
			var M_rain_vert = $('.'+site+'').find('span[name = M_rain_vert]').text('');	
			var M_rain_hori = $('.'+site+'').find('span[name = M_rain_hori]').text('');				
			var error_Time_Vert = $('.'+site+'').find('span[name = error_Time_Vert]').text('');	
			var error_Time_Hori = $('.'+site+'').find('span[name = error_Time_Hori]').text('');
			diameter_a2 = diameter_a; 
			diameter_b2 = diameter_b;  
			Stand_Site_A = 0; 
			Stand_Site_B = 0; 
			Prim_Site_A = 0;  
			Prim_Site_B = 0; 
			Frequency_FD = 0; 
			Transmit_FD = 0; 
			SD_Sep_A = 0;
			SD_Sep_B = 0;
			Main_Freq = 0; 
			Div_Freq = 0; 			
		
			console.log("Calculating for Version 2 mode "+mode); 
			$.post( "AjaxFunctions.php", { func: 'calc_res', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_a2, Extra_diameter_B: diameter_b2, Antenna_Menu: mode, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD, RainZone: RainZone, SD_Sep_A: SD_Sep_A, SD_Sep_B: SD_Sep_B, Main_Freq: Main_Freq, Div_Freq: Div_Freq}, function(response)
			{	
				var Data = $.parseJSON(response);
				$.each(Data, function(i, item)
				{
					
					$(M_Path_Vert).text(''+item.MultipathVert+'');
					$(M_Path_Hori).text(''+item.MultipathHor+'');
					$(Rain_vert).text(''+item.Rain_Vert+'');
					$(Rain_hori).text(''+item.Rain_Hor+'');
					$(M_rain_vert).text(''+item.Multipath_Rain_Vert+'');
					$(M_rain_hori).text(''+item.Multipath_Rain_Hor+'');
					if(item.Multipath_Rain_Vert >= 99.995)  $(M_rain_vert).css("background-color", good);
					if(item.Multipath_Rain_Vert >= 99.9 && item.Multipath_Rain_Vert < 99.995 )  $(M_rain_vert).css("background-color", almost);
					if(item.Multipath_Rain_Vert < 99.9)  $(M_rain_vert).css("background-color", bad);

					if(item.Multipath_Rain_Hor >= 99.995)  $(M_rain_hori).css("background-color", good);
					if(item.Multipath_Rain_Hor >= 99.9 && item.Multipath_Rain_Hor < 99.995 )  $(M_rain_hori).css("background-color", almost);
					if(item.Multipath_Rain_Hor < 99.9)  $(M_rain_hori).css("background-color", bad);
					
					$(error_Time_Vert).text(''+item.Error_Vert+'');
					$(error_Time_Hori).text(''+item.Error_Hor+'');
				});
			});	
		}
	}
	if(version == 3)
	{	
		var M_Path_Vert = $('.'+site+'').find('span[name = M_path_vert]').text('');	
		var M_Path_Hori = $('.'+site+'').find('span[name = M_path_hori]').text('');
		var Rain_vert = $('.'+site+'').find('span[name = rain_vert]').text('');	
		var Rain_hori = $('.'+site+'').find('span[name = rain_hori]').text('');		
		var M_rain_vert = $('.'+site+'').find('span[name = M_rain_vert]').text('');	
		var M_rain_hori = $('.'+site+'').find('span[name = M_rain_hori]').text('');				
		var error_Time_Vert = $('.'+site+'').find('span[name = error_Time_Vert]').text('');	
		var error_Time_Hori = $('.'+site+'').find('span[name = error_Time_Hori]').text('');		

		var SD_Sep_A = $('.'+site+'').find('input[name = SD_sep_A]').val();
		var SD_Sep_B = $('.'+site+'').find('input[name = SD_sep_B]').val();
		
		Antenna_Amount = 0;
		Stand_Site_A = 0; 
		Stand_Site_B = 0; 
		Prim_Site_A = 0;  
		Prim_Site_B = 0; 
		Frequency_FD = 0; 
		Transmit_FD = 0; 
		Main_Freq = 0; 
		Div_Freq = 0; 
		
		console.log("Calculating for Version 1"); 
			$.post( "AjaxFunctions.php", { func: 'calc_res', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_SD, Extra_diameter_B: diameter_B_SD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD, RainZone: RainZone, SD_Sep_A: SD_Sep_A, SD_Sep_B: SD_Sep_B, Main_Freq: Main_Freq, Div_Freq: Div_Freq}, function(response)
			{	
				//alert(response);
				var Data = $.parseJSON(response);
				$.each(Data, function(i, item)
				{
					
					$(M_Path_Vert).text(''+item.MultipathVert+'');
					$(M_Path_Hori).text(''+item.MultipathHor+'');
					$(Rain_vert).text(''+item.Rain_Vert+'');
					$(Rain_hori).text(''+item.Rain_Hor+'');
					$(M_rain_vert).text(''+item.Multipath_Rain_Vert+'');
					$(M_rain_hori).text(''+item.Multipath_Rain_Hor+'');
					if(item.Multipath_Rain_Vert >= 99.995)  $(M_rain_vert).css("background-color", good);
					if(item.Multipath_Rain_Vert >= 99.9 && item.Multipath_Rain_Vert < 99.995 )  $(M_rain_vert).css("background-color", almost);
					if(item.Multipath_Rain_Vert < 99.9)  $(M_rain_vert).css("background-color", bad);

					if(item.Multipath_Rain_Hor >= 99.995)  $(M_rain_hori).css("background-color", good);
					if(item.Multipath_Rain_Hor >= 99.9 && item.Multipath_Rain_Hor < 99.995 )  $(M_rain_hori).css("background-color", almost);
					if(item.Multipath_Rain_Hor < 99.9)  $(M_rain_hori).css("background-color", bad);
					
					$(error_Time_Vert).text(''+item.Error_Vert+'');
					$(error_Time_Hori).text(''+item.Error_Hor+'');
				});
			});	
	
	}
	if(version == 4)
	{	
		var M_Path_Vert = $('.'+site+'').find('span[name = M_path_vert]').text('');	
		var M_Path_Hori = $('.'+site+'').find('span[name = M_path_hori]').text('');
		var Rain_vert = $('.'+site+'').find('span[name = rain_vert]').text('');	
		var Rain_hori = $('.'+site+'').find('span[name = rain_hori]').text('');		
		var M_rain_vert = $('.'+site+'').find('span[name = M_rain_vert]').text('');	
		var M_rain_hori = $('.'+site+'').find('span[name = M_rain_hori]').text('');				
		var error_Time_Vert = $('.'+site+'').find('span[name = error_Time_Vert]').text('');	
		var error_Time_Hori = $('.'+site+'').find('span[name = error_Time_Hori]').text('');
		var diameter_A_FD = $('.'+site+'').find('select[name = diameter_A_FD]').val();
		var diameter_B_FD = $('.'+site+'').find('select[name = diameter_B_FD]').val();		

		var SD_Sep_A = $('.'+site+'').find('input[name = SD_sep_A]').val();
		var SD_Sep_B = $('.'+site+'').find('input[name = SD_sep_B]').val();
		
		
		//var Main_Freq = $('.'+site+'').find('input[name = Main_Freq]').val();
		//var Div_Freq = $('.'+site+'').find('input[name = Div_Freq]').val();
		
		Stand_Site_A = 0; 
		Stand_Site_B = 0; 
		Prim_Site_A = 0;  
		Prim_Site_B = 0; 

		console.log("Calculating for Version 1"); 
			$.post( "AjaxFunctions.php", { func: 'calc_res', ProdID: ProdID, version: version, diameter_a: diameter_a, diameter_b: diameter_b, AntennaHeightA: AntennaHeightA, AntennaHeightB: AntennaHeightB, Transmitter: Transmitter, Extra_diameter_A: diameter_A_FD, Extra_diameter_B: diameter_B_FD, Antenna_Menu: Antenna_Amount, LatA: LatA, LatB: LatB, LonA: LonA, LonB: LonB, Frequency: Frequency, Losses: Losses, Stand_Site_A: Stand_Site_A, Stand_Site_B: Stand_Site_B, Prim_Site_A: Prim_Site_A, Prim_Site_B: Prim_Site_B, Bandwidth: Bandwidth, Standart: Standart, FEC: FEC, Modulation: Modulation, Temperature: Temperature, Manufacturer: antennaManuf, Frequency_FD: Frequency_FD, Transmit_FD: Transmit_FD, RainZone: RainZone, SD_Sep_A: SD_Sep_A, SD_Sep_B: SD_Sep_B, Main_Freq: Main_Freq, Div_Freq: Div_Freq}, function(response)
			{	
				//alert(response);
				var Data = $.parseJSON(response);
				$.each(Data, function(i, item)
				{
					
					$(M_Path_Vert).text(''+item.MultipathVert+'');
					$(M_Path_Hori).text(''+item.MultipathHor+'');
					$(Rain_vert).text(''+item.Rain_Vert+'');
					$(Rain_hori).text(''+item.Rain_Hor+'');
					$(M_rain_vert).text(''+item.Multipath_Rain_Vert+'');
					$(M_rain_hori).text(''+item.Multipath_Rain_Hor+'');
					if(item.Multipath_Rain_Vert >= 99.995)  $(M_rain_vert).css("background-color", good);
					if(item.Multipath_Rain_Vert >= 99.9 && item.Multipath_Rain_Vert < 99.995 )  $(M_rain_vert).css("background-color", almost);
					if(item.Multipath_Rain_Vert < 99.9)  $(M_rain_vert).css("background-color", bad);

					if(item.Multipath_Rain_Hor >= 99.995)  $(M_rain_hori).css("background-color", good);
					if(item.Multipath_Rain_Hor >= 99.9 && item.Multipath_Rain_Hor < 99.995 )  $(M_rain_hori).css("background-color", almost);
					if(item.Multipath_Rain_Hor < 99.9)  $(M_rain_hori).css("background-color", bad);
					
					$(error_Time_Vert).text(''+item.Error_Vert+'');
					$(error_Time_Hori).text(''+item.Error_Hor+'');
				});
			});	
	
	}
	
}