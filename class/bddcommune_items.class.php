<?php

class bddcommune_items extends global_class {

	static function getPDO() {		
		if (!static::$PDO) {
			$pdo = cpdo::getInstance();
			static::$PDO = $pdo;
		} else {
			$pdo = static::$PDO;
		}
		return $pdo;
	}
	
	public static function prefix_bdd() {
		return "";
	}

	public static function editable(){
		return false;
	}
	public static function supprimable(){
		return false;
	}
	

}
