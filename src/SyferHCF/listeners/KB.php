<?php

namespace SyferHCF\listeners;

use SyferHCF\Loader;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\Player;
use pocketmine\Server;

class KB implements Listener {
  
  protected  $plugin;
  
  public function __construct(){
    
  }
  public function onDamage(EntityDamageByEntityEvent $event) {
		$player = $event->getEntity();
		if($event instanceof EntityDamageByEntityEvent) {
			if($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
						$event->setKnockBack(0.300);
			}
		}
  }
}
