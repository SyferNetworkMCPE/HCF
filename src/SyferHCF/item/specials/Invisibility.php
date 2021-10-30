<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class Invisibility extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Invisibility Constructor.
	 */
	public function __construct(){
		parent::__construct(self::SUGAR, "§l§aInvisibility", [TE::GRAY."If you use this item you will receive invisibility\n§7for 3 minutes, it does not hide armor\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
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
