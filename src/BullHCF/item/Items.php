<?php

namespace BullHCF\item;

use BullHCF\item\specials\{Firework, StormBreaker, AntiTrapper, EggPorts, Strength, Resistance, Invisibility, PotionCounter, PrePearl};

use BullHCF\item\netherite\{Helmet, Chestplate, Leggings, Boots, Sword, Pickaxe};

use pocketmine\item\{Item, ItemFactory};

class Items {

	const NETHERITE_HELMET = 748, NETHERITE_CHESTPLATE = 749, NETHERITE_LEGGINGS = 750, NETHERITE_BOOTS = 751, NETHERITE_SWORD = 743, NETHERITE_PICKAXE = 745;
	
	/**
	 * @return void
	 */
	public static function init() : void {
		ItemFactory::registerItem(new EnderPearl(), true);
		ItemFactory::registerItem(new FishingRod(), true);
		ItemFactory::registerItem(new SplashPotion(), true);
		ItemFactory::registerItem(new GoldenApple(), true);
		ItemFactory::registerItem(new GoldenAppleEnchanted(), true);
		ItemFactory::registerItem(new EnderEye(), true);

		ItemFactory::registerItem(new Helmet(), true);
		ItemFactory::registerItem(new Chestplate(), true);
		ItemFactory::registerItem(new Leggings(), true);
		ItemFactory::registerItem(new Boots(), true);
		ItemFactory::registerItem(new Sword(), true);
		ItemFactory::registerItem(new Pickaxe(), true);
		
		ItemFactory::registerItem(new StormBreaker(), true);
		ItemFactory::registerItem(new AntiTrapper(), true);
		ItemFactory::registerItem(new EggPorts(), true);
		ItemFactory::registerItem(new Strength(), true);
		ItemFactory::registerItem(new Resistance(), true);
		ItemFactory::registerItem(new Invisibility(), true);
		ItemFactory::registerItem(new PotionCounter(), true);
		ItemFactory::registerItem(new Firework(), true);
	}

	/**
	 * @param Item $item
	 * @return Array[]
	 */
	public static function itemSerialize(Item $item) : Array {
		$data = $item->jsonSerialize();
		return $data;
	}

	/**
	 * @param Array $items
	 * @return Item
	 */
	public static function itemDeserialize(Array $items) : Item {
		$item = Item::jsonDeserialize($items);
		return $item;
	}
}

?>