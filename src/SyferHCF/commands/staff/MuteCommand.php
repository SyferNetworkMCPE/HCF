<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;
use SyferHCF\utils\{Data, Discord};

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class MuteCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * MuteCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("mute", Loader::getInstance());
		$this->setPermission("mute.command.use");
	}   
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("mute.command.use")){
        	$sender->sendMessage(TE::RED."You have not permissions to use this command");
        	return;
        }
        if(!isset($args[0])||!isset($args[1])){
        	$sender->sendMessage(TE::RED."Usage: /mute [string: target] [string: reason]");
        	return;
		}
		if(Loader::getInstance()->getServer()->getPlayer($args[0]) instanceof Player){
			if(Data::isPermanentlyMuted(Loader::getInstance()->getServer()->getPlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{Loader::getInstance()->getServer()->getPlayer($args[0])->getName()} is already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			$reason = implode(" ", $exploded);
			
			Data::addMute(Loader::getInstance()->getServer()->getPlayer($args[0])->getName(), $reason, $sender->getName(), true);
			Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was silenced from the network by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason.TE::RESET);
		}else{
			if(Data::isPermanentlyMuted(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName()} is already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			$reason = implode(" ", $exploded);

			Data::addMute(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName(), $reason, $sender->getName(), true);
			Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was silenced from the network by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason.TE::RESET);
		}
	}
}
