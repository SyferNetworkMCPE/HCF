<?php

namespace SyferHCF\listeners\interact;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;
use SyferHCF\Task\ArcherTagTask;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\{ProjectileHitEvent, ProjectileHitEntityEvent, EntityDamageEvent, EntityDamageByEntityEvent};

use pocketmine\entity\projectile\Arrow;

class Archer implements Listener {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * Archer Constructor.
	 * @param Loader $plugin
	 */
	public function Archer__construct(Loader $plugin){
		$this->plugin = $plugin;
    }
    
    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
    public function onProjectileHitEvent(ProjectileHitEvent $event) : void {
        $entity = $event->getEntity();
        if($entity instanceof Arrow){
            $damager = $entity->getOwningEntity();
            if($damager instanceof Player && $event instanceof ProjectileHitEntityEvent && $damager->isArcherClass()){
                $player = $event->getEntityHit();
                if($player instanceof Player){
                    if(!Factions::isSpawnRegion($damager) && !Factions::isSpawnRegion($player)){
                        if(!$player->isArcherTag()){
                            if($player->getName() === $damager->getName()){
                                return;
                            }
                            if(Factions::inFaction($damager->getName()) && Factions::inFaction($player->getName()) && Factions::getFaction($damager->getName()) === Factions::getFaction($player->getName())){
                                return;
                            }
                            $damager->sendMessage(str_replace(["&", "{playerName}", "{playerHealth}"], ["§", $player->getName(), $player->getHealth()], Loader::getConfiguration("messages")->get("player_archer_tag_target")));
                            Loader::$mark[$player->getName()] = time() + Loader::getDefaultConfig("Cooldowns")["ArcherMark"];

                            Loader::getInstance()()->scheduleRepeatingTask(new ArcherTagTask($player), 20);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($player instanceof Player and $damager instanceof Player){
                if($player->isArcherTag()){
                    $baseDamage = $event->getBaseDamage();
                    $event->setBaseDamage($baseDamage + 2.0);
                }
            }
        }
    }
	
	/**
     * @param PlayerInteractEvent $event
     * @return void
     */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		if($player->isArcherClass()){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR||$event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
				switch($event->getItem()->getId()){
					case ItemIds::SUGAR:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getArcherEnergy() < $player->getBardEnergyCost($event->getItem()->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getArcherEnergy(), $player->getBardEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("player_not_enough_energy")));
							return;
						}
						$player->setArcherEnergy($player->getArcherEnergy() - $player->getBardEnergyCost($event->getItem()->getId()));
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10, 3));
						$event->getItem()->setCount($event->getItem()->getCount() - 1);
                        $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
					break;
					case ItemIds::FEATHER:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getArcherEnergy() < $player->getBardEnergyCost($event->getItem()->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getArcherEnergy(), $player->getBardEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("player_not_enough_energy")));
							return;
						}
						$player->setArcherEnergy($player->getArcherEnergy() - $player->getBardEnergyCost($event->getItem()->getId()));
						$player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 10, 3));
						$event->getItem()->setCount($event->getItem()->getCount() - 1);
                        $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
					break;
				}
			}
		}
	}
}

?>
