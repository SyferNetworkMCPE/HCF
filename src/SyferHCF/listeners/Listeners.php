<?php

namespace SyferHCF\listeners;

use SyferHCF\{Loader, EventListener};

use SyferHCF\crate\CrateListener;
use SyferHCF\level\LevelListener;
use SyferHCF\listeners\block\{BlockBreak, BlockPlace};

use SyferHCF\listeners\interact\{OpBard, OpArcher, OpRogue, Bard, Archer, Crowbar, Shop, Gapple, Rogue, Elevators, Mage};

use SyferHCF\listeners\event\{SOTW, EOTW, Invincibility, KEYALL, PP, AIRDROP, EVENT, PURGUE, FFA};
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
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new KB(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Claim(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Death(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Logout(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Spawn(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Portal(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new SpecialItems(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new StaffMode(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Inventory(), Loader::getInstance());
        
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new BlockBreak(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new BlockPlace(), Loader::getInstance());
        
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new CrateListener(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new LevelListener(), Loader::getInstance());
        
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Bard(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new OpBard(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new OpArcher(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new OpRogue(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Archer(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Shop(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Gapple(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Crowbar(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Rogue(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Mage(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Elevators(), Loader::getInstance());

        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new SOTW(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new EOTW(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new KEYALL(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new Invincibility(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new PP(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new AIRDROP(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new EVENT(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new PURGUE(), Loader::getInstance());
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new FFA(), Loader::getInstance());
    }
}

?>
