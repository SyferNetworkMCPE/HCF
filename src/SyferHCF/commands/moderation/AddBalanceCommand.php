<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class AddBalanceCommand extends PluginCommand{
  public function __construct(){
    parent::__construct("addbalance", Loader::getInstance());
    $this->setDescription("add Balance a player");
  }
  public function execute(CommandSender $sender, String $label, Array $args) : void{
    if(!$sender->hasPermission("addbalance.command.use")){
      $sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
    }
    if(count($args) === 0){
      $sender->sendMessage("§cUse: /addbalance <playerName> <amount>");
    }
    if(!is_numeric($args[1])){
      $sender->sendMessage("§cThe argument must be numeric");
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($args[0]);
		$sender->sendMessage("§5added Balance set to §6" . $args[0]);
		$player->addBalance($args[1]);
  }
}
