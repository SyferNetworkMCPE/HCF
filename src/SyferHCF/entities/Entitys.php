<?php

namespace SyferHCF\entities;

use SyferHCF\Loader;

use SyferHCF\entities\spawnable\{Cow, Enderman, Villager, Pig, Creeper, Turtle, Horse};

use pocketmine\entity\Entity;
use pocketmine\tile\Tile;

class Entitys {
	
	/**
	 * @return void
	 */
	public static function init() : void {
		Entity::registerEntity(Cow::class, true, ["Cow"]);
		Entity::registerEntity(Enderman::class, true, ["Enderman"]);
		Entity::registerEntity(Villager::class, true, ["Villager"]);
		Entity::registerEntity(Pig::class, true, ["Pig"]);
		Entity::registerEntity(Creeper::class, true, ["Creeper"]);
		Entity::registerEntity(Turtle::class, true, ["Turtle"]);
		Entity::registerEntity(Horse::class, true, ["Horse"]);
			
		Entity::registerEntity(EnderPearl::class, true, ["EnderPearl"]);
        Entity::registerEntity(Egg::class, true, ["Egg"]);
        Entity::registerEntity(Snowball::class, true, ["Snowball"]);
		Entity::registerEntity(SplashPotion::class, true, ["SplashPotion"]);
		Entity::registerEntity(Hook::class, false, ["FishingHook", "minecraft:fishing_rod"]);
	}
	
	/**
	 * @return Array[]
	 */
	public static function getEntitysType() : Array {
		return Loader::getDefaultConfig("Entitys");
	}
}

?>
