<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class Rank extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Rank Constructor.
	 */
	public function __construct(){
		parent::__construct(self::PAPER, "§l§bRANK §r§7(§dx1 Map§7)", [TE::BOLD.TE::LIGHT_PURPLE."RANK TEMPORARY".TE::RESET."\n".TE::GRAY."You can get this free rank obviously it will be temporary".TE::RESET."\n".TE::GRAY."the time of this rank will only be for the map where you got it".TE::RESET."\n\n".TE::DARK_PURPLE."Avaiable exclusively at §dCitadel Crate"]);
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
