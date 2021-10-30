<?php

namespace SyferHCF\listeners\interact;

use SyferHCF\Loader;
use SyferHCF\player\Player;
use SyferHCF\Task\OpRogueTask;

use pocketmine\event\Listener;

use pocketmine\item\Item;

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\event\player\{PlayerMoveEvent, PlayerDeathEvent};


class OpRogue implements Listener {
  
  protected $plugin;
  
  public function OpRogue__construct(Loader $plugin){
    $this->plugin = $plugin;
  }
  
  public function onDamage(EntityDamageEvent $event){
  	$player = $event->getEntity();
      if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if($entity instanceof Player && $damager instanceof Player){
               if($damager->isOpRogueClass() && $player){
                  if($damager->getInventory()->getItemInHand()->getId() == Item::GOLD_SWORD){
                    if(!isset(Loader::$oprogue[$damager->getName()])){
                     $damager->getInventory()->setItemInHand(Item::get(Item::AIR));
                     $heart = $entity->getHealth();
                     $damage = mt_rand(3, 5);
                     $entity->setHealth($heart - $damage);
                     Loader::$oprogue[$damager->getName()] = time() + Loader::getDefaultConfig("Cooldowns")["OPROGUE_DELAY"];
                    }else if(time() < Loader::$oprogue[$damager->getName()]){
                      return;
                    }else{
                      unset(Loader::$oprogue[$damager->getName()]);
                    }
                  }
                }
            }
        }
    }
}
