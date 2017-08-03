$(function(){
	
	$(".bubbles").click(addbubble);
	$(".exbubble").click(addex);
	$("#date input").datepicker({
		dateFormat:"yy-mm-dd",
	});
	
	$("#date input").change(reload);
	for(i=0;i<used.length;i++){
		var f = $("#"+used[i].bubble+" span.bubbles:not(.full)");
		$(f).first().addClass("full");
		$(f).first().attr('eat',used[i].eat);
		if(used[i].bubble == "milk"){
			$("#"+used[i].meal).append("<span class='food'>"+used[i].eat+"</span>");
		}
		else if($("#"+used[i].bubble).attr('lgroup').length > 0){
			$("#"+used[i].meal+" ."+$("#"+used[i].bubble).attr('lgroup').toLowerCase()+" .foods").append("<span class='food'>"+used[i].eat+"</span>");
			if(used[i].meal){
				$("#meal select").val(used[i].meal);
			}
		}
		 
	}
	
	for(i=0;i<ex.length;i++){
		$("#"+ex[i]+" span").addClass("exfull");
	}
});

function addbubble(e){
	var event = event || e;
	var url = "";
	var bubble = $(event.target).closest("tr").attr('id');
	if($(event.target).hasClass("full")){
		var c = confirm("Remove bubble "+$(event.target).attr('eat')+"?");
		console.log(c);
		if(!c){
			return false;
		}
		else{
			var eat = $(event.target).attr('eat');
			url = "/bubble/addlist.php?mode=delete";
		}
	}
	else{
		var eat = prompt("What did you eat?");
		url = "/bubble/addlist.php?mode=add&meal="+$("#meal select").val();
	}
	url += "&bubble="+bubble+"&date="+$("#date input").val()+"&email="+$("#name select").val()+"&eat="+eat;
	
	$.ajax({
		url:url,
		success:function(res){
			console.log(res);
			if($(event.target).hasClass("full")){
				$(event.target).removeClass("full");
				$(event.target).removeAttr('eat');
				reload(event);
				
			}
			else{
				$(event.target).addClass("full");
				$(event.target).attr('eat',eat);
				$("#"+$("#meal select").val()).append("<p>"+eat+"</p>");
			}
			
		},
		error:function(err){
			alert('error');
			console.log(err);
		}
	})
	
}

function addex(e){
	var event = event || e;
	var url = "";
	var bubble = $(event.target).closest("td").attr('id');
	if($(event.target).hasClass("exfull")){
		var c = confirm("Remove bubble?");
		console.log(c);
		if(!c){
			return false;
		}
		else{
			var eat = $(event.target).attr('eat');
			url = "/bubble/addlist.php?mode=deleteex";
		}
	}
	else{
		url = "/bubble/addlist.php?mode=addex";
	}
	url += "&type="+bubble+"&date="+$("#date input").val()+"&email="+$("#name select").val();
	
	$.ajax({
		url:url,
		success:function(res){
			console.log(res);
			if($(event.target).hasClass("exfull")){
				$(event.target).removeClass("exfull");
				reload(event);
				
			}
			else{
				$(event.target).addClass("exfull");
			}
			
		},
		error:function(err){
			alert('error');
			console.log(err);
		}
	})
	
}

function reload(e){
	var event = event || e;
	if($(event.target).attr('type') == "text"){
		var newloc = window.location.origin + window.location.pathname+"?date="+$("#date input").val();
	}
	else{
		var newloc = window.location;
	}
	window.location = newloc;
}