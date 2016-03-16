<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta name="description" content="Register">
	<link href="../Style/css.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="ajax.js"></script>
	
	<title>Lapa</title>
	</head>
	<body>
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
	<div id = "content">
	<script> 
		$(document).ready(function()
		{
			var DivID = 1; 
			$("#AddLink").click(function(){
				$('<h4>Calculate link no '+DivID+'</h4>').appendTo('#content');
				$('<div class = "'+DivID+'"></div>').appendTo('#content');
				$('.'+DivID+'').load('HTMLframe.html');
				DivID = DivID + 1;
			});
			
			$(".Remove").click(function(){
				var id = $(".RemoveID").val(); 
				console.log(id);
				$( '.'+id+'' ).empty();
				
			});
		});
	</script>
	</div> 
	<br>
	 <button id = "AddLink">Add Link</button> 
	 <form>
		<input type="text" class = "RemoveID">
		<button class = "Remove">Remove Link</button> 
	 </form>
	</body>
	<script src="js/bootstrap.min.js"></script>
</html>
