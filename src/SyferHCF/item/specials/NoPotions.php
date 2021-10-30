<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class NoPotions extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * NoPotions Constructor.
	 */
	public function __construct(){
		parent::__construct(self::REDSTONE, "§l§aNoPotions", [TE::GRAY."gives you high level regeneration effect almost does not\n§7lower your life you can save potions.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 64;
    }
}

?>
