<?php

namespace BullHCF\commands;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\commands\moderation\{KitCommand, PexCommand, RoollbackCommand, GlobalEffects, TpaCommand, CrateCommand, GodCommand, SpecialItemsCommand, SpawnCommand, ClearEntitysCommand, EnchantCommand};
use BullHCF\commands\events\{SOTWCommand, EOTWCommand, KothCommand};
use pocketmine\utils\TextFormat as TE;

class Commands {

    /**
     * @return void
     */
    public static function init() : void {
        Loader::getInstance()->getServer()->getCommandMap()->register("/clearentitys", new ClearEntitysCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/kit", new KitCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/rb", new RoollbackCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/geffects", new GlobalEffects());
        Loader::getInstance()->getServer()->getCommandMap()->register("/tpa", new TpaCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/crates", new CrateCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/god", new GodCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/items", new SpecialItemsCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/spawn", new SpawnCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/enchant", new EnchantCommand());

		Loader::getInstance()->getServer()->getCommandMap()->register("/f", new FactionCommand());
		Loader::getInstance()->getServer()->getCommandMap()->register("/gkit", new GkitCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/reclaim", new ReclaimCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/brewer", new BrewerCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/enderchest", new EnderChestCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/near", new NearCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/money", new MoneyCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/pay", new PayCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/players", new OnlinePlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/tl", new LocationCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/feed", new FeedCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/fix", new FixCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/craft", new CraftCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/rename", new RenameCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/logout", new LogoutCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/pvp", new PvPCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/autofeed", new AutoFeedCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/endplayers", new EndPlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/netherplayers", new NetherPlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/cenchantments", new CEnchantmentsCommand());
        
        Loader::getInstance()->getServer()->getCommandMap()->register("/sotw", new SOTWCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/eotw", new EOTWCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/koth", new KothCommand());

    }
}

?>