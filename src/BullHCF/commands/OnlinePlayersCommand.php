<?php

namespace BullHCF\commands;

use BullHCF\Loader;
use BullHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class OnlinePlayersCommand extends PluginCommand {
	
	/**
	 * OnlinePlayers Constructor.
	 */
	public function __construct(){
		parent::__construct("players", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$sender->sendMessage(TE::GRAY."----------------------------");
		$sender->sendMessage(TE::LIGHT_PURPLE."Connected Players: ".TE::GRAY.count(Loader::getInstance()->getServer()->getOnlinePlayers()));
		$sender->sendMessage(TE::GRAY."----------------------------");
	}
}

?>