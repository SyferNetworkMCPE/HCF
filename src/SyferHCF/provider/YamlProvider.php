<?php

namespace SyferHCF\provider;

use SyferHCF\listeners\interact\Shop;
use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Server;

use SyferHCF\kit\{Kit, KitBackup, KitManager};
use SyferHCF\crate\CrateBackup;
use SyferHCF\shop\ShopBackup;
use SyferHCF\koth\KothBackup;
use SyferHCF\Citadel\CitadelBackup;
use SyferHCF\packages\PackageBackup;

class YamlProvider {
	
	const ONE_DAY = 1 * 86400;
	
	const THREE_HOURS = 3 * 3600;
	
	/**
	 * @return void
	 */
	
	public static function init() : void {
		self::load();
		if(!is_dir(Loader::getInstance()->getDataFolder())){
			@mkdir(Loader::getInstance()->getDataFolder());
		}
		if(!is_dir(Loader::getInstance()->getDataFolder()."players")){
			@mkdir(Loader::getInstance()->getDataFolder()."players");
		}
		if(!is_dir(Loader::getInstance()->getDataFolder()."backup")){
			@mkdir(Loader::getInstance()->getDataFolder()."backup");
		}
		Loader::getInstance()->saveResource("config.yml");
		Loader::getInstance()->saveResource("messages.yml");
		Loader::getInstance()->saveResource("permissions.yml");
		Loader::getInstance()->saveResource("scoreboard_settings.yml");
		Loader::getInstance()->saveResource("bot_settings.yml");
		
		Loader::$warns = (new Config(Loader::getInstance()->getDataFolder()."WarnsData.yml", Config::YAML))->getAll();
		Loader::getInstance()->getLogger()->info(TE::LIGHT_PURPLE."SyferHCF ".TE::GOLD."| ".TE::GREEN."YamlProvider connected correctly");
	}
	
	/**
	 * @param Player $player
	 * @return void
	 */
	public static function createConfig(Player $player, Int $defaultTime = self::THREE_HOURS, Int $defaultRewardTime = self::ONE_DAY, Int $defaultMoney = null) : void {                     
		new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$player->getName()}.yml", Config::YAML, [
		"items.menu" => true,
		]);
	}
	
	/**
	 * @param String $playerName
	 * @param String $data
	 * @param String $type
	 */
	public static function setData(String $playerName, $data, $type) : void {
		$config = new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
		$config->set($data, $type);
		$config->save();
	}
	
	/**
	 * @param String $playerName
	 * @return String|Config
	 */
	public static function getData(String $playerName){
		return new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
	}
	
	/**
	 * @param String $playerName
	 * @param String $configType
	 * @param String|Int $config
	 * @return void
	 */
	public static function reset(String $playerName, String $configType, $configSelect) : void {
		self::setData($playerName, $configType, $configSelect);
	}
	
	/**
	 * @param String $playerName
	 * @param String $kitName
	 */
	public static function getKitTime(String $playerName, String $kitName){
		return self::getData($playerName)->get($kitName);
	}
	
	/**
	 * @return void
	 */
	public static function load() : void {
		try {
			$appleenchanted = (new Config(Loader::getInstance()->getDataFolder()."cooldowns.yml", Config::YAML))->getAll();
			if(!empty($appleenchanted)){
				Loader::$appleenchanted = $appleenchanted;
			}
			CrateBackup::initAll();
			ShopBackup::initAll();
			KothBackup::initAll();
			CitadelBackup::initAll();
			PackageBackup::initAll();

		} catch (\Exception $exception) {
			Loader::getInstance()->getLogger()->error($exception->getMessage());
		}
	}
	
	/**
	 * @return void
	 */
	public static function save() : void {
		try {
			if(!empty(Loader::$appleenchanted)){
                $file = new Config(Loader::getInstance()->getDataFolder()."cooldowns.yml", \pocketmine\utils\Config::YAML);
                $file->setAll(Loader::$appleenchanted);
                $file->save();
            }
			CrateBackup::saveAll();
			ShopBackup::saveAll();
			KothBackup::saveAll();
			CitadelBackup::saveAll();
			PackageBackup::saveAll();

		} catch (\Exception $exception) {
			Loader::getInstance()->getLogger()->error($exception->getMessage());
		}
	}
}

?>
