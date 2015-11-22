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

define("SERVER_IP",getHostByName(getHostName()));
$users = array();
$groups = array();
$sockets = array();
$master;
$modules = array();

class message{
	public $data;
	public $type;
	public $time;
	public $group;
	public $id;
	public $sender;
	
	function __construct($type,$client,$msg,$reciver){
		$this->sender = $client->name;
		$this->data = $msg;
		$this->type = $type;
		$this->time = date('H:i:s');
		$this->group = $client->active_group;
		$this->id = substr(sha1($client->name . $this->data . $this->time),0,10);
	}
}

class User{
	public $socket;
	public $name;
	public $status;
	public $rights;
	public $invites = array();
	public $groups = array();
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
	#public $port;?
	public $name;
	public $members = array();
	public $task;
}

include_once(ROOT_DIR.'\\main\\connection.php');
include_once(ROOT_DIR.'\\main\\other.php');
include_once(ROOT_DIR.'\\main\\select.php');
include_once(ROOT_DIR.'\\main\\chat.php');
include_once(ROOT_DIR.'\\main\\commands.php');

echo "[main]Main directories included\n";
echo "[main]Including modules\n";

foreach(new DirectoryIterator(ROOT_DIR ."\\modules") as $datei)
{
	if($datei->isDir())
	{
		if($datei->getFilename() != "." && $datei->getFilename() != "..")
		{
			echo "[main]loading ". $datei->getFilename() ." ... ";
		
			$json = file_get_contents(ROOT_DIR ."\\modules\\". $datei->getFilename() ."\\config.json");
		
			if(NULL != json_decode($json))
			{
				if(include_once(ROOT_DIR ."\\modules\\". $datei->getFilename() ."\\". $datei->getFilename() .".php"))
				{
					echo "finish\n";
					array_push($modules,$datei->getFilename());
				}
				/*
				else if() check for lua files
				*/
				else
				{
					echo "failed no server side file\n";
				}

			}
			else
			{
				echo "failed no or invalid JSON\n";
			}
		}
	}
}

echo "[main]Modules included\n";
echo "[main]Starting chat server on port: ".$port.".\n";
echo "----------------------------------------------------------------------\n";

$chat = new chat;

	$server = new User;
	$server->name = "SERVER";
	$server->rights = 40000000;
	$server->status = 3;
	array_push($users,$server);

	$chat->socket_server($port);

echo "ende";

?>



