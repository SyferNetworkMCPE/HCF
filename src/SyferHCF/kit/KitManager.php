<?php

namespace SyferHCF\kit;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\Config;

class KitManager {
	
	/** @var Array[] */
	public static $kit = [];
	
	/**
	 * @param String $kitName
	 * @return bool
	 */
	public static function isKit(String $kitName) : bool {
		if(isset(self::$kit[$kitName])){
			return true;
		}else{
			return false;
		}
		return false;
	}
	
	/**
	 * @param Array $args
	 * @return void
	 */
	public static function createKit(Array $args = []) : void {
		self::$kit[$args["name"]] = new Kit($args["name"], !empty($args["contents"]) ? $args["contents"] : [], !empty($args["armorContents"]) ? $args["armorContents"] : [], $args["permission"], $args["nameFormat"]);
	}
	
	/**
	 * @param String $kitName
	 * @return void
	 */
	public static function removeKit(String $kitName) : void {
		unset(self::$kit[$kitName]);
		$file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."kits.yml", Config::YAML);
		$file->remove($kitName);
		$file->save();
	}

	/**
	 * @param String $kitName
	 * @return Kit
	 */
	public static function getKit(String $kitName) : ?Kit {
		return self::isKit($kitName) ? self::$kit[$kitName] : null;
	}
	
	/**
	 * @return Array[]
	 */
	public static function getKits() : Array {
		return self::$kit;
	}
}

?>
