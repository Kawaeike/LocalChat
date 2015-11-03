var socket;
var blockRequest = [];
var blockMsg = [];
var user = [];
var name = "";
var status = "";


function init(){
	var host = "ws://localhost:12345/WS/server.php";
	try{
		socket = new WebSocket(host);
		socket.onopen    = function(msg){if(status == "d"){info("Connected")} status="c"; console.log("Welcome"); };
		socket.onmessage = function(msg){ split(msg) };
		socket.onclose   = function(msg){if(status != "d"){error("Disconnected");} status="d"; reconnect(); };
	}
	catch(ex){console.log(ex);}
}
function reconnect()
{
	setTimeout(function(){init();},2000)
}

function split(data)
{
	json = JSON.parse(data.data);
	console.log(json)
	if(typeof(json.mess) != 'undefined'){chat(json.mess);}
	if(typeof(json.command) != 'undefined'){eval(json.command);}
	if(typeof(json.user) != 'undefined'){users(json.user);}
	if(typeof(json.game) != 'undefined'){game(json.game);}
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
		if (mess.message.match(/^!me /))
		{
			var timestamp = '<span class="timestamp">'+mess.time+'|</span>';
			var sender = '<span class="sender">['+mess.sender+']</span>';
			var content = '<span class="me">'+mess.message+'</span>';
			$("#chatMsg").append('<div class="msg">'+timestamp+sender+content+'</div>');
		}
		else
		{
			var timestamp = '<span class="timestamp">'+mess.time+'|</span>';
			var sender = '<span class="sender">['+mess.sender+']</span>';
			var content = '<span class="me">'+mess.message+'</span>';
			$("#chatMsg").append('<div class="msg">'+timestamp+sender+content+'</div>');	
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
	var msg = '{"time":"'+h+":"+m+":"+s+'","sender":"ERROR","message":"'+error+'"}'
	msg = JSON.parse(msg);
	chat(msg);
}
function info(info)
{
	var d = new Date();
	var h = addZero(d.getHours());
	var m = addZero(d.getMinutes());
	var s = addZero(d.getSeconds());
	var msg = '{"time":"'+h+":"+m+":"+s+'","sender":"INFO","message":"'+info+'"}'
	msg = JSON.parse(msg);
	chat(msg);
}
function game(data)
{
	window.parent.frames[0].frameElement.contentWindow.frame(data);
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


$(document).ready(function() {
	$("#msg").focus();
	init();
	$(document).keypress(function(e)
	{
		if(e.which == 13)
		{
			send();
		}
	});
});
$(window).unload(function() {
	socket.close();
});