<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\commands\moderation\{LoreCommand, Gamemode, AddBalanceCommand, ReduceBalanceCommand, TopKillsCommand, RoollbackCommand, GeCommand, NoticeCommand, TpaCommand, CrateCommand, GodCommand, SpecialItemsCommand, SpawnCommand, ClearEntitysCommand, EnchantCommand, RemoveEffects, PackagesCommand, RanksCommand};
use SyferHCF\commands\staff\{
	BanCommand,
	UnBanCommand,
	OpCommand,
	GipCommand,
	ReportCommand,
	ClearInvCommand,
	KickCommand,
	StaffChatCommand,
	MuteCommand,
	UnMuteCommand,
	PingCommand,
	TimeBanCommand,
	TimeMuteCommand,
	HistoryCommand,
	StaffModeCommand,
	HelpStaffCommand,
	TellCommand,
	MessageCommand,
	WCommand,
	WarnCommand,
	InfoPlayerCommand,
	BanListCommand};
	
use SyferHCF\commands\events\{SOTWCommand, EOTWCommand, KothCommand, CitadelCommand, KEYALLCommand, PPCommand, AIRDROPCommand, EVENTCommand, PURGUECommand, FFACommand};
use pocketmine\utils\TextFormat as TE;
use pocketmine\Server;

class Commands {

    /**
     * @return void
     */
    public static function init() : void {
        Loader::getInstance()->getServer()->getCommandMap()->register("/clearentitys", new ClearEntitysCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/rb", new RoollbackCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/ffaeffects", new GeCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/tpa", new TpaCommand()); 
        Loader::getInstance()->getServer()->getCommandMap()->register("/cr", new CrateCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/god", new GodCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/specialitems", new SpecialItemsCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/spawn", new SpawnCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/addbalance", new AddBalanceCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/reducebalance", new ReduceBalanceCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/lore", new LoreCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/enchant", new EnchantCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/package", new PackagesCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/gamemode", new Gamemode());
        Loader::getInstance()->getServer()->getCommandMap()->register("/notice", new NoticeCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/topkill", new TopKillsCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/ranks", new RanksCommand());
        
		Loader::getInstance()->getServer()->getCommandMap()->register("/f", new FactionCommand());
		Loader::getInstance()->getServer()->getCommandMap()->register("/gkit", new GkitCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/reclaim", new ReclaimCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/brewer", new BrewerCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/near", new NearCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/money", new MoneyCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/enderchest", new EnderChestCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/pay", new PayCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/players", new OnlinePlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/lff", new LFFCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/recruit", new RecruitCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/info", new InfoCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/tl", new LocationCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/feed", new FeedCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/items", new ItemsCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/kills", new KillsCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/fix", new FixCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/rename", new RenameCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/logout", new LogoutCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/pvp", new PvPCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/nick", new NickCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/autofeed", new AutoFeedCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/craft", new CraftCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/endplayers", new EndPlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/netherplayers", new NetherPlayersCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/cenchantments", new CustomEnchantmentsCommand());
        
        Loader::getInstance()->getServer()->getCommandMap()->register("/sotw", new SOTWCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/eotw", new EOTWCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/koth", new KothCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/citadel", new CitadelCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/keyall", new KEYALLCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/packageall", new PPCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/airdropall", new AIRDROPCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/event", new EVENTCommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/purgue", new PURGUECommand());
        Loader::getInstance()->getServer()->getCommandMap()->register("/ffa", new FFACommand());
        
        Loader::getInstance()->getServer()->getCommandMap()->register("/ban", new BanCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/unban", new UnBanCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/op", new OpCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/gip", new GipCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/report", new ReportCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/clearinv", new ClearInvCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/kick", new KickCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/sc", new StaffChatCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/ping", new PingCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/mute", new MuteCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/unmute", new UnMuteCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/history", new HistoryCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/tban", new TimeBanCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/tmute", new TimeMuteCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/mod", new StaffModeCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/banlist", new BanListCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/tell", new TellCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/msg", new MessageCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/w", new WCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/warn", new WarnCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/co", new InfoPlayerCommand(Loader::getInstance()));
		Loader::getInstance()->getServer()->getCommandMap()->register("/request", new HelpStaffCommand(Loader::getInstance()));

    }
}

?>
