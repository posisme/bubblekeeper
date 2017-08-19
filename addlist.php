<?php

date_default_timezone_set('America/Chicago');

$conn = new mysqli('localhost','home_user','hdaslkjdsflkhasdd&*8768','meals');
$url = "";
if($_GET["mode"] == "savedbubble"){
	
	$r = $conn->query("select * from savedbubble where email = '".$_GET["email"]."' and groupname = '".$_GET["sk"]."'");
	while($j = $r->fetch_assoc()){
		$res = add($conn,$_GET["email"],$j["bubble"],$_GET["date"],$j["eat"],$_GET["meal"]);
		$ret = $res->execute();
	}
	$res->close();
	$conn->close();
}
else if($_GET["mode"] == "addgroup"){
	foreach($_POST["p"] as $p){
		$res = addtosave($conn,$_POST["gn"],$p["eat"],$p["bubble"],$_GET["email"]);
		$res->execute();
	}
	$res->close();
	$conn->close();
}
else if($_GET["mode"] == "config"){
	$url = "delete from bubblesuser";
	$conn->query($url);
	$res = $conn->prepare("insert into bubblesuser values (?,?,?,?)");
		
	foreach($_POST as $j){
		$v = preg_split("/\n/",$j);
		
		foreach($v as $k){
			$l = preg_split("/\t/",$k);
			if(count($l) == 5){
				$res->bind_param("issi",$l[0],$l[1],$l[2],$l[3]);
				$res->execute();
			}
		}
	}
	$res->close();
	$conn->close();
}
else {
	if($_GET["mode"] == "delete"){
		$url = "delete from bubblesused where email = ? and bubble = ? and date = ? and eat = ? order by ? desc limit 1";
		$res = $conn->prepare($url);
		$res->bind_param("sssss",$_GET["email"],$_GET["bubble"],$_GET["date"],$_GET["eat"],$_GET["meal"]);
		
	}
	else if($_GET["mode"] == "add"){
		// $url = "insert into bubblesused (email,bubble,date,eat,meal) values(?,?,?,?,?)";
		// $res = $conn->prepare($url);
		// $res->bind_param("sssss",$_GET["email"],$_GET["bubble"],$_GET["date"],$_GET["eat"],$_GET["meal"]);
		$res = add($conn,$_GET["email"],$_GET["bubble"],$_GET["date"],$_GET["eat"],$_GET["meal"]);
		
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
}


function add($conn,$email,$bubble,$date,$eat,$meal){
	$url = "insert into bubblesused (email,bubble,date,eat,meal) values(?,?,?,?,?)";
	$res = $conn->prepare($url);
	$res->bind_param("sssss",$email,$bubble,$date,$eat,$meal);
	return $res;
}
function addtosave($conn,$gn,$eat,$bubble,$email){
	$url = "insert into savedbubble (email,bubble,eat,groupname) values(?,?,?,?)";
	$res = $conn->prepare($url);
	$res->bind_param("ssss",$email,$bubble,$eat,$gn);
	return $res;
}
?>