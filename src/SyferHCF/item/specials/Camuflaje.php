<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class Camuflaje extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Camuflaje Constructor.
	 */
	public function __construct(){
		parent::__construct(self::COAL, "§l§6Camuflaje", [TE::GRAY."invisibility armor for yourself".TE::RESET."\n\n".TE::DARK_PURPLE."Available in our Store §dsyferhcf.tebex.io"]);
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
