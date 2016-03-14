$( document ).ready(function(){
//Elementu slēpšana.
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
			case '1':
				$('.odu').hide();
				$('.Antenna_Coupler').hide();
				$('.field').hide();
				$('.AntennasAm').hide();
				$('.ExtraFreq').hide();
				$('.prod4').hide();	
				$('.prod3').hide();
				$('.ExtraAntennas').hide();

				$('.Coupler').hide();
				$('.HSB_hide').hide(); 
				$('.HSB_Show').hide(); 					
				break;
			case '4':
				$('.field').show();
				$('.AntennasAm').show();
				$('.ExtraFreq').show();
				$('.odu').show();
				$('.prod4').show();	
				$('.prod3').hide();	
				$('.ExtraAntennas').hide(); 
				$('.Antenna_Coupler').hide();
				$('.Coupler').hide();
				$('.HSB_hide').hide(); 
				$('.HSB_Show').hide(); 					
				break;
			case '3':
				$('.field').show();
				$('.odu').show();
				$('.prod3').show();
				$('.ExtraAntennas').show(); 
				$('.Antenna_Coupler').hide();
				$('.AntennasAm').hide();
				$('.ExtraFreq').hide();
				$('.prod4').hide();	
				$('.Coupler').hide();
				$('.HSB_hide').hide(); 
				$('.HSB_Show').hide(); 	
				break;
			case '2':
				$('.odu').show();
				$('.Antenna_Coupler').show();
				$('.field').hide();
				$('.AntennasAm').hide();
				$('.ExtraFreq').hide();
				$('.prod4').hide();	
				$('.prod3').hide();
				$('.ExtraAntennas').hide(); 
				$('.Coupler').hide();
				$('.HSB_hide').hide(); 
				$('.HSB_Show').hide(); 	
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
	
	
//
//Dinamiski dropdown listi
	$('.Version').change(function() 
	{
		var select = $('.Products').empty();
		var version = $('.Version').val();	
		
		$.post( "AjaxFunctions.php", { Version: version}, function(response){	
			//alert(response);
			var Data = $.parseJSON(response);
			console.log(Data);
			$.each(Data, function(i, item) {
			$('<option value="' + item.id + '">' + item.name + '</option>').appendTo(select);
			});
		});
		
	});
	


	/*var select = $('.rModulation').empty();
	$.post( "refractivity.php", { functionName: Print_Product_ID}, function(response){	
			alert(response);
			});
	});*/

	

});