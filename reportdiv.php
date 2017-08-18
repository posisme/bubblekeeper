<html>
<head>
<title>Bubble Keeper</title>
<meta name='viewport' content='width=device-width; maximum-scale=1; minimum-scale=1;' />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
<style>
.tablegroup{
	overflow:auto;
	padding:5px;
}
.smalltable{
	float:left;
	height:300px;
	border-collapse:collapse;
	padding:0px;
}
.smalltable .firstcol{
	width:50%;
}
.smalltable tr{
	height:2em;
}
.smalltable th,.smalltable td{
	border:1px solid black;
	text-align:center;
}

.low{
	background-color:black;
	color:white;
}
.high{
	background-color:red;
	color:white;
}
.right{
	background-color:white;
	color:black;
}
.none{
	background-color:blue;
	color:white;
}
</style>
</head>
<body>
<h1>Bubble Report</h1>
<?php
	if(!isset($_GET["start"])){
		echo "<p>Include a parameter start=yyyy-mm-dd</p>";
	}
	if(!isset($_GET["end"])){
		echo "<p>Include a parameter end=yyyy-mm-dd</p>";
	}
	date_default_timezone_set('America/Chicago');
	$conn = new mysqli('localhost','home_user','hdaslkjdsflkhasdd&*8768','meals');
?>
<table>
<tr><td class='red'>less than 50%</td><td class='blue'>greater than 50%</td><td class='black'>over 100%</td></tr>
</table>

<?php
	
	$sql = "select * from bubblesuser where email = '".$_SERVER['REMOTE_USER']."'";
	$rep = $conn->query($sql);
	$bubbles = array();
	while($use = $rep->fetch_assoc()){
		$bubbles[] = $use;
	}
	$sql = "select distinct date from bubblesused where email = '".$_SERVER['REMOTE_USER']."' order by date";
	$rep = $conn->query($sql);
	
	$sql = "select date, bubble, count(bubble) as ctbub from bubblesused where email = ? and date = ? and bubble = ? group by date,bubble";
	$prep = $conn->prepare($sql);
	$ct = 0;
	$perrow = 6;
	echo "<div class='tablegroup'>";
	while($dt = $rep->fetch_assoc()){
		if($ct % $perrow == 0){
			echo "</div><div class='tablegroup'>";
		}
		echo "<table class='smalltable'><tr>";
		if($ct % $perrow == 0){
			echo "<th class='firstcol'></th>";
		}
		echo "<th>".$dt["date"]."</th></tr>";
		foreach($bubbles as $b){
			$prep->bind_param("sss",$a = $_SERVER['REMOTE_USER'],$dt["date"],$b["bubble"]);
			$prep->execute();
			echo "<tr>";
			if($ct % $perrow == 0){
				echo "<td class='firstcol'>".$b["bubble"]."</td>";
			}
			$prep->bind_result($d,$bub,$ctbub);
			while($prep->fetch()){
				$calc = round(($ctbub/$b["numb"])*100);
				if($calc > 0 && $calc < 50){
					$rl = "low";
				}
				else if($calc >=50 && $calc <=100){
					$rl = "right";
				}
				else if($calc > 100){
					$rl = "high";
				}
				else{
					$rl = "none";
				}
				echo "<td class='".$rl."'>".$calc."%</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		
		$ct++;
	}
	
?>
	
</body>
</html>
