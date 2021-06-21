<?php

namespace BullHCF;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use BullHCF\provider\{
    SQLite3Provider, YamlProvider,
};
use BullHCF\player\{
    Player,
};
use BullHCF\API\{
    Scoreboards,
};
use BullHCF\Task\{
	BardTask, ArcherTask,
};
use BullHCF\Task\event\{
	FactionTask,
};
use BullHCF\Task\updater\{
    NinjaShearUpdaterTask,
};
use BullHCF\listeners\{
	Listeners,
};
use BullHCF\commands\{
    Commands,
};
use BullHCF\item\{
    Items,
};
use BullHCF\block\{
    Blocks,
};
use BullHCF\entities\{
    Entitys,
};
use BullHCF\enchantments\{
    Enchantments,
};
class Loader extends PluginBase {
    
    /** @var Loader */
    protected static $instance;
    
    /** @var Array[] */
    public static $appleenchanted = [];
    
    /** @var Array[] */
	public $permission = [];
    
    /**
     * @return void
     */
    public function onLoad() : void {
        self::$instance = $this;
    }
    
    /**
     * @return void
     */
    public function onEnable() : void {
        SQLite3Provider::connect();

        Listeners::init();
        Commands::init();
        Items::init();
        Blocks::init();
        Entitys::init();
        Enchantments::init();
        
        YamlProvider::init();
        
        Factions::init();

        $this->getScheduler()->scheduleRepeatingTask(new BardTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new ArcherTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new FactionTask(), 5 * 60 * 40);
    }
    
    /**
     * @return void
     */
    public function onDisable() : void {
        SQLite3Provider::disconnect();

        YamlProvider::save();
    }

    /**
     * @return Loader
     */
    public static function getInstance() : Loader {
        return self::$instance;
    }

    /**
     * @return SQLite3Provider
     */
    public static function getProvider() : SQLite3Provider {
        return new SQLite3Provider();
    }

    /**
     * @return Scoreboards
     */
	public static function getScoreboard() : Scoreboards {
		return new Scoreboards();
    }

    /**
     * @param String $configuration
     */
    public static function getDefaultConfig($configuration){
        return self::getInstance()->getConfig()->get($configuration);
    }
    
    /**
     * @param String $configuration
     */
    public static function getConfiguration($configuration){
    	return new Config(self::getInstance()->getDataFolder()."{$configuration}.yml", Config::YAML);
    }

    /**
     * @param Player $player
     */
    public function getPermission(Player $player){
        if(!isset($this->permission[$player->getName()])){
            $this->permission[$player->getName()] = $player->addAttachment($this);
        }
        return $this->permission[$player->getName()];
    }

    public function getPurePerms()
    {
        return $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    }

}

?>