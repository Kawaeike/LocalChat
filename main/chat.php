<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class chat{

	public function socket_server($port,$server_ip)
	{
		global $sockets,$groups,$master;

		$main = new Group;
		$main->port = $port;
		$main->name = "Main";
		$main->members = array();
		$main->task = "chat";
		array_push($groups,$main);

		$master = connection::WebSocket($server_ip,$port);
		$sockets = array($master);

		while(true){

			$changed = $sockets;
			$write = NULL;
			$except = NULL;
			socket_select($changed,$write,$except,NULL);
			foreach($changed as $socket){
				if($socket==$master)
				{
					$client_socket=socket_accept($master);
					if($client_socket<0){ 
						continue; 
					}
					else{ 
						connection::connect($client_socket);
					}
				}
				else{
					$client = select::bysocket($socket);
					$bytes_lenght = socket_recv($socket, $buffer, 2048, 0);
					if ($bytes_lenght <= 6) {
						connection::disconnect($client);
					}
					else{
						if($client->status < 2){
							connection::dohandshake($client,$buffer);
						}
						else{
							$buffer = other::handle_data($buffer);
							if($client->status < 3){
								connection::login($client,$buffer);
							}
							else
							{
								if(true/*block/spamm filter*/){
									chat::process($client,$buffer);
								}
								else{
									//nothing
								}
							}
						}
					}
				}
			}
			chat::logm("run\n");
		}
	}

	public static function send_msg($client,$msg,$reciver){

		if(DEBUG){echo "send_msg\n";}
		$msg = '{"mess":{"time":"'. date('H:i:s') .'","group":"'. $client->active_group .'","sender":"'. $client->name .'","message":"'. $msg .'"}}';
		$msg = other::encoded($msg);
		socket_write($reciver->socket,$msg,strlen($msg));
	}

	public static function process($client,$msg){

		if(DEBUG){echo "process\n";}
		$msg = str_replace("&", "&amp;", $msg);
		$msg = str_replace("<", "&lt;", $msg);
		$msg = str_replace(">", "&gt;", $msg);

		$receivers = select::receivers($client->active_group);

		if(substr($msg,0,1) == "!game"){

			$gamemode::run($msg,$client,$receivers);
		}
		else if(substr($msg,0,1) == "!"){
			commands::command($msg,$client,$receivers);
		}
		else{
			chat::send($client,$msg,$receivers);
		}
	}


	public static function send($client,$msg,$receivers){

		global $master;
		if(DEBUG){echo "send\n";}
		foreach ($receivers->members as $reciver) {
			if ($reciver == $master){}
			else{
				chat::send_msg($client,$msg,$reciver);
			}
		}
	}

	private static function logm($logMsg = "test"){

		if (OUTPUT) {
			$class = get_class();
			echo "[". $class ."]".$logMsg;
		}
	} 
	private static function logdie($logMsg){
		die("[chat]".$logMsg);
	}
}
?>