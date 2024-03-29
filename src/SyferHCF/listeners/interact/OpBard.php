<?php

namespace SyferHCF\listeners\interact;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use SyferHCF\utils\Translator;

use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\event\Listener;
use pocketmine\item\{Item, ItemIds};

use pocketmine\event\player\{PlayerInteractEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class OpBard implements Listener {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * OpBard Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		$item = $event->getItem();
		if($player->isOpBardClass()){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR||$event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
				switch($item->getId()){
					case ItemIds::SUGAR:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10, 2);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));


                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
                           	    	$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::IRON_INGOT:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 20 * 10, 2);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
						$player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

						$item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::BLAZE_POWDER:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 1);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::GHAST_TEAR:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::REGENERATION), 15 * 10, 2);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::FEATHER:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 10, 5);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::DYE:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 30 * 10, 0);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::MAGMA_CREAM:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 50 * 50, 1);

						$player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						$player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                        if(Factions::inFaction($player->getName())){
                        	foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                           	 $online = Loader::getInstance()->getServer()->getPlayer($value);
                        	    if($online instanceof Player && $online->distanceSquared($player) < 250){
                           	     	$online->addEffect($effect);
									$online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
                            	}
                            }
                        }
					break;
					case ItemIds::SPIDER_EYE:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getOpBardEnergy() < $player->getOpBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getOpBardEnergy(), $player->getOpBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("OpBard_not_enough_energy")));
							return;
						}
						$effect = new EffectInstance(Effect::getEffect(Effect::WITHER), 20 * 7, 1);

						foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $online){
							if($online->distanceSquared($player) < 250){
							    $online->addEffect($effect);
                                $online->sendMessage(str_replace(["&", "{playerName}", "{effectName}", "{effectLevel}"], ["§", $player->getName(), Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_global_give_effects")));
							}
						}
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("OpBard_give_effects")));

                        $player->setOpBardEnergy($player->getOpBardEnergy() - $player->getOpBardEnergyCost($item->getId()));
						
						$item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));                
					break;
                }
			}
		}
	}
}

?>
