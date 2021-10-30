<?php

namespace SyferHCF\listeners;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use Advanced\Data\PlayerBase;

use SyferHCF\entities\spawnable\Villager;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class Logout implements Listener {
	
	/**
	 * Logout Constructor.
	 */
	public function __construct(){

	}
	
	/**
	 * @param PlayerQuitEvent $event
	 * @return void
	 */
	public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {
		$player = $event->getPlayer();
		if($player->isCombatTag()||!$player->isLogout()){
			if(Factions::isSpawnRegion($player)) return;
			if(class_exists("PlayerBase")){
				if(PlayerBase::isStaff($player)) return;
			}
			
			$nbt = Entity::createBaseNBT($player, null, $player->yaw, $player->pitch);
			$villager = new Villager($player->getLevel(), $nbt, $player);
			$villager->handleData($player);
			$player->getLevel()->addEntity($villager);
			$villager->spawnToAll();
		}
	}
}

?>
