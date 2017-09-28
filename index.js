$(document).on('pageinit',function(){
		
});
$(function(){
	
	$(".bubbles").click(addbubble);
	$(".exbubble").click(addex);
	$(".overbubble").click(overbubble);
	$("#savedbubblesend").click(savedbubble);
	$("#newsaved").click(newsavedpopup);
$("body").on("swipeleft",swipeleft);
	$("body").on("swiperight",swiperight);
	
	$("#date input").datepicker({
		dateFormat:"yy-mm-dd",
	});
	
	$("#date input").change(reload);
	for(i=0;i<used.length;i++){
		if($("#"+used[i].bubble+" span.bubbles:not(.full)").length == 0){
			$("#"+used[i].bubble+" td:last-child").append("<span class='bubbles fulloverbubble'></span>");
			$("#"+used[i].bubble+" span.overbubble").show();
		}
		else if($("#"+used[i].bubble+" span.bubbles:not(.full)").length == 1){
			//$("#"+used[i].bubble+" td:last-child").append("<span class='bubbles fulloverbubble'></span>");
			$("#"+used[i].bubble+" span.overbubble").show();
		}
		var f = $("#"+used[i].bubble+" span.bubbles:not(.full)");
		$(f).first().addClass("full");
		$(f).first().attr('eat',used[i].eat);
		//$(f).first().hover(showeat);
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
	$(".food").each(function(){
		if($(this).text() == $(this).next().text()){
			$(this).remove();
		}
	});
	
	for(i=0;i<ex.length;i++){
		$("#"+ex[i]+" span").addClass("exfull");
	}
});

function swipeleft(){
	var newdate = new Date($("#date input").val()+" 12:00:00");
	newdate.setDate(newdate.getDate()+1);
	newdate = $.datepicker.formatDate("yy-mm-dd",newdate);
	var newloc = window.location.origin + window.location.pathname+"?date="+newdate;
	window.location = newloc;
}

function swiperight(){
	var newdate = new Date($("#date input").val()+" 12:00:00");
	newdate.setDate(newdate.getDate()-1);
	newdate = $.datepicker.formatDate("yy-mm-dd",newdate);
	var newloc = window.location.origin + window.location.pathname+"?date="+newdate;
	window.location = newloc;
}
function newsavedpopup(){
	$(".popup").show();
}
function killpopup(){
	
	$(".popup").hide();
}
function addpopuprow(){
	var cl = $(".popup table tr:last-child").clone();
	$(".popup table").append(cl);
}
function delpopuprow(row){
	if(!$(row).closest('tr').is(":nth-child(2)")){
		$(row).closest('tr').remove();
	}
	
}

function savepopup(){
	var data = {};
	data.gn = $("#groupnamepopup").val().replace(/[^a-z0-9]/gi,"-");
	
	data.p = [];
	$(".popup table tr").each(function(){
		if(!$(this).is(":first-child")){
			data.p.push({eat:$(this).find("input").val(),bubble:$(this).find("select").val()});
		}
	});
	$.ajax({
		url:"/bubble/addlist.php?mode=addgroup&email="+$("#name select").val(),
		type:"POST",
		data:data,
		success:function(r){
			console.log(r);
			reload(event);
		},
		error:function(err){
			alert("error");
			console.log(err);
		}
	})
}
function savedbubble(e){
	var event = event || e;
	var sk = $("#savedbubble").val();
	var oksk = confirm("Add "+sk+"?");
	if(oksk && sk != ""){
		$.ajax({
			url:"/bubble/addlist.php?mode=savedbubble&sk="+sk+"&email="+$("#name select").val()+"&date="+$("#date input").val()+"&meal="+$("#meal select").val(),
			success:function(r){
				reload(event);
			},
			error:function(err){
				alert("error");
				console.log(err);
			}
		})
	}
}

function showeat(e){
	var event = event || e;
	var pos = $(event.target).position();
	var eat = $("<div id='balloon' style='top:"+pos.top+";left:"+pos.left+"'>"+$(event.target).attr('eat')+"</div>").click(killballoon);
	$("body").append(eat);
	
}
function killballoon(){
	$("#balloon").remove();
}
var lasteat = "";
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
		var eat = prompt("What did you eat?",lasteat);
		lasteat = eat;
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

function overbubble(e){
	var event = event || e;
	var url = "";
	var bubble = $(event.target).closest("tr").attr('id');
	var eat = prompt("What did you eat?");
	url = "/bubble/addlist.php?mode=add&meal="+$("#meal select").val();
	url += "&bubble="+bubble+"&date="+$("#date input").val()+"&email="+$("#name select").val()+"&eat="+eat;
	
	$.ajax({
		url:url,
		success:function(res){
			console.log(res);
			$(event.target).addClass("fulloverbubble");
			
			$(event.target).attr('eat',eat);
			$("#"+$("#meal select").val()).append("<p>"+eat+"</p>");
			
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