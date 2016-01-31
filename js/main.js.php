var socket;
var blockRequest = [];
var blockMsg = [];
var user = [];
var name = "";
var status = "";
var heightnummber = 9999999999999
var run_modules = [];
var module_nummber = 0;



function init(){
	var host = "ws://"+"<?php echo getHostByName(getHostName()) ?>"+":12345/WS/server.php";
	try{
		socket = new WebSocket(host);
		socket.onopen = function(msg){
			if(status != "c"){
				info("Connected");
			} 
			status="c";
		};
		socket.onmessage = function(msg){
			split(msg)
		};
		socket.onclose = function(msg){
			if(status != "d"){
				error("Disconnect");
			}
			status="d";
			reconnect();
		};
	}
	catch(ex){console.log(ex); }
}
function reconnect()
{
	setTimeout(function(){init();},2000);
}

function split(data)
{
	json = JSON.parse(data.data);
	if(json.type == "mess"){
		chat(json);
	}
	else if(json.type == "command"){
		eval(json.data);
	}
	else if(json.type == "user"){
		users(json);
	}
	else if(json.type == "data"){
		module(json);
	}
	else{
		console.log(json)
	}
}
function users(user)
{
	for (var i = 0; i < user.length; i++) {
		if(user[i].status == "connect")
		{
			$('#user tr:last').after('<tr id='+user[i].name+'><td><img src="img/online.png" title="online"></td><td>'+user[i].name+'</td></tr>');
			user.append(user[i].name);
		}
		else if(user[i].status == "disconnenct")
		{
			$('table tr#'+user[i].name).remove();
			var index = user.indexOf(user[i].name);
			if (index > -1) {
    			user.splice(index, 1);
			}
			else{console.log("Disconnected user["+user[i].name+"] not found")}

		}
		else{console.log("undefind status")};
	}
}
function chat(mess)
{
	if(blockMsg.indexOf(mess.sender) == -1)
	{
		var bar = 0;
		var tmp = $("#chatMsg").scrollTop();
		$("#chatMsg").scrollTop(heightnummber);
		var tmp2 = $("#chatMsg").scrollTop();
		if(tmp2 == tmp){
			bar = 1;
		}
		else
		{
			$("#chatMsg").scrollTop(tmp);
		}
		
		if (mess.data.match(/^!me /))
		{
			var timestamp = '<span class="timestamp">'+mess.time+'|</span>';
			var sender = '<span class="sender">['+mess.sender+']</span>';
			var content = '<span class="me">'+mess.data+'</span>';
			$("#chatMsg").append('<div id="'+mess.id+'" class="msg">'+timestamp+sender+content+'</div>');
		}
		else
		{
			var timestamp = '<span class="timestamp">'+mess.time+'|</span>';
			var sender = '<span class="sender">['+mess.sender+']</span>';
			var content = '<span class="me">'+mess.data+'</span>';
			$("#chatMsg").append('<div id="'+mess.id+'" class="msg">'+timestamp+sender+content+'</div>');	
		}
		if(bar == 1){
			$("#chatMsg").scrollTop(heightnummber)
		}
	}
}
function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
function error(error)
{
	var d = new Date();
	var h = addZero(d.getHours());
	var m = addZero(d.getMinutes());
	var s = addZero(d.getSeconds());
	var msg = '{"data": "'+error+'", "type": "message", "time": "'+h+":"+m+":"+s+'", "group": "none", "sender":"ERROR" }';
	msg = JSON.parse(msg);
	chat(msg);
}
function info(info)
{
	var d = new Date();
	var h = addZero(d.getHours());
	var m = addZero(d.getMinutes());
	var s = addZero(d.getSeconds());
	var msg = '{"data": "'+info+'", "type": "message", "time": "'+h+":"+m+":"+s+'", "group": "none", "sender":"INFO" }';
	var msg = JSON.parse(msg);
	chat(msg);
}
function module(module_data)
{
	if(typeof run_modules[module_data.data.id] == 'undefined'){
		
		run_modules[module_data.data.id] = module_nummber;
		module_nummber++;
		style = "height:"+module_data.data.size.height+"; width:"+module_data.data.size.width;
		src = "http://<?php echo getHostByName(getHostName()) ?>/localchat/modules/"+module_data.data.name+"/"+module_data.data.files.client;
		$("#modules").append('<iframe border="0" src="'+ src +'" id="'+ module_data.id +'" style="'+ style +'" > </iframe>');
		setTimeout(function(){
			window.parent.frames[run_modules[module_data.data.id]].frameElement.contentWindow.socketrecive('{"type":"set_m_id","value":"'+module_data.data.id+'"}');
		},5000)
		
	}
	else{
		window.parent.frames[run_modules[module_data.data.id]].frameElement.contentWindow.socketrecive(module_data);
	}
}
function modulerecive(data){

	console.log(data)


		if(socket.readyState == 1){
			try{ socket.send(data); } catch(ex){ error(ex); }
		}
		else{
			error("Socket is not open")
		}
	
}

function send(){
	var txt,msg;
	txt = $("#msg");
	msg = txt.val();
	if(!msg){ alert("Message can not be empty"); return; }
	txt.val("");
	txt.focus();

	if(msg.substr(0,1) == "!")
	{
		if(commands(msg)){

		}
		else{

			if(socket.readyState == 1){
				try{ socket.send(msg); } catch(ex){ error(ex); }
			}
			else{
				error("Socket is not open")
			}
		}
	}
	else{
		if(socket.readyState == 1){
			try{ socket.send(msg); } catch(ex){ error(ex); }
		}
		else{
			error("Socket is not open")
		}
	}
}
function commands(com){
	com.substr(1)
	parts = com.split(" ")

	switch(parts[0]){
		case "toggle":

		break;
		case "block":
			switch(parts[1]){
				case "msg":

				break;
			}
		break;
		case "unblock":

		break;
	}

}
function load_module(module_name,data)
{

}


$(document).ready(function(){
	$("#msg").focus();
	init();
	$(document).keypress(function(e){
		if(e.which == 13)
		{
			send();
		}
	});
});
$(window).unload(function() {
	socket.close();
});
function auto_command(elm){
		id = $(elm).attr("id")
		val = $(elm).text();
		part = $(elm).parent(".me").text().split("[");
		part = part[0].split(" ");
		part = part[part.length-1]
		if(val == "accept"){
			msg = "!accept "+id;
			$(elm).parent(".me").text("you accepted the invite to "+part);
		}
		else{
			msg = "!reject "+id;
			$(elm).parent(".me").text("you rejected the invite to "+part);
		}
		try{ socket.send(msg); } catch(ex){ error(ex); }
}
