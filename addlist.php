<?php

date_default_timezone_set('America/Chicago');

$conn = new mysqli('localhost','home_user','hdaslkjdsflkhasdd&*8768','meals');
$url = "";
if($_GET["mode"] == "delete"){
	$url = "delete from bubblesused where email = ? and bubble = ? and date = ? and eat = ? order by ? desc limit 1";
	$res = $conn->prepare($url);
	$res->bind_param("sssss",$_GET["email"],$_GET["bubble"],$_GET["date"],$_GET["eat"],$_GET["meal"]);
}
else if($_GET["mode"] == "add"){
	$url = "insert into bubblesused (email,bubble,date,eat,meal) values(?,?,?,?,?)";
	$res = $conn->prepare($url);
	$res->bind_param("sssss",$_GET["email"],$_GET["bubble"],$_GET["date"],$_GET["eat"],$_GET["meal"]);
}
else if($_GET["mode"] == "deleteex"){
	$url = "delete from exercize where email = ? and type = ? and date = ? limit 1";
	$res = $conn->prepare($url);
	$res->bind_param("sss",$_GET["email"],$_GET["type"],$_GET["date"]);
}
else if($_GET["mode"] == "addex"){
	$url = "insert into exercize (email,type,date) values(?,?,?)";
	$res = $conn->prepare($url);
	$res->bind_param("sss",$_GET["email"],$_GET["type"],$_GET["date"]);
}

$ret = $res->execute();
$res->close();
$conn->close();
echo json_encode($_GET);
?>