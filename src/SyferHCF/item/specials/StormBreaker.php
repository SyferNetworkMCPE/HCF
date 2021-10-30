<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class StormBreaker extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	const USES_LEFT = "Uses left";
	
	/**
	 * StormBreaker Constructor.
	 * @param Int $usesLeft
	 */
	public function __construct(Int $usesLeft = 5){
		parent::__construct(self::GOLD_AXE, "§l§aStormBreaker", [TE::GRAY."remove the enemy's helmet after 3 seconds."."\n".TE::BOLD.TE::GOLD."Uses Left§r§f: ".TE::LIGHT_PURPLE.$usesLeft.TE::RESET."\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
		$this->getNamedTagEntry(self::CUSTOM_ITEM)->setInt(self::USES_LEFT, $usesLeft);
	}
	
	/**
	 * @param Player $player
	 * @return void
	 */
	public function reduceUses(Player $player) : void {
		$nbt = $this->getNamedTagEntry(self::CUSTOM_ITEM)->getInt(self::USES_LEFT);
		if($nbt > 0){
			$nbt--;
			if($nbt === 0){
				$player->getInventory()->setItemInHand(self::get(self::AIR));
			}else{
				$this->getNamedTagEntry(self::CUSTOM_ITEM)->setInt(self::USES_LEFT, $nbt);
				$this->setLore([TE::RESET."\n".TE::BOLD.TE::GOLD."Uses Left§r§f: ".TE::LIGHT_PURPLE.$nbt]);
				$player->getInventory()->setItemInHand($this);
			}
		}
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 1;
    }
}

?>
