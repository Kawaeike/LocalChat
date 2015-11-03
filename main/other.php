<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class other{

	public static function handle_data($data)
	{
		$bytes = $data;
		$data_length = "";
		$mask = "";
		$coded_data = "" ;
		$decoded_data = "";        
		$data_length = $bytes[1] & 127;
		if($data_length === 126){
			 $mask = substr($bytes, 4, 8);
			 $coded_data = substr($bytes, 8);
		}
		else if($data_length === 127){
			$mask = substr($bytes, 10, 14);
			$coded_data = substr($bytes, 14);
		}
		else{
			$mask = substr($bytes, 2, 6);
			$coded_data = substr($bytes, 6);
		}
		for($i=0;$i<strlen($coded_data);$i++){
			$decoded_data .= $coded_data[$i] ^ $mask[$i%4];
		}
		return $decoded_data;
	}

	public static function encoded($data){
		$frame = Array();
		$encoded = "";
		$frame[0] = 0x81;
		$data_length = strlen($data);

		if($data_length <= 125){
			$frame[1] = $data_length;    
		}
		else{
			$frame[1] = 126;  
			$frame[2] = $data_length >> 8;
			$frame[3] = $data_length & 0xFF; 
		}

		for($i=0;$i<sizeof($frame);$i++){
			$encoded .= chr($frame[$i]);
		}
		$encoded .= $data; 
		return $encoded;     
	}

	private function logm($logMsg){
		if (OUTPUT) {
			$class = get_class();
			echo "[". $class ."]".$logMsg;
		}
	} 
	private function logdie($logMsg){
		die("[other]".$logMsg);
	}
}
?>