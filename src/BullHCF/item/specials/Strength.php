<?php

namespace BullHCF\item\specials;

use BullHCF\Loader;
use BullHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class Strength extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Strength Constructor.
	 */
	public function __construct(){
		parent::__construct(self::BLAZE_POWDER, "Strength 2", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Can get Strength 2 for yourself"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 64;
    }
}

?>