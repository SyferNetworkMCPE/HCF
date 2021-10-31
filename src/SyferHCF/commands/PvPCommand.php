<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;
use SyferHCF\player\{Player, PlayerBase};

class PvPCommand extends PluginCommand {
	
  public function __construct(){
    parent::__construct("pvp", Loader::getInstance());
  }
  public function execute(CommandSender $sender, String $label, Array $args){
    if(empty($args[0])){
      $sender->sendMessage(TE::RED . "Use: /{$label} enable");
      
      return;
    }
    switch($args[0]){
      
      case "enable":
          if($sender instanceof Player){
              if($sender->isInvincibility()){
              	PlayerBase::removeData($sender->getName(), "pvp_time");
                  $sender->setInvincibility(false);
                  $sender->sendMessage(TE::GRAY."You have successfully enabled §dPvPTimer§7!");
              }else{
                  $sender->sendMessage(TE::GRAY."You have already the §dPvPTimer §7enabled!");
              }
          }
      break;
      
    }
  }
}
