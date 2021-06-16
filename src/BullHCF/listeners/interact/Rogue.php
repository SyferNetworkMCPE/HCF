<?php

namespace BullHCF\listeners\interact;

use BullHCF\{Loader, Factions};
use BullHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class Rogue implements Listener {
	
	/**
	 * Rogue Constructor.
	 */
	public function __construct(){

    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($player instanceof Player && $damager instanceof Player){
                if($damager->isRogueClass() && $damager->getInventory()->getItemInHand()->getId() === ItemIds::GOLD_SWORD){
                    
                    if(Factions::inFaction($damager->getName()) && Factions::inFaction($player->getName()) && Factions::getFaction($damager->getName()) === Factions::getFaction($player->getName())) return;
                    
                    if(Factions::isSpawnRegion($player)||Factions::isSpawnRegion($damager)||$damager->isInvincibility()||$player->isInvincibility()) return;
                    //We subtract 4 hearts for the blow with the gold sword
                    $player->setHealth($player->getHealth() - 4);
                    
                    $damager->getInventory()->setItemInHand(Item::get(Item::AIR));
                    $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 3 * 10, 3));
                    $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS ), 3 * 10, 3));
                    $damager->sendMessage(str_replace(["&", "{playerName}", "{playerHealth}"], ["§", $player->getName(), $player->getHealth()], Loader::getConfiguration("messages")->get("rogue_made_backstab_target")));
                }
            }
        }
    }
}

?>