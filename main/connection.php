<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class connection{

	public static function WebSocket($address = "localhost",$port){

		if(DEBUG){echo "WebSocket\n";}
		$master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)		or connection::logdie("socket_create() failed");
		socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)		or connection::logdie("socket_option() failed");
		socket_bind($master, $address, $port)						or connection::logdie("socket_bind() failed");
		socket_listen($master,20)									or connection::logdie("socket_listen() failed");
		connection::logm("Server Started : ".date("Y-m-d H:i:s")."\n");
		connection::logm("Master socket  : ".$master."\n");
		connection::logm("Listening on   : ".$address." port ".$port."\n\n");
		return $master;
	}

	public static function connect($socket){

		if(DEBUG){echo "connect\n";}
		socket_getpeername ($socket,$ip);
		connection::logm("connect ". $socket ."\n");
		global $sockets,$users;
		$user = new User();
		socket_getpeername ($socket,$ip);
		$user->ip = $ip;
		$user->socket = $socket;
		$user->rights = 100;
		$user->status = 0;
		array_push($users,$user);
		array_push($sockets,$socket);
	}

	public static function disconnect($client){

		global $sockets;
		if(DEBUG){echo "disconnect\n";}
		echo "disconnect ". $client->socket ."|\"". $client->name ."\"\n";
		$client->status = 5;
		for($i = 0;count($sockets) > $i;$i++) {
			if($client->socket == $sockets[$i]){
				unset($sockets[$i]);
			}
		}
		socket_close($client->socket);
		$client->socket = NULL;
	}

	public static function dohandshake($client,$buffer){

		if(DEBUG){echo "dohandshake\n";}
		list($resource,$host,$origin,$strkey1) = connection::getheaders($buffer);
		$newkey = base64_encode(sha1($strkey1 . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true));
		$upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
					"Upgrade: WebSocket\r\n" .
					"Connection: Upgrade\r\n" .
					"Sec-WebSocket-Accept: " . $newkey . "\r\n" .
					"\r\n";
		socket_write($client->socket,$upgrade,strlen($upgrade));
		$client->status=2;
		return true;
	}

	public static function getheaders($req){

		if(DEBUG){echo "getheaders\n";}
		$r=$h=$o=null;
		if(preg_match("/GET (.*) HTTP/"   ,$req,$match)){ $r=$match[1]; }
		if(preg_match("/Host: (.*)\r\n/"  ,$req,$match)){ $h=$match[1]; }
		if(preg_match("/Origin: (.*)\r\n/",$req,$match)){ $o=$match[1]; }
		if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$req,$match)){ $key1=$match[1]; }
		return array($r,$h,$o,$key1);
	}

	public static function login($client,$msg){
	global $server,$groups,$users;
		if(DEBUG){echo "login\n";}
		$temp = explode(" ",$msg);
		if($temp[0] == "!login"){
			if($client->ip == "127.0.0.1")#admin
			{
				$client->name = "Admin";
				$client->status = 3;
				$client->active_group = "Main";
				$client->rights = 10000000;
				$group = select::receivers($client->active_group);

				array_push($group->members,new member($client));
				array_push($users,$client);

				chat::send_msg($server,"Success",$client);
			}
			else if(select::byname($temp[1]) === false) {
				if(preg_match("/admin/i", $temp[1]) == 0 && preg_match("/server/i", $temp[1]) == 0 ){
					if(preg_match("/[-_a-z0-9]{4,20}/i",$temp[1])){
						$client->name = $temp[1];
						$client->status = 3;
						$client->active_group = "Main";

						$group = select::receivers($client->active_group);

						array_push($group->members,new member($client));
						array_push($users,$client);

						chat::send_msg($server,"Success",$client);
					}
					else{
						chat::send_msg($server,"Invalid Username",$client);
					}
				}
				else{
					chat::send_msg($server,"Username is reserved",$client);
				}
			}
			else{
				chat::send_msg($server,"Username already used! Chose a other!",$client);
			}
		}
		else{
			chat::send_msg($server,"You must first login with !login &lt;username&gt;!",$client);
		}
	}

	public static function MySQL($host="localhost",$username="root",$passwort="",$dbname="localchat"){

		if(DEBUG){echo "MySQL\n";}
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			logdie("[connect] ".$conn->connect_error."\n");
		}
		return true;
	}

	private static function logm($logMsg){
		if (OUTPUT) {
			echo "[connection]".$logMsg;
		}
	} 
	private static function logdie($logMsg)
	{
		die("[connection]".$logMsg);
	}
}
?>