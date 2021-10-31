<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class InfoCommand extends PluginCommand {
	
	/**
	 * Info Constructor.
	 */
	public function __construct(){
		parent::__construct("info", Loader::getInstance());
		parent::setDescription("info the map");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$sender->sendMessage(TE::BOLD.TE::LIGHT_PURPLE."SyferHCF".TE::RESET.TE::GRAY." | ".TE::WHITE."Info Map");
		$sender->sendMessage(TE::BOLD.TE::GOLD."Border".TE::RESET.TE::WHITE.": ".TE::RED."2000x2000");
		$sender->sendMessage(TE::BOLD.TE::GOLD."§6§lFaction Size§r§f: §r§c10 Man§7, §cNo Allies\n§7§l§6Enchant Info§r§f:§c Sharp 2, Prot 2, Power 4\n§7");
	}
}

?>
