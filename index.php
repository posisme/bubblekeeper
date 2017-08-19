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
	$res = $conn->query("select * from bubblesuser join bubbles on bubbles.short = bubblesuser.bubble where email = '".$_SERVER['REMOTE_USER']."'");
	
	if($_GET && $_GET["date"]){
		$used = $conn->query("select * from bubblesused where email = '".$_SERVER['REMOTE_USER']."' and date = '".$_GET["date"]."' order by usedkey");
		$ex = $conn->query("select * from exercize where email = '".$_SERVER['REMOTE_USER']."' and date = '".$_GET["date"]."' order by exkey");
	}
	else{
		$used = $conn->query("select * from bubblesused where email = '".$_SERVER['REMOTE_USER']."' and date = '".date("Y-m-d")."' order by usedkey");
		$ex = $conn->query("select * from exercize where email = '".$_SERVER['REMOTE_USER']."' and date = '".date("Y-m-d")."' order by exkey");
	}
	while($use = $used->fetch_assoc()){
		echo "{bubble:'".$use["bubble"]."',eat:'".$use["eat"]."',meal:'".$use["meal"]."'},";
	}
	
?>
	];
	var ex = [
<?php
	while($exe = $ex->fetch_assoc()){
		echo "'".$exe["type"]."',";
	}
?>
	];
</script>
<div id='top'>
<div>Name:<span id='name'><select><option value='posisme@gmail.com'>Randy Pospisil</option><option value='kim@thrown-iowa.com'>Kim Pospisil</option></select></span></div>
<script>
	$(function(){
<?php
	echo "var thisval = '".$_SERVER['REMOTE_USER']."';\n";
?>
		$("#name select").val(thisval);
	});
</script>
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
	$groupids = array();
	while($row = $res->fetch_assoc()){
		$groupids[] = $row["short"];
		echo "<tr lgroup='".$row["groupid"]."' id='".$row["short"]."'><td>".$row["longdesc"];
		if($row["groupid"] != ""){
			echo " (".$row["groupid"].")";
		}
		echo "</td><td>";
		for($i=0;$i<$row["numb"];$i++){
			echo "<span class='bubbles'></span>";
		}
		echo "<span class='overbubble' style='display:none;'></span>";
		echo "</td></tr>";
	}
	
?>

</table>
<table id='exercise'>
<tr><th>Exercise</th><th>Aerobic</th><th>Strengthening</th></tr>
<tr><th>30 Minutes</th><td id='aerobic'><span class='exbubble'></span></td><td id='strength'><span class='exbubble'></span></td></tr>
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
<hr>
<h2>Saved Bubble Group</h2>
<p><select id='savedbubble'>
	<option value=''>Select...</option>
<?php
	$sb = $conn->query("select group_concat(bubble) as b,group_concat(eat) as e, groupname from savedbubble where email = '".$_SERVER['REMOTE_USER']."' group by groupname");
	while($s = $sb->fetch_assoc()){
		echo "<option value='".$s["groupname"]."'>".$s["groupname"]."</option>\n";
	}
?>
</select> 
<button id='savedbubblesend'>Go</button>  
<button id='newsaved'>New</button>
</p>

<div class='popup'>
<div id='killpopup' onclick='killpopup()'>X</div>
<p>Group Name (no spaces) <input type='text' id='groupnamepopup' /></p>
<table>
<tr><th>Food</th><th>Bubble</th><th><span onclick='addpopuprow()'>+</span></th></tr>
<tr class='popuptrow'><td><input type='text' class='popupeat' /></td><td><select class='popupbub'>
<option value=''>Select...</option>
<?php
	foreach($groupids as $g){
		echo "<option value='".$g."'>".$g."</option>\n";
	}
?>
</select>
</td><td><span onclick='delpopuprow(this)'>X</span></td></tr>
</table>
<p><button onclick='savepopup()'>Save</button></p>
</div>
<hr>
<p><a href='/bubble/reportdiv.php'>Report</a></p>
</body>
</html>