$( document ).ready(function(){
	$('.field').hide();
	$('.odu').hide();
	$('.AntennasAm').hide();
	$('.ExtraFreq').hide();
	$('.field3').hide();
	$('.prod3').hide();
	$('.prod4').hide();
	$('.ExtraAntennas').hide();
	$('.Antenna_Coupler').hide();
	$('.Coupler').hide();
	$('#Version').change(function()
	{
		var version = $('#Version').val();
		$('.AntennasAm').hide();
		switch(version)
		{
			case '4':
				$('.field').show();
				$('.AntennasAm').show();
				$('.ExtraFreq').show();
				$('.odu').show();
				$('.prod4').show();				
				break;
			case '3':
				$('.field').show();
				$('.odu').show();
				$('.prod3').show();
				$('.ExtraAntennas').show(); 
				break;
			case '2':
				$('.odu').show();
				$('.Antenna_Coupler').show();
			break;
		}
	});
	$('#AntennasAm').change(function()
	{
		var antennas = $('#AntennasAm').val();
		switch(antennas)
		{
			case '1':
				$('.field3').show();
				$('.ExtraAntennas').show(); 
				break;
		}
	});
	$('#Antenna_Coupler').change(function()
	{
		var value = $('#Antenna_Coupler').val();
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