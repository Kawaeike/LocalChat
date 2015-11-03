<?php
if (!defined('ROOT_DIR')) {die("Nop");}

class select{

	public static function byname($name)
	{
		global $users;
		
		$found=false;
		foreach($users as $user){
			if($user->name==$name){
				$found=$user; break;
			}
		}
		return $found;
	}
	public static function receivers($group_name)
	{
		global $groups;
		
		$found=false;
		foreach($groups as $group){
			if($group->name==$group_name){
				$found = $group;
			}
		}
		return $found;
	}
	public static function bysocket($socket)
	{
		global $users;
		$found=false;
		foreach($users as $user){
			if($user->socket==$socket){
				$found=$user; break;
			}
		}
		return $found;
	}

	private function logm($logMsg){
		if (OUTPUT) {
			echo "[select]".$logMsg;
		}
	} 
	private function logdie($logMsg){
		die("[select]".$logMsg);
	}
}
?>