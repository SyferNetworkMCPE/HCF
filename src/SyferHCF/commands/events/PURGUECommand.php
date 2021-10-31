<?php

namespace SyferHCF\commands\events;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\listeners\event\PURGUE;
use SyferHCF\utils\Translator;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class PURGUECommand extends PluginCommand {
	
	/**
	 * PURGUECommand Constructor.
	 */
	public function __construct(){
		parent::__construct("purgue", Loader::getInstance());
		parent::setDescription("handle purgue event");
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
				if(PURGUE::isEnable()){
					$sender->sendMessage(TE::RED."The event was started before, you can't do this!");
					return;
				}
				PURGUE::start($args[1]);
			break;
			case "off":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(!PURGUE::isEnable()){
					$sender->sendMessage(TE::RED."The event was never started, you can't do this!");
					return;
				}
				PURGUE::stop();
			break;
		}
	}
}

?>
