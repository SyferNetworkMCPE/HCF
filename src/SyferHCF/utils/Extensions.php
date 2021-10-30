<?php

namespace SyferHCF\utils;

use SyferHCF\Loader;
use SyferHCF\player\Player;
use SyferHCF\player\PlayerBase;
use SyferHCF\level\LevelManager;
use SyferHCF\View\ViewMenu;
use SyferHCF\provider\MySQLProvider;
use SyferHCF\provider\SQLite3Provider;

class Extensions {
    public static function getLevelManager(): LevelManager {
        return new LevelManager(Loader::getInstance());
    }
    
    public static function getViewMenu(): ViewMenu {
        return new ViewMenu(Loader::getInstance());
    }

    public static function getMySQLProvider() : MySQLProvider {
        return new MySQLProvider(Loader::getInstance());
    }

    public static function getProvider() : SQLite3Provider {
        return new SQLite3Provider();
    }
    
    public static function getBasicsettings() : Settings {
        return new Settings();
    }

    public static function getPlayerBase(Player $player): PlayerBase {
        return new PlayerBase(self::getInstance(), $player);
    }
}
