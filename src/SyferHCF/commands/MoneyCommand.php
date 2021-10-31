<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class MoneyCommand extends PluginCommand {
	
	/**
	 * MoneyCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("balance", Loader::getInstance());
		parent::setAliases(["bal"]);
		parent::setDescription("Can see the total sum of your balance");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
        $sender->sendMessage(str_replace(["&", "{money}"], ["§", $sender->getBalance()], Loader::getConfiguration("messages")->get("player_total_money")));
	}
}

?>
