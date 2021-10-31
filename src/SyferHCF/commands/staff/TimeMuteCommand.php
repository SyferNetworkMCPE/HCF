<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;
use SyferHCF\utils\{Data, Time, Discord};

use pocketmine\item\{Item, ItemIds};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;

class TimeMuteCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * TimeMuteCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("tmute", Loader::getInstance());
		$this->setPermission("tmute.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender->hasPermission("tmute.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /tmute [string: target] [int: time] [string: reason]");
			return;
		}
		if(!in_array(Time::intToString($args[1]), Time::VALID_FORMATS)){
			$sender->sendMessage(TE::RED."The time format you enter is invalid!");
			return;
		}
		if(Loader::getInstance()->getServer()->getPlayer($args[0]) instanceof Player){
			if(Data::isTemporarilyMuted(Loader::getInstance()->getServer()->getPlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{Loader::getInstance()->getServer()->getPlayer($args[0])->getName()} already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			unset($exploded[1]);
			$reason = implode(" ", $exploded);
			
			Data::addMute(Loader::getInstance()->getServer()->getPlayer($args[0])->getName(), $reason, $sender->getName(), false, Time::getFormatTime(Time::stringToInt($args[1]), $args[1]));
			Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was temporarily muted of the network by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason.TE::RESET);
		}else{
			if(Data::isTemporarilyMuted(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName())){
				$sender->sendMessage(TE::RED."{Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName()} already muted from the network!");
				return;
			}
			$argument = implode(" ", $args);
			$exploded = explode(" ", $argument);
			//TODO:
			unset($exploded[0]);
			unset($exploded[1]);
			$reason = implode(" ", $exploded);

			Data::addMute(Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName(), $reason, $sender->getName(), false, Time::getFormatTime(Time::stringToInt($args[1]), $args[1]));
			Loader::getInstance()->getServer()->broadcastMessage(Loader::PREFIX.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was temporarily muted of the network by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason.TE::RESET);
		}
	}
}


?>
