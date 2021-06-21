<?php

namespace BullHCF;

use BullHCF\{Loader, Factions};
use BullHCF\player\{Player, PlayerBase};

use BullHCF\Task\asynctask\{SavePlayerData};

use BullHCF\Task\Scoreboard;

use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TE;
use pocketmine\level\biome\Biome;

use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent, PlayerMoveEvent, PlayerInteractEvent};
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;

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
        $event->setJoinMessage(TE::GRAY."[".TE::GREEN."+".TE::GRAY."] ".TE::GREEN.$player->getName().TE::GRAY." entered the server!");
        
        PlayerBase::create($player->getName());
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new Scoreboard($player), 20);
    }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
		$event->setQuitMessage(TE::GRAY."[".TE::RED."-".TE::GRAY."] ".TE::RED.$player->getName().TE::GRAY." left the server!");

        Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new SavePlayerData($player->getName(), $player->getUniqueId()->toString(), $player->getClientId(), $player->getCountry(), $player->getAddress(), Factions::inFaction($player->getName()) ? Factions::getFaction($player->getName()) : "This player not have faction", Loader::getDefaultConfig("MySQL")["hostname"], Loader::getDefaultConfig("MySQL")["username"], Loader::getDefaultConfig("MySQL")["password"], Loader::getDefaultConfig("MySQL")["database"], Loader::getDefaultConfig("MySQL")["port"]));
	}
	
	/**
     * @param EntityLevelChangeEvent $event
     * @return void
     */
	public function onEntityLevelChangeEvent(EntityLevelChangeEvent $event) : void {
		$player = $event->getEntity();
		$player->showCoordinates();
	}

}

?>