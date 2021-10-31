<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class OnlinePlayersCommand extends PluginCommand {
	
	/**
	 * OnlinePlayers Constructor.
	 */
	public function __construct(){
		parent::__construct("players", Loader::getInstance());
		parent::setDescription("show all onlineplayers the server");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$sender->sendMessage(TE::GRAY."----------------------------");
		$sender->sendMessage(TE::BOLD.TE::LIGHT_PURPLE."Online Players§r§f: ".TE::RESET.TE::GOLD.count(Loader::getInstance()->getServer()->getOnlinePlayers()));
		$sender->sendMessage(TE::GRAY."----------------------------");
	}
}

?>
