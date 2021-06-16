<?php

namespace BullHCF\provider;

use BullHCF\listeners\interact\Shop;
use BullHCF\Loader;
use BullHCF\player\Player;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Server;

use BullHCF\kit\KitBackup;
use BullHCF\crate\CrateBackup;
use BullHCF\shop\ShopBackup;
use BullHCF\koth\KothBackup;

class YamlProvider {
	
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
		Loader::getInstance()->getLogger()->info(TE::GREEN."YamlProvider » was loaded successfully!");
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
			KitBackup::initAll();
			CrateBackup::initAll();
			ShopBackup::initAll();
			KothBackup::initAll();

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
			KitBackup::saveAll();
			CrateBackup::saveAll();
			ShopBackup::saveAll();
			KothBackup::saveAll();

		} catch (\Exception $exception) {
			Loader::getInstance()->getLogger()->error($exception->getMessage());
		}
	}
}

?>