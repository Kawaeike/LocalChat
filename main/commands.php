<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class commands{

	public static function command($msg,$user){

		$returnmsg = "";
		$msg = substr($msg, 1);
		$command = explode(' ',$msg);
		commands::logm("command:". $command[0] ."|rechte [". $user->rights ."]\n");

		
		switch ($command[0])
		{
			case "invite":

			break;
			case "join":

			break;
			case "leave":

			break;
			case "rights":

			break;
			case "group":

			break;
			case "eval":
				if($user->rights >= 10000000){
					global $sockets,$users,$groups,$server;

					ob_start();
					eval($command[1].";");
					$result = ob_get_clean();
					$returnmsg = $result;
				}
				else{
					$returnmsg = "Insufficient permissions!";
				}
			break;
			case "kick":

			break;
			case "ban":

			break;
			case "shout":
				if($user->rights > 100){

				}
				else{
					$returnmsg = "Insufficient permissions!";
				}
			break;
			case "w":
				if(select::byname($command[1])){
					$returnmsg = "You whispert to:". $command[1] ." ". $msg;
				}
				else{
					$returnmsg = "User \"". $command[1] ."\" not found";
				}
			break;

			default:
				$returnmsg = "Command not found";
			break;
		}
		return $returnmsg;
	}

	private static function logm($logMsg){
		if (OUTPUT) {
			echo "[commands]".$logMsg;
		}
	} 
	private static function logdie($logMsg){
		die("[commands]".$logMsg);
	}
}
?>