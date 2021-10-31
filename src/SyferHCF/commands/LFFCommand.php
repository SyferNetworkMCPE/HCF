<?php

namespace SyferHCF\commands;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};

use pocketmine\utils\TextFormat as TE;
class LFFCommand extends PluginCommand{
  
  public function __construct(){
    parent::__construct("lff", Loader::getInstance());
    $this->setDescription("Search a faction");
  }
  public function execute(CommandSender $sender, String $label, Array $args): void{
    if(Factions::inFaction($sender->getName())){
    $sender->sendMessage(TE::RED . "You cannot run this command if you are already in a faction");
    return;
    }
    Loader::getInstance()->getServer()->broadcastMessage("§b----------------------------------");
		Loader::getInstance()->getServer()->broadcastMessage("§l§a".$sender->getName()."§6 is looking for faction");
		Loader::getInstance()->getServer()->broadcastMessage("§b----------------------------------");
  }
}
