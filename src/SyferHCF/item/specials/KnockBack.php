<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class KnockBack extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * KnockBack Constructor.
	 */
	public function __construct(){
		parent::__construct(self::BLAZE_ROD, "§l§aKnockback", [TE::GRAY."enchantment knockback 2 to ward off enemies or throw\n§7them from high places for whatever you wan.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::KNOCKBACK), 1));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 1;
    }
}

?>
