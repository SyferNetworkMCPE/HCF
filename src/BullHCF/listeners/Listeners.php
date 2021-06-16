<?php

namespace BullHCF\listeners;

use BullHCF\{Loader, EventListener};

use BullHCF\listeners\block\{BlockBreak, BlockPlace};

use BullHCF\listeners\interact\{Bard, Archer, Shop, Gapple, Rogue, Elevators};

use BullHCF\listeners\event\{SOTW, EOTW, Invincibility, GiftChest, DeathBan};
use pocketmine\utils\TextFormat as TE;

class Listeners {

    /**
     * @return void
     */
    public static function init() : void {
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new EventListener(), Loader::getInstance());

        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new CombatTag(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new EnderPearl(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Faction(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Claim(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Death(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Logout(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Spawn(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Crates(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new SpecialItems(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Inventory(), Loader::getInstance());
        
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new BlockBreak(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new BlockPlace(), Loader::getInstance());
        
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Bard(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Archer(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Shop(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Gapple(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Rogue(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Elevators(), Loader::getInstance());

        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new SOTW(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new EOTW(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Invincibility(), Loader::getInstance());
    }
}

?>