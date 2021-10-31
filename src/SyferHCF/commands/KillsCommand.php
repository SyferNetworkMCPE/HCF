<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class KillsCommand extends PluginCommand {
	
	/**
	 * Kills Constructor.
	 */
	public function __construct(){
		parent::__construct("kills", Loader::getInstance());
		parent::setDescription("show your kills");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$sender->sendMessage(TE::YELLOW."--------------");
		$sender->sendMessage(TE::DARK_PURPLE."You have ".TE::GOLD.$sender->getKills().TE::DARK_PURPLE." Kills");
		$sender->sendMessage(TE::YELLOW."--------------");
	}
}

?>
