<?php
define('ROOT_DIR',dirname($_SERVER['PHP_SELF']));
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();


//----------------------- start parameter
$port = 12345;
define("OUTPUT",true);
define("DEBUG",false);

//----------------------- start parameter

$server_ip = getHostByName(getHostName());
$users = array();
$groups = array();
$sockets = array();
$master;

class User{
	var $socket;
	var $name;
	var $status;
	var $rights;
	var $invites = array();
	var $ip;
	var $active_group;
}
/*
status
	0 = start
	1 = do handeshacke
	2 = do login
	3 = online
	4 = in game
	5 = offline
*/
class member{
	var $rights;
	var $socket;

	function __construct($client){
		$this->socket = $client->socket;
		$this->rights = $client->rights;
	}
}
class Group{
	var $port;#?
	var $name;
	var $members = array();
	var $task;
}

include ROOT_DIR.'\\main\\connection.php';
include ROOT_DIR.'\\main\\other.php';
include ROOT_DIR.'\\main\\select.php';
include ROOT_DIR.'\\main\\chat.php';
include ROOT_DIR.'\\main\\commands.php';

echo "[main]Main directories included\n";

//include ROOT_DIR.\\battelship\\main.php

echo "[main]Games directories included\n";

echo "[main]Starting chat server on port: ".$port.".\n";

echo "----------------------------------------------------------------------\n";

/*
	$client(Obj) sender off the message
	$msg(str) message off the client
	$reciver(arr) recivers off the message
*/


$chat = new chat;

	$server = new User;
	$server->name = "SERVER";
	$server->rights = 40000000;
	$server->status = 3;
	array_push($users,$server);

	$chat->socket_server($port,$server_ip);



echo "ende"

?>


