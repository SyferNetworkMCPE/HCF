<?php

namespace SyferHCF\item;

use SyferHCF\item\specials\{Firework,
    SecondChance,
    RemoveEffects,
    StormBreaker,
    KnockBack,
    AntiTrapper,
    EggPorts,
    Strength,
    ResetItems,
    Camuflaje,
    RageBall,
    Resistance,
    UnBan,
    Rank,
    NinjaShear,
    Refill,
    Cocaine,
    Invisibility,
    NoPotions,
    CloseCall,
    AntiFall,
    PotionCounter,
    LoggerBait,
    EffectsBard,
    RareBrick,
    PartnerPackages,
};

use SyferHCF\item\netherite\{Helmet, Chestplate, Leggings, Boots, Sword, Pickaxe};

use pocketmine\item\{Item, ItemFactory};

class Items {

	const NETHERITE_HELMET = 748, NETHERITE_CHESTPLATE = 749, NETHERITE_LEGGINGS = 750, NETHERITE_BOOTS = 751, NETHERITE_SWORD = 743, NETHERITE_PICKAXE = 745;
	
	/**
	 * @return void
	 */
	public static function init() : void {
		ItemFactory::registerItem(new EnderPearl(), true);
		ItemFactory::registerItem(new Rod(), true);
		ItemFactory::registerItem(new SplashPotion(), true);
		ItemFactory::registerItem(new GoldenApple(), true);
		ItemFactory::registerItem(new GoldenAppleEnchanted(), true);
		ItemFactory::registerItem(new EnderEye(), true);
		ItemFactory::registerItem(new FlintSteel(), true);
		ItemFactory::registerItem(new GlassBottle(), true);

		ItemFactory::registerItem(new Helmet(), true);
		ItemFactory::registerItem(new Chestplate(), true);
		ItemFactory::registerItem(new Leggings(), true);
		ItemFactory::registerItem(new Boots(), true);
		ItemFactory::registerItem(new Sword(), true);
		ItemFactory::registerItem(new Pickaxe(), true);
		
		ItemFactory::registerItem(new StormBreaker(), true);
		ItemFactory::registerItem(new SecondChance(), true);
		ItemFactory::registerItem(new AntiTrapper(), true);
		ItemFactory::registerItem(new EggPorts(), true);
		ItemFactory::registerItem(new NoPotions(), true);
		ItemFactory::registerItem(new Strength(), true);
		ItemFactory::registerItem(new Resistance(), true);
		ItemFactory::registerItem(new ResetItems(), true);
		ItemFactory::registerItem(new Resistance(), true);
		ItemFactory::registerItem(new Strength(), true);
		ItemFactory::registerItem(new Invisibility(), true);
		ItemFactory::registerItem(new Cocaine(), true);
		ItemFactory::registerItem(new CloseCall(), true);
		ItemFactory::registerItem(new RemoveEffects(), true);
		ItemFactory::registerItem(new Camuflaje(), true);
		ItemFactory::registerItem(new knockback(), true);
		ItemFactory::registerItem(new PotionCounter(), true);
		ItemFactory::registerItem(new Firework(), true);
		ItemFactory::registerItem(new RageBall(), true);
		ItemFactory::registerItem(new NinjaShear(), true);
        ItemFactory::registerItem(new EffectsBard(), true);
        ItemFactory::registerItem(new PartnerPackages(), true);
        ItemFactory::registerItem(new LoggerBait(), true);
        ItemFactory::registerItem(new Refill(), true);
        ItemFactory::registerItem(new AntiFall(), true);
        ItemFactory::registerItem(new UnBan(), true);
        ItemFactory::registerItem(new Rank(), true);
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
