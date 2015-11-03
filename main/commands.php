<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class command{

	public function command($msg,$user){

		$msg = substr($msg, 1);
		$command = explode(' ',$msg);
		logm("command:". $command[0] ."|rechte [". $user->rights ."]\n");
		logm("target:". $command[1] ."\n");

		
		switch ([ $command[0], $user->rights])
		{
			case ["COMMAND", rights]:

			break;
		}
	}

	private function logm($logMsg){
		if (OUTPUT) {
			echo "[commands]".$logMsg;
		}
	} 
	private function logdie($logMsg){
		die("[commands]".$logMsg);
	}
}
?>