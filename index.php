<html>
<head>
<title>Bubble Keeper</title>
<meta name='viewport' content='width=device-width; maximum-scale=1; minimum-scale=1;' />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src='index.js'></script>
<link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel='stylesheet' type='text/css' href='index.css'>
</head>
<body>
<script>
	var used = [
<?php
	date_default_timezone_set('America/Chicago');
	$conn = new mysqli('localhost','home_user','hdaslkjdsflkhasdd&*8768','meals');
	$res = $conn->query("select * from bubblesuser join bubbles on bubbles.short = bubblesuser.bubble where email = 'posisme@gmail.com'");
	
	if($_GET && $_GET["date"]){
		$used = $conn->query("select * from bubblesused where email = 'posisme@gmail.com' and date = '".$_GET["date"]."' order by usedkey");
	}
	else{
		$used = $conn->query("select * from bubblesused where email = 'posisme@gmail.com' and date = '".date("Y-m-d")."' order by usedkey");
	}
	while($use = $used->fetch_assoc()){
		echo "{bubble:'".$use["bubble"]."',eat:'".$use["eat"]."',meal:'".$use["meal"]."'},";
	}
?>
	]
</script>
<div id='top'>
<div>Name:<span id='name'><select><option value='posisme@gmail.com'>Randy Pospisil</option></select></span></div>
<div>Date:<span id='date'>
<?php
	if($_GET && $_GET["date"]){
		echo "<input type='text' value='".$_GET["date"]."' />";
	}
	else{
		echo "<input type='text' value='".date("Y-m-d")."' />";
	}
?>

</span></div>
<div>Meal:<span id='meal'><select>
<option value='breakfast'>Breakfast</option>
<option value='m1'>Milk 1</option>
<option value='lunch'>Lunch</option>
<option value='m2'>Milk 2</option>
<option value='supper'>Supper</option>
<option value='m3'>Milk 3</option>
</select>
</span></div></div>
<h1>Guide to Healthy Eating and Exercise</h1>
<h2><span id='totalcalories'></span> Calories</h2>
<table id='bubbles'>
<?php
	while($row = $res->fetch_assoc()){
		echo "<tr lgroup='".$row["groupid"]."' id='".$row["short"]."'><td>".$row["longdesc"];
		if($row["groupid"] != ""){
			echo " (".$row["groupid"].")";
		}
		echo "</td><td>";
		for($i=0;$i<$row["numb"];$i++){
			echo "<span class='bubbles'></span>";
		}
		echo "</td></tr>";
	}
	
?>

</table>
<table id='exercise'>
<tr><th>Exercise</th><th>Aerobic</th><th>Strengthening</th></tr>
<tr><th>Minutes</th><td id='aerobic'></td><td id='strength'></td></tr>
</table>
<table id='breakfast'>
<tr class='c'><td class='bubdet'><span class='numb'></span> C:</td><td class='foods'></td></tr>
<tr class='p'><td class='bubdet'><span class='numb'></span> P:</td><td class='foods'></td></tr>
<tr class='f'><td class='bubdet'><span class='numb'></span> F:</td><td class='foods'></td></tr>
<tr class='v'><td class='bubdet'><span class='numb'></span> V:</td><td class='foods'></td></tr>
</table>
<p>Snack 1 M: <span id='m1'></span></p>
<table id='lunch'>
<tr class='c'><td class='bubdet'><span class='numb'></span> C:</td><td class='foods'></td></tr>
<tr class='p'><td class='bubdet'><span class='numb'></span> P:</td><td class='foods'></td></tr>
<tr class='f'><td class='bubdet'><span class='numb'></span> F:</td><td class='foods'></td></tr>

<tr class='v'><td class='bubdet'><span class='numb'></span> V:</td><td class='foods'></td></tr>
</table>
<p>Snack 2 M: <span id='m2'></span></p>
<table id='supper'>
<tr class='c'><td class='bubdet'><span class='numb'></span> C:</td><td class='foods'></td></tr>
<tr class='p'><td class='bubdet'><span class='numb'></span> P:</td><td class='foods'></td></tr>
<tr class='f'><td class='bubdet'><span class='numb'></span> F:</td><td class='foods'></td></tr>
<tr class='v'><td class='bubdet'><span class='numb'></span> V:</td><td class='foods'></td></tr>
</table>
<p>Snack 3 M: <span id='m3'></span></p>
</body>
</html>