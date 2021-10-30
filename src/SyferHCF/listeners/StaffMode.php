<?php

namespace SyferHCF\Listeners;

use SyferHCF\Loader;
use SyferHCF\utils\Data;
use SyferHCF\utils\Time;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\item\{Item, ItemIds};

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;

use pocketmine\event\player\{PlayerPreLoginEvent, PlayerInteractEvent, PlayerMoveEvent, PlayerChatEvent, PlayerCommandPreprocessEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\event\block\{BlockPlaceEvent, BlockBreakEvent};
use pocketmine\Server;

class StaffMode implements Listener {

    /** @var Loader */
    private $os = [];
    private $device = [];
    
    private $listOfOs = ["Unknown", "Android", "iOS", "macOS", "FireOS", "GearVR", "HoloLens", "Windows10", "Windows", "EducalVersion","Dedicated", "PlayStation4", "Switch", "XboxOne"];
    private $spam = array();
    
    /**
     * StaffMode Constructor.
     * @param Loader $plugin
     */
     
    public function __construct(){
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $direction = $event->getPlayer()->getDirectionVector()->multiply(4);
        if($event->getPlayer()->getInventory()->getItemInHand()->getId() === ItemIds::COMPASS and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            $event->getPlayer()->teleport(Position::fromObject($event->getPlayer()->add($direction->getX(), $direction->getY(), $direction->getZ()), $event->getPlayer()->getLevel()));
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        if($event->getPlayer()->getInventory()->getItemInHand()->getId() === ItemIds::PACKED_ICE and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerPreLoginEvent $event
     * @return void
     */
    public function onPlayerPreLoginEvent(PlayerPreLoginEvent $event) : void {
        $playerName = $event->getPlayer()->getName();
        if(Data::isPermanentlyBanned($playerName)){
            $config = new Config(Loader::getInstance()->getDataFolder()."players_banneds.yml", Config::YAML);
			$result = $config->get($playerName);
            $event->getPlayer()->close("", TE::BOLD.TE::LIGHT_PURPLE."You were banned from the network permanently".TE::RESET."\n".TE::GRAY."You were banned by: ".TE::DARK_PURPLE.$result["sender_name"].TE::RESET."\n".TE::GRAY."Reason: ".TE::LIGHT_PURPLE.$result["reason_of_ban"].TE::RESET."\n".TE::BLUE.TE::BOLD."Discord: ".TE::RESET.TE::AQUA."https://discord.gg/S5N6YaY%22");
        }
        if(Data::isTemporarilyBanned($playerName)){
            $config = new Config(Loader::getInstance()->getDataFolder()."players_timebanneds.yml", Config::YAML);
			$result = $config->get($playerName);
            if($result["time_ban"] > time()){
                $event->getPlayer()->close("", TE::BOLD.TE::LIGHT_PURPLE."You were banned from the network temporarily".TE::RESET."\n".TE::GRAY."You were banned by: ".TE::DARK_PURPLE.$result["sender_name"].TE::RESET."\n".TE::GRAY."Reason: ".TE::LIGHT_PURPLE.$result["reason_of_ban"].TE::RESET."\n".TE::GRAY."Time left: ".TE::GREEN.Loader::getTime($result["time_ban"]).TE::RESET."\n".TE::BLUE.TE::BOLD."Discord: ".TE::RESET.TE::AQUA."https://discord.gg/S5N6YaY%22");
            }else{
                Data::deleteBan($playerName, false);
            }
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR and isset(Loader::$staffmode[$event->getPlayer()->getName()])){
            if($event->getItem()->getId() === ItemIds::CLOCK){
                $players = [];
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                    $players[] = $player;
                }
                $event->getPlayer()->teleport($players[array_rand($players)]);
            }
            if($event->getItem()->getId() === ItemIds::DYE and $event->getItem()->getDamage() === 10){
                $event->getPlayer()->getInventory()->setItemInHand(Item::get(ItemIds::DYE, 1, 1)->setCustomName(TE::RED."Disable Vanish"));
                $event->getPlayer()->sendMessage(TE::GREEN."Vanish was activated!");
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                    $player->hidePlayer($event->getPlayer());
                }
            }
            if($event->getItem()->getId() === ItemIds::DYE and $event->getItem()->getDamage() === 1){
                $event->getPlayer()->getInventory()->setItemInHand(Item::get(ItemIds::DYE, 10, 1)->setCustomName(TE::GREEN."Enable Vanish"));
                $event->getPlayer()->sendMessage(TE::RED."Vanish was desactivated!");
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                    $player->showPlayer($event->getPlayer());
                }
            }
        }
    }

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChatEvent(PlayerChatEvent $event) : void {
        $playerName = $event->getPlayer()->getName();
        if($event->getPlayer()->hasPermission("expire.chat.command")) return;
        if(isset($this->spam[$playerName])){
        	if((time() - $this->spam[$playerName]) < 10){
        		$time = time() - $this->spam[$playerName];
        		$event->getPlayer()->sendMessage(TE::DARK_PURPLE."You have to wait ".Loader::getTimeToString(10 - $time)." to write in the chat again!");
        		$event->setCancelled(true);
        	}else{
        		$this->spam[$playerName] = time();
        	}
        }else{
        	$this->spam[$playerName] = time();
        }
    }

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function onPlayerMoveEvent(PlayerMoveEvent $event) : void {
        if(isset(Loader::$freeze[$event->getPlayer()->getName()])){
            $event->getPlayer()->addTitle(TE::LIGHT_PURPLE."YOU ARE FROZEN");
            $event->setCancelled(true);
        }
    }
    
    /**
     * @param PlayerCommandPreprocessEvent $event
     * @return void
     */
    public function onPlayerCommandPreprocessEvent(PlayerCommandPreprocessEvent $event) : void {
    	$command = explode(" ", $event->getMessage());
    	foreach(Loader::getDataConfig("commands_block") as $block){
    		if($command[0] === "/".$block||$command[0] === "./".$block){
    			$event->setCancelled(true);
    		}
    	}
    }
    
    /**
     * @param DataPacketReceiveEvent $event
     * @return void
     */
    public function onDataPacketReceiveEvent(DataPacketReceiveEvent $event) : void {
    	$player = $event->getPlayer();
    	$packet = $event->getPacket();
    	if($packet instanceof LoginPacket && $player instanceof Player){
    		Loader::$device[$packet->username] = $packet->clientData["DeviceOS"];
    	}
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if ($entity instanceof Player && $damager instanceof Player) {
                if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK and $damager->getInventory()->getItemInHand()->getId() === ItemIds::PACKED_ICE and isset(Loader::$staffmode[$damager->getName()])){
                    $event->setCancelled(true);
                    if(!isset(Loader::$freeze[$entity->getName()])){
                        Loader::$freeze[$entity->getName()] = $entity;
                        Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$entity->getName().TE::RESET.TE::GRAY." was frozen by ".TE::BOLD.TE::YELLOW.$damager->getName());
                    }else{
                        unset(Loader::$freeze[$entity->getName()]);
                        Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::GOLD.$entity->getName().TE::RESET.TE::GRAY." was unfrozen by ".TE::BOLD.TE::YELLOW.$damager->getName());
                    }
                } elseif ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK and $damager->getInventory()->getItemInHand()->getId() === ItemIds::SKULL and isset(Loader::$staffmode[$damager->getName()])) {
                    $damager->sendMessage("§l --- §a§lPlayer Info§f --- ");
                    $damager->sendMessage("§d§lName§r§7:§f {$entity->getName()}");
                    $damager->sendMessage("§d§lXUID§r§7:§f {$entity->getXuid()}");
                    $damager->sendMessage("§d§lHealth§r§7:§f {$entity->getHealth()}");
                    $damager->sendMessage("§d§lPing§r§7:§f {$entity->getPing()}");
                    $damager->sendMessage("§d§lPosition§r§7:§f {$entity->getX()} - {$entity->getY()} - {$entity->getZ()}");
                    $damager->sendMessage("§d§lDeviceOS§r§7:§f {$this->getPlayerOs($entity)}");
                } elseif ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK and $damager->getInventory()->getItemInHand()->getId() === ItemIds::BOOK and isset(Loader::$staffmode[$damager->getName()])) {
                    Server::getInstance()->dispatchCommand($damager, "invsee {$player}");
                }
            }
        }
    }

    public function onPacketReceived(DataPacketReceiveEvent $e){
        if($e->getPacket() instanceof LoginPacket){
            //Is the line below useless?
            if($e->getPacket()->clientData["DeviceOS"] !== null){
                $this->os[strtolower($e->getPacket()->username) ?? "unavailable"] = $e->getPacket()->clientData["DeviceOS"];
                $this->device[strtolower($e->getPacket()->username) ?? "unavailable"] = $e->getPacket()->clientData["DeviceModel"];
            }
        }
    }

    public function getPlayerOs(Player $player) : ?string{
        $name = strtolower($player->getName());
        if(!isset($this->os[$name]) OR $this->os[$name] == null) return null;
        return $this->listOfOs[$this->os[$name]];
    }
}
?>
