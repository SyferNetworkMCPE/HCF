<?php

namespace SyferHCF;

use pocketmine\math\Vector3;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\nbt\tag\StringTag;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\Server;

use SyferHCF\provider\{
    SQLite3Provider, YamlProvider, MysqlProvider,
};
use SyferHCF\player\{
    Player,
};
use SyferHCF\koth\{
	KothBackup,
};
use SyferHCF\kit\{
	KitBackup,
};
use SyferHCF\crate\{
	CrateBackup,
};
use SyferHCF\level\{
   LevelManager,
};
use SyferHCF\API\{
    Scoreboards,
};
use SyferHCF\Task\{
	BardTask, OpBardTask, ArcherTask, MageTask, OpArcherTask, OpRogueTask, RogueTask, DelayedCrossDimensionTeleportTask, TopKillTask,
};
use SyferHCF\Task\event\{
	FactionTask,
};
use SyferHCF\block\{
    Blocks,
};
use SyferHCF\listeners\{
	Listeners,
};
use SyferHCF\commands\{
    Commands,
};
use SyferHCF\item\{
    Items,
};
use SyferHCF\entities\{
    Entitys,
};
use SyferHCF\entities\spawnable\{
	EntitysTop,
	Top1,
	Top2,
	Top3,
};
use SyferHCF\enchantments\{
    Enchantments,
};
use SyferHCF\utils\{Data, Extensions, Settings, Discord, Time, EntityUtils, Dimensions};
use libs\muqsit\invmenu\InvMenuHandler;

class Loader extends PluginBase {
    
    /** @var Loader */
    protected static $instance;
    
    /** @var PREFIX\String */
    const PREFIX = TE::GRAY . "§8[§cSystem§8] " . TE::BOLD . TE::GRAY . "» " . TE::GRAY;

    /** @var KIDS\String */
    const KIDS = TE::GRAY . " » " . TE::RESET;

    /** @var Loader */
    protected static $pluginLogger = null, $dataLogger = null, $provider = null, $mysql = null;
    
    /** @var Array[] */
    public static $appleenchanted = [], $rogue = [], $oprogue = [], $mark = [], $v = [], $warns = [], $device = [], $staffmode = [], $freeze = [];
    
    public static $helmet = [], $kits = [], $names = [];
    
    /** @var Array[] */
	public $permission = [];
	
	/** @var LevelManager */
    public static $levelManager;
    
    /** @var int[] */
    public static $onPortal = [];
    
    /** @var string */
   public static $endName = "end";
   
   /** @var Level */
   public static $endLevel;
   
   /** @var string */
   public static $netherName = "nether";
   
   /** @var Level */
   public static $netherLevel;
   
   /** @var string */
   public static $overworldLevelName = "HCF";
   
   /** @var Level */
   public static $overworldLevel;
   
   
   /**
	 * @return void
	 */
	public static function init(){
		$commands = array("kick", "ban", "unban", "op", "deop", "tell", "msg", "w", "banlist");
		for($i = 0; $i < count($commands); $i++){
			self::removeCommand($commands[$i]);
		}
		self::getInstance()->getServer()->getPluginManager()->registerEvents(new EventListener(self::getInstance()), self::getInstance());
	}
    
    /**
     * @return void
     */
    public function onLoad() : void {
        self::$instance = $this;
        self::$pluginLogger = $this;
        self::$dataLogger = new Data();
        $this->getServer()->getNetwork()->setName(" §r§d§lSyferHCF §r§7| §fBETA-2.0 §r§7");
    }
    
    /**
     * @return void
     */
    public function onEnable(): void
    {
    	
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        
        MysqlProvider::connect();
        SQLite3Provider::connect();

        Listeners::init();
        Commands::init();
        Items::init();
        Blocks::init();
        Entitys::init();
        Enchantments::init();
        
        YamlProvider::init();
        
        Factions::init();
        Extensions::getLevelManager()->init();
        
        @mkdir($this->getDataFolder() . "/Skins/");
         Loader::$instance->saveResource("Kills.yml");
         Entity::registerEntity(Top1::class, true);
         Entity::registerEntity(Top2::class, true);
         Entity::registerEntity(Top3::class, true);
         $this->getScheduler()->scheduleRepeatingTask(new TopKillTask($this), 20);

        $this->getScheduler()->scheduleRepeatingTask(new RogueTask(), 8);
        $this->getScheduler()->scheduleRepeatingTask(new OpRogueTask(), 12);
        $this->getScheduler()->scheduleRepeatingTask(new BardTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new OpBardTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new ArcherTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new OpArcherTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MageTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new FactionTask(), 5 * 60 * 40);
            self::$netherName = "nether";
            self::$endName = "end";
            self::$overworldLevelName = "HCF";
            self::$endLevel = Server::getInstance()->getLevelByName(self::$endName);
            self::$netherLevel = Server::getInstance()->getLevelByName(self::$netherName);
            self::$overworldLevel = Server::getInstance()->getLevelByName(self::$overworldLevelName);
            $this->getServer()->loadLevel(self::getDefaultConfig("Worlds")["nether"]);
            $this->getServer()->loadLevel(self::getDefaultConfig("Worlds")["end"]);
            
    }}
    
    /**
     * @return void
     */
     
    public function onDisable(): void
    {
    	
        SQLite3Provider::disconnect();
        MysqlProvider::disconnect();
        
        YamlProvider::save();

    }
  
    public static function getInstance(): Loader
    {
        return self::$instance;
    }
    
    /**
     * @return LevelManager
     */
    static function getLevelManager(): LevelManager {
        return self::$levelManager;
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
    
    public static function getDataConfig($configuration){
        return self::getInstance()->getConfig()->get($configuration);
    }
    
    /**
	 * @param String $commamd
	 */
	public static function removeCommand(String $command){
		$commandMap = self::getInstance()->getServer()->getCommandMap();
		$cmd = $commandMap->getCommand($command);
		if($cmd === null){
			return;
		}
		$cmd->setLabel("");
		$cmd->unregister($commandMap);
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
}

?>
