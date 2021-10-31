<?php

namespace SyferHCF\commands\events;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\PP;
use SyferHCF\utils\Translator;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class PPCommand extends PluginCommand {
	
	/**
	 * PPCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("packageall", Loader::getInstance());
		parent::setDescription("handle pp event");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(count($args) === 0){
			$sender->sendMessage(TE::RED."Use: /{$label} <on|off>");
			return;
		}
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		switch($args[0]){
			case "on":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [Int: time]");
					return;
				}
				if(PP::isEnable()){
					$sender->sendMessage(TE::RED."The event was started before, you can't do this!");
					return;
				}
				PP::start($args[1]);
				Loader::getInstance()->getServer()->broadcastMessage("§r§8[§4Alert§8]§7 §dPartnerPackages §7the event has started for all connected players, be aware so you don't miss it.");
			break;
			case "off":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(!PP::isEnable()){
					$sender->sendMessage(TE::RED."The event was never started, you can't do this!");
					return;
				}
				PP::stop();
			break;
		}
	}
}

?>
