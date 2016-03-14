<?php
include 'connection.php';
	
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
	{
			$object[0] = array('id' => 0, 'name' => "Please choose product.");
			$object[1] = array('id' => 1, 'name' => "Integra");
			$object[2] = array('id' => 2, 'name' => "Integra W");
			$object[3] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[4] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[5] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[6] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 2)
	{
			$object[0] = array('id' => 0, 'name' => "Please choose product.");
			$object[1] = array('id' => 3, 'name' => "CFIP Lumina FODU");
			$object[2] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[3] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[4] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	if($version == 3 || $version == 4)
	{
			$object[0] = array('id' => 0, 'name' => "Please choose product.");
			$object[1] = array('id' => 4, 'name' => "CFIP Phoenix IDU");
			$object[2] = array('id' => 5, 'name' => "CFIP Phoenix M IDU");
			$object[3] = array('id' => 6, 'name' => "CFIP Phoenix C IDU");
	}
	echo json_encode($object);
}

?>