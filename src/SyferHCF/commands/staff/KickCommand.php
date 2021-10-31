<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class KickCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * KickCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("kick", Loader::getInstance());
		$this->setPermission("kick.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("kick.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /kick [string: target] [string: reason]");
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($args[0]);
		if($player === null){
			$sender->sendMessage(TE::RED."The player you are looking for is not connected!");
   			return;
		}
		unset($args[0]);
		$reason = implode(" ", $args);
		$player->close("", TE::BOLD.TE::DARK_PURPLE."You were kicked from our network".TE::RESET."\n".TE::GOLD."Kicked By§r§f: ".TE::LIGHT_PURPLE.$sender->getName()."\n".TE::GOLD."Reason: ".TE::LIGHT_PURPLE.$reason);
		Loader::getInstance()->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::LIGHT_PURPLE.$player->getName().TE::RESET.TE::GRAY." was kicked from the server by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason);
	}
}

?>
