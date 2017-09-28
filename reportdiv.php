<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<html>
<head>
<title>Bubble Keeper</title>
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
.foodlist{
	-webkit-column-count: 2; /* Chrome, Safari, Opera */
    -moz-column-count: 2; /* Firefox */
    column-count: 2;
}
.allfoodlist{
	-webkit-column-count: 4; /* Chrome, Safari, Opera */
    -moz-column-count: 4; /* Firefox */
    column-count: 4;
}
.allfoodlist p,.foodlist p{
	padding: .02em;
    margin: .02em;
}
</style>
</head>
<body>
<?php
session_start();

if(sha1("H4y3u3".$_COOKIE["user"]."Wolverine") == $_COOKIE["auth"]){
	$_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['username'] = $_COOKIE["user"];
}
if(isset($_SESSION["valid"]) && !empty($_SESSION["valid"]) && $_SESSION["valid"] === TRUE){
	
}
else{
	echo "<script> window.location.assign('/auth'); </script>";
}
?>
<h1>Bubble Report</h1>
<p>Start Date: <input type='text' class='datepicker' id='start' value='<?php if(isset($_GET["start"])){echo $_GET["start"];}?>'/></p>
<script>
$(function(){
	$(".datepicker").datepicker({dateFormat:"yy-mm-dd"});
	$("#start").change(function(){
		window.location = window.location.origin + window.location.pathname +"?start="+$("#start").val();
	});
});
</script>
<?php
	if(!isset($_GET["start"])){
		echo "<p>Include a parameter start=yyyy-mm-dd</p>";
		$_GET["start"]="2017-01-01";
	}
	if(!isset($_GET["end"])){
		//echo "<p>Include a parameter end=yyyy-mm-dd</p>";
	}
	date_default_timezone_set('America/Chicago');
	$config = parse_ini_file('/var/www/bubblesconfig.ini');
	
	$conn = new mysqli($config["mysqlhost"],$config["mysqluser"],$config["mysqlpassword"],$config["mysqldbname"]);
?>


<?php
	
	$sql = "select * from bubblesuser where email = '".$_SESSION['username']."'";
	$rep = $conn->query($sql);
	$bubbles = array();
	while($use = $rep->fetch_assoc()){
		$bubbles[] = $use;
	}
	
	$sql = "select distinct date from bubblesused where email = '".$_SESSION['username']."' and date >= '".$_GET["start"]."' order by date";
	$rep = $conn->query($sql);
	
	$sql = "select date, bubble, count(bubble) as ctbub from bubblesused where email = ? and date = ? and bubble = ? group by date,bubble";
	$prep = $conn->prepare($sql);
	$ct = 0;
	$perrow = 7;
	echo "<div class='tablegroup'>";
	while($dt = $rep->fetch_assoc()){
		if($ct != 0 && $ct % $perrow == 0){
			echo "</div>";
			echo "<div class='foodlist'>";
			
			echo "</div>";
			echo "<div class='tablegroup'>";
		}
		echo "<table class='smalltable'><tr>";
		if($ct % $perrow == 0){
			echo "<th class='firstcol'></th>";
		}
		echo "<th id='tabledate-".$dt["date"]."'>".$dt["date"]."</th></tr>";
		foreach($bubbles as $b){
			$prep->bind_param("sss",$a = $_SESSION['username'],$dt["date"],$b["bubble"]);
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
</div>
<div class='foodlist'></div>

<script>
	var foodlist = [
<?php
	$sql = "select date,eat, bubble,count(eat) as bubbles,meal from bubblesused where email = '".$_SESSION['username']."'";
	if(isset($_GET["start"])){
		$sql = $sql." and date >= '".$_GET["start"]."'";
	}
	$sql = $sql. " group by eat,bubble,meal,date order by date,meal,bubble";
	$r = $conn->query($sql);
	
	while ($j = $r->fetch_assoc()){
		echo "{date:'".$j["date"]."',eat:'". $j["eat"]."',ct:".$j["bubbles"].",bubble:'".$j["bubble"]."',meal:'".$j["meal"]."'},\n";
	}
	
?>
	];
	
	console.log(foodlist);
	$(function(){
		var lastdate,str;
		for(i=0;i<foodlist.length;i++){
			if(lastdate == foodlist[i].date){
				str = "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+foodlist[i].eat+" ("+foodlist[i].ct+" "+foodlist[i].bubble+")"+" at "+foodlist[i].meal+"</p>";
			}
			else{
				str = "<p>"+foodlist[i].date+" "+foodlist[i].eat+" ("+foodlist[i].ct+" "+foodlist[i].bubble+")"+" at "+foodlist[i].meal+"</p>";
			}
			$("#tabledate-"+foodlist[i].date).closest(".tablegroup").next().append(str);
			lastdate = foodlist[i].date;
		}
		
	});
	
</script>
<h3>Food List</h3>
<div class='allfoodlist'>

<?php
	$sql = "select distinct eat from bubblesused where email = '".$_SESSION['username']."'";
	if(isset($_GET["start"])){
		$sql = $sql." and date >= '".$_GET["start"]."'";
	}
	$sql = $sql. " order by eat";
	$r = $conn->query($sql);
	
	while ($j = $r->fetch_assoc()){
		echo "<p>".$j["eat"]."</p>\n";
	}
?>
</div>
</body>
</html>
