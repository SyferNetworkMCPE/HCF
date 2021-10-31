<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;
use SyferHCF\utils\Data;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class UnMuteCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * UnMuteCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("unmute", Loader::getInstance());
		$this->setPermission("unmute.command.use");
	}
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("unmute.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])){
			$sender->sendMessage(TE::RED."/unmute [string: target]");
			return;
		}
		if(Data::isPermanentlyMuted(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName())){
			Data::deleteMute(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName(), true);
			Loader::getInstance()->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was unmuted from the network, by the staff ".TE::BOLD.TE::DARK_PURPLE.$sender->getName());
		}
		elseif(Data::isTemporarilyMuted(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName())){
			Data::deleteMute(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName(), false);
			Loader::getInstance()->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was unmuted from the network, by the staff ".TE::BOLD.TE::DARK_PURPLE.$sender->getName());
		}else{
			$sender->sendMessage(TE::RED.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName()." It was never muted from the server");
		}
	}
}
