<html>
<head><title>Bubble Keeper Config</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<h1>Config</h1>

<h2>Bubbles User</h2>
<p><textarea id='bubblesuser' style='width:100%;height:20em;'>
<?php
date_default_timezone_set('America/Chicago');
$config = parse_ini_file('/var/www/bubblesconfig.ini');
	
$conn = new mysqli($config["mysqlhost"],$config["mysqluser"],$config["mysqlpassword"],$config["mysqldbname"]);$rep = $conn->query("select * from bubblesuser");
while($r = $rep->fetch_array(MYSQLI_NUM)){
	foreach($r as $a){
		echo $a."\t";
	}
	echo "\n";
	
}
?>
</textarea>
</p>
<button id='saveconfig' onclick='setconfig()'>Set</button>
<script>

function setconfig(){
	$.ajax({
		url:"addlist.php?mode=config",
		type:"POST",
		data:{data:$("#bubblesuser").val()},
		success:function(r){
			console.log(r);
		},
		error:function(err){
			alert("error");
			console.log(err);
		}
	})
}

</script>
</body>
</html>