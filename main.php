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

class message{
	public $mess;
	public $type;
	public $time;
	public $group;
	
	__construct($type,$client,$msg,$reciver){
		$this->mess = $msg;
		$this->type = $type;
		$this->time = date('H:i:s');
		$this->groupe = $reciver->name;
	}
}

class User{
	public $socket;
	public $name;
	public $status;
	public $rights;
	public $invites = array();
	public $ip;
	public $active_group;
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
	public $rights;
	public $socket;

	function __construct($client){
		$this->socket = $client->socket;
		$this->rights = $client->rights;
	}
}
class Group{
	public $port;#?
	public $name;
	public $members = array();
	public $task;
}

include ROOT_DIR.'\\main\\connection.php';
include ROOT_DIR.'\\main\\other.php';
include ROOT_DIR.'\\main\\select.php';
include ROOT_DIR.'\\main\\chat.php';
include ROOT_DIR.'\\main\\commands.php';

echo "[main]Main directories included\n";


foreach(new DirectoryIterator(".\\modules") as $datei)
{
	if($datei->isDir())
	{
		echo "[main]loading ". $datei->getFilename() ."...   ";
		
		
		$json = file_get_contents(".\\modules\\". $datei->getFilename() ."\\config.json");
		
		if(NULL != json_decode($json);)
		{
			include("\\modules\\". $datei->getFilename() ."\\". $datei->getFilename() .".php");
		}
		else
		{
			echo "failed no or invalid JSON"
		}
	}
}

echo "[main]Modules included\n";

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



