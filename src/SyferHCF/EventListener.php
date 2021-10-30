
<?php

namespace SyferHCF;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\{Player, PlayerBase};
use SyferHCF\provider\{YamlProvider, MysqlProvider};
use SyferHCF\Task\asynctask\{LoadPlayerData, SavePlayerData};
use SyferHCF\utils\Extensions;
use SyferHCF\Task\Scoreboard;
use Himbeer\LibSkin\SkinConverter;
use SyferHCF\entities\spawnable\{EntitysTop, Top1, Top2, Top3};
use pocketmine\entity\Skin;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TE;
use pocketmine\level\biome\Biome;
use pocketmine\utils\Config;

use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\particle\HeartParticle;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent, PlayerMoveEvent, PlayerInteractEvent};
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerRespawnEvent;

use pocketmine\network\mcpe\protocol\LevelEventPacket;

class EventListener implements Listener {

    /**
     * EventListener Constructor.
     */
    public function __construct(){
		
    }
    
    /**
     * @param PlayerCreationEvent $event
     * @return void
     */
    public function onPlayerCreationEvent(PlayerCreationEvent $event) : void {
        $event->setPlayerClass(Player::class, true);
    }

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onPlayerJoinEvent(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $pl = $event->getPlayer();
        SkinConverter::skinDataToImageSave($pl->getSkin()->getSkinData(), Loader::getInstance()->getDataFolder() . "Skins/{$pl->getName()}.png");
        $player->setDisplayName($player->getName());
        $event->setJoinMessage(TE::GREEN." + ".TE::BOLD.TE::LIGHT_PURPLE.$player->getName().TE::RESET.TE::WHITE." has entered HCF");
        $source = (new Vector3($player->x, $player->y, $player->z))->floor();
        $player->getLevel()->addSound(new GhastShootSound($source));
        $player->getLevel()->addParticle(new HeartParticle($source));
        $player->sendMessage("§8      §6");
	    $player->sendMessage("§8       §6");
		$player->sendMessage("§8      §d§lSyferHCF     ");
		$player->sendMessage("§8  §7Welcome to Map §f#§5BETA-2.0");
		$player->sendMessage("§8 §b");
		$player->sendMessage("§l§5Store§r§f: §r§dhttps://syferhcf.tebex.io");
		$player->sendMessage("§l§5Discord§r§f: §r§dhttps://discord.gg/uMUgd63YAZ");
		$player->sendMessage("§l§5Vote§r§f: §r§dComming Soon!");
		$player->sendMessage("§8       §6");
		$player->sendMessage("§8       §6");
		$player->sendMessage("§r§7Welcome to SyferHCF!");
        
        YamlProvider::createConfig($player);
        PlayerBase::create($player->getName());
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new Scoreboard($player), 20);
        Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new LoadPlayerData($player->getName(), $player->getUniqueId()->toString(), Loader::getDefaultConfig("MySQL")["hostname"], Loader::getDefaultConfig("MySQL")["username"], Loader::getDefaultConfig("MySQL")["password"], Loader::getDefaultConfig("MySQL")["database"], Loader::getDefaultConfig("MySQL")["port"]));
    }
    
 /**
  * @param EntityDamageByEntityEvent
  */
  public function onDamageNpcs(EntityDamageByEntityEvent $e) {
    if ($e->getEntity() instanceof Top1 or $e->getEntity() instanceof Top2 or $e->getEntity() instanceof Top3) {
      $pl = $e->getDamager();
      if ($pl instanceof Player) {
        $e->setCancelled(true);
      }
    }
  }

  /**
  * @param PlayerDeathEvent
  */
  public function addKill(PlayerDeathEvent $e) {
    $pl = $e->getPlayer();
    $causa = $e->getEntity()->getLastDamageCause();
    if ($causa instanceof EntityDamageByEntityEvent) {
      $damager = $causa->getDamager();
      if (!$damager instanceof Player) return;
      $kills = new Config(Loader::getInstance()->getDataFolder() . "Kills.yml", Config::YAML);
      $kills->set($damager->getName(), $kills->get($pl->getName()) + 1);
      $kills->save();
      }
   }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
		$event->setQuitMessage(TE::RED." - ".TE::BOLD.TE::LIGHT_PURPLE.$player->getName().TE::RESET.TE::WHITE." has left HCF");

        Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new SavePlayerData($player->getName(), $player->getUniqueId()->toString(), $player->getClientId(), $player->getAddress(), Factions::inFaction($player->getName()) ? Factions::getFaction($player->getName()) : "This player not have faction", Loader::getDefaultConfig("MySQL")["hostname"], Loader::getDefaultConfig("MySQL")["username"], Loader::getDefaultConfig("MySQL")["password"], Loader::getDefaultConfig("MySQL")["database"], Loader::getDefaultConfig("MySQL")["port"]));
        if($player instanceof Player){
            $player->removePermissionsPlayer();
		}
	}
	
	/**
     * @param EntityLevelChangeEvent $event
     * @return void
     */
	public function onEntityLevelChangeEvent(EntityLevelChangeEvent $event) : void {
		$player = $event->getEntity();
		$player->showCoordinates();
	}
	
    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChatEvent(PlayerChatEvent $event) : void {
    	$player = $event->getPlayer();
    	$format = null;
    	if($player->getRank() === null||$player->getRank() === "Member"){
    		$format = TE::GRAY."[".TE::GREEN."Member".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Member_COL"){
    		$format = TE::GRAY."[".TE::GREEN."Member".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."]".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Member_MEX"){
    		$format = TE::GRAY."[".TE::GREEN."Member".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Member_ESP"){
    		$format = TE::GRAY."[".TE::GREEN."Member".TE::GRAY."]".TE::GRAY."[".TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Member_ARG"){
    		$format = TE::GRAY."[".TE::GREEN."Member".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		
    	if($player->getRank() === null||$player->getRank() === "Owner"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Owner_COL"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Owner_MEX"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Owner_ESP"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."]".TE::GRAY."[".TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Owner_ARG"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
    	if($player->getRank() === null||$player->getRank() === "Owner_666"){
    		$format = TE::GRAY."[".TE::DARK_RED."Owner".TE::GRAY."]".TE::GRAY."[".TE::OBFUSCATED.TE::RED."¡!".TE::RESET.TE::BOLD.TE::BLACK."666".TE::RESET.TE::OBFUSCATED.TE::RED."¡!".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Co-Owner"){
    		$format = TE::GRAY."[".TE::RED."Co-Owner".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Co-Owner_COL"){
    		$format = TE::GRAY."[".TE::RED."Co-Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Co-Owner_MEX"){
    		$format = TE::GRAY."[".TE::RED."Co-Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Co-Owner_ESP"){
    		$format = TE::GRAY."[".TE::RED."Co-Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Co-Owner_ARG"){
    		$format = TE::GRAY."[".TE::RED."Co-Owner".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "PlataformAdmin"){
    		$format = TE::GRAY."[".TE::RED."Plat.Admin".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "PlataformAdmin_COL"){
    		$format = TE::GRAY."[".TE::RED."Plat.Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "PlataformAdmin_MEX"){
    		$format = TE::GRAY."[".TE::RED."Plat.Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "PlataformAdmin_ESP"){
    		$format = TE::GRAY."[".TE::RED."Plat.Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "PlataformAdmin_ARG"){
    		$format = TE::GRAY."[".TE::RED."Plat.Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Developer"){
    		$format = TE::GRAY."[".TE::DARK_RED."Developer".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Dev_COL"){
    		$format = TE::GRAY."[".TE::DARK_RED."Developer".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Dev_MEX"){
    		$format = TE::GRAY."[".TE::DARK_RED."Developer".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Dev_ESP"){
    		$format = TE::GRAY."[".TE::DARK_RED."Developer".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Dev_ARG"){
    		$format = TE::GRAY."[".TE::DARK_RED."Developer".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "SrAdmin"){
    		$format = TE::GRAY."[".TE::GOLD."SrAdmin".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrAdmin_COL"){
    		$format = TE::GRAY."[".TE::GOLD."SrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrAdmin_MEX"){
    		$format = TE::GRAY."[".TE::GOLD."SrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrAdmin_ESP"){
    		$format = TE::GRAY."[".TE::GOLD."SrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrAdmin_ARG"){
    		$format = TE::GRAY."[".TE::GOLD."SrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Admin"){
    		$format = TE::GRAY."[".TE::YELLOW."Admin".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Admin_COL"){
    		$format = TE::GRAY."[".TE::YELLOW."Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Admin_MEX"){
    		$format = TE::GRAY."[".TE::YELLOW."Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Admin_ESP"){
    		$format = TE::GRAY."[".TE::YELLOW."Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Admin_ARG"){
    		$format = TE::GRAY."[".TE::YELLOW."Admin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
        

    	if($player->getRank() === null||$player->getRank() === "JrAdmin"){
    		$format = TE::GRAY."[".TE::AQUA."JrAdmin".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "JrAdmin_COL"){
    		$format = TE::GRAY."[".TE::AQUA."JrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "JrAdmin_MEX"){
    		$format = TE::GRAY."[".TE::AQUA."JrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "JrAdmin_ESP"){
    		$format = TE::GRAY."[".TE::AQUA."JrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BLUE."C".TE::WHITE."H".TE::RED."I".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "JrAdmin_ARG"){
    		$format = TE::GRAY."[".TE::AQUA."JrAdmin".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "SrMod"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."SrMod".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrMod_COL"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."SrMod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrMod_MEX"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."SrMod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrMod_ESP"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."SrMod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "SrMod_ARG"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."SrMod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Mod+"){
    		$format = TE::GRAY."[".TE::DARK_AQUA."Mod+".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}	
		if($player->getRank() === null||$player->getRank() === "Mod+_COL"){
    		$format = TE::GRAY."[".TE::DARK_AQUA."Mod+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod+_MEX"){
    		$format = TE::GRAY."[".TE::DARK_AQUA."Mod+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod+_ESP"){
    		$format = TE::GRAY."[".TE::DARK_AQUA."Mod+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod+_ARG"){
    		$format = TE::GRAY."[".TE::DARK_AQUA."Mod+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Mod"){
    		$format = TE::GRAY."[".TE::AQUA."Mod".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod_COL"){
    		$format = TE::GRAY."[".TE::AQUA."Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod_MEX"){
    		$format = TE::GRAY."[".TE::AQUA."Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod_ESP"){
    		$format = TE::GRAY."[".TE::AQUA."Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Mod_ARG"){
    		$format = TE::GRAY."[".TE::AQUA."Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Trial-Mod"){
    		$format = TE::GRAY."[".TE::YELLOW."Trial-Mod".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Trial-Mod_COL"){
    		$format = TE::GRAY."[".TE::YELLOW."Trial-Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Trial-Mod_MEX"){
    		$format = TE::GRAY."[".TE::YELLOW."Trial-Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Trial-Mod_ESP"){
    		$format = TE::GRAY."[".TE::YELLOW."Trial-Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Trial-Mod_ARG"){
    		$format = TE::GRAY."[".TE::YELLOW."Trial-Mod".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Partner"){
    	  $format = TE::GRAY ."[".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::LIGHT_PURPLE."Partner".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::GRAY."]".TE::LIGHT_PURPLE.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Partner_COL"){
    		$format = TE::GRAY."[".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::LIGHT_PURPLE."Partner".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Partner_MEX"){
    		$format = TE::GRAY."[".TE::WHITE."".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Partner_ESP"){
    		$format = TE::GRAY."[".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::LIGHT_PURPLE."Partner".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Partner_ARG"){
    		$format = TE::GRAY."[".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::LIGHT_PURPLE."Partner".TE::OBFUSCATED.TE::WHITE."!!".TE::RESET.TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Refys+"){
    	  $format = TE::GRAY."[".TE::LIGHT_PURPLE."Refys".TE::GOLD."+".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys+_COL"){
    		$format = TE::GRAY."[".TE::LIGHT_PURPLE."Refys".TE::GOLD."+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys+_MEX"){
    		$format = TE::GRAY."[".TE::LIGHT_PURPLE."Refys".TE::GOLD."+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys+_ESP"){
    		$format = TE::GRAY."[".TE::LIGHT_PURPLE."Refys".TE::GOLD."+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys+_ARG"){
    		$format = TE::GRAY."[".TE::LIGHT_PURPLE."Refys".TE::GOLD."+".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Refys"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Refys".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys_COL"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Refys".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys_MEX"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Refys".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys_ESP"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Refys".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Refys_ARG"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Refys".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Poison"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."Poison".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Poison_COL"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."Poison".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Poison_MEX"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."Poison".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Poison_ESP"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."Poison".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Poison_ARG"){
    		$format = TE::GRAY."[".TE::DARK_GREEN."Poison".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Forest"){
    		$format = TE::GRAY."[".TE::YELLOW."Forest".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Forest_COL"){
    		$format = TE::GRAY."[".TE::YELLOW."Forest".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Forest_MEX"){
    		$format = TE::GRAY."[".TE::YELLOW."Forest".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Forest_ESP"){
    		$format = TE::GRAY."[".TE::YELLOW."Forest".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Forest_ARG"){
    		$format = TE::GRAY."[".TE::YELLOW."Forest".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "MiniYT"){
    		$format = TE::GRAY."[".TE::WHITE."Mini".TE::RED."YT".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "MiniYT_COL"){
    		$format = TE::GRAY."[".TE::WHITE."Mini".TE::RED."YT".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "MiniYT_MEX"){
    		$format = TE::GRAY."[".TE::WHITE."Mini".TE::RED."YT".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "MiniYT_ESP"){
    		$format = TE::GRAY."[".TE::WHITE."Mini".TE::RED."YT".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "MiniYT_ARG"){
    		$format = TE::GRAY."[".TE::WHITE."Mini".TE::RED."YT".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "YouTuber"){
    		$format = TE::GRAY."[".TE::WHITE."You".TE::RED."Tuber".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "YouTuber_COL"){
    		$format = TE::GRAY."[".TE::WHITE."You".TE::RED."Tuber".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "YouTuber_MEX"){
    		$format = TE::GRAY."[".TE::WHITE."You".TE::RED."Tuber".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "YouTuber_ESP"){
    		$format = TE::GRAY."[".TE::WHITE."You".TE::RED."Tuber".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "YouTuber_ARG"){
    		$format = TE::GRAY."[".TE::WHITE."You".TE::RED."Tuber".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}


    	if($player->getRank() === null||$player->getRank() === "Famous"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Famous".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Famous_COL"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Famous".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Famous_MEX"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Famous".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Famous_ESP"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Famous".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Famous_ARG"){
    		$format = TE::GRAY."[".TE::DARK_PURPLE."Famous".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
    
    
    if($player->getRank() === null||$player->getRank() === "Booster"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Booster_COL"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Booster_MEX"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Booster_ESP"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "Booster_ARG"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
    
    
    if($player->getRank() === null||$player->getRank() === "BoosterCloned"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "BoosterCloned_COL"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "BoosterCloned_MEX"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "BoosterCloned_ESP"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::RED."E".TE::YELLOW."S".TE::RED."P".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
		if($player->getRank() === null||$player->getRank() === "BoosterCloned_ARG"){
    		$format = TE::GRAY."[".TE::BLUE."Booster".TE::LIGHT_PURPLE."Cloned".TE::GRAY."]".TE::GRAY."[".TE::BOLD.TE::AQUA."A".TE::WHITE."R".TE::AQUA."G".TE::RESET.TE::GRAY."] ".TE::GRAY.$player->getName().TE::WHITE;
    	}
    	if(Factions::inFaction($player->getName())){
			$factionName = Factions::getFaction($player->getName());
			$event->setFormat(TE::YELLOW."[".TE::LIGHT_PURPLE.$factionName.TE::YELLOW."]".TE::RESET.$format.": ".$event->getMessage(), true);
		}else{
			$event->setFormat($format.": ".$event->getMessage(), true);
		}
	}
}

?>
