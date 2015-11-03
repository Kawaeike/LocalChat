<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class input_handler extends Thread
{
	function __construct(){
		$server = new User;
		$server->name = "Server";
		$server->rights = 2147483646;
		$server->status = "active";
	}

	public function handle_input()
	{
		while (true) {
			$line = readline("Command: ");
        	readline_add_history($line);

        	log($line);
        }
	}
	private function log($logMsg){
	if (OUTPUT) {
		echo "[input_handler]".$logMsg;
	}
	} 
	private function logdie($logMsg)
	{
		die("[input_handler]".$logMsg);
	}
}