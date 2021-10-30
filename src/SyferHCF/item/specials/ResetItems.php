<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class ResetItems extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * ResetItems Constructor.
	 */
	public function __construct(){
		parent::__construct(self::DIAMOND, "§l§aResetItems", [TE::GRAY."using this item will remove the cooldown from\n§7specialitems, not all cooldowns\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 1;
    }
}

?>
