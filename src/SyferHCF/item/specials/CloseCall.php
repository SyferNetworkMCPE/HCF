<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class CloseCall extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * CloseCall Constructor.
	 */
	
	public function __construct(){
		parent::__construct(self::COOKIE, "§l§aClose Call", [TE::GRAY."when using this item you will receive strength 2 and regeneration 5\n§7for 15 seconds to eliminate the enemy.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
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
