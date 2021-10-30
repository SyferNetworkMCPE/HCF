<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class Firework extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * Firework constructor.
     */
    public function __construct(){
        parent::__construct(self::FIREWORKS, "§l§aFirework", [TE::GRAY."use the item and rise to the sky with power\n§7you will have the fall damage ever\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
        $this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
    }
}

?>
