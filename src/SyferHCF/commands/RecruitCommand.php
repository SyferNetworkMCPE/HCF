<?php

namespace SyferHCF\commands;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};

use pocketmine\utils\TextFormat as TE;
class RecruitCommand extends PluginCommand{
  
  public function __construct(){
    parent::__construct("recruit", Loader::getInstance());
    $this->setDescription("Search members a faction");
  }
  public function execute(CommandSender $sender, String $label, Array $args): void{
    $senderName = $sender->getName();
    if (!Factions::inFaction($senderName)) {
    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
    return;
    }
    if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
    return;
    }
    Loader::getInstance()->getServer()->broadcastMessage("§b----------------------------------");
		Loader::getInstance()->getServer()->broadcastMessage("§l§a".$sender->getName()."§6 Search for members for your faction");
		Loader::getInstance()->getServer()->broadcastMessage("§b----------------------------------");
  }
}
