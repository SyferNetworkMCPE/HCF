<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class HelpStaffCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * HelpStaffCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("request", Loader::getInstance());
		$this->setDescription("/helpop:request [string: args]");
        $this->setAliases(["helpop"]);
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $cmd
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $cmd, Array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TE::RED."Use this command in the game!");
			return;
        }
        if(!isset($args[0])){
            $sender->sendMessage(TE::RED."Usage: /request <what you need> or /helpop <what you need>");
            return;
        }
        $reason = implode(" ", $args);
        $sender->sendMessage(TE::GREEN."Request help correctly, wait for the staffs!");
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
			if($player->hasPermission("report.command.use")){
                $player->sendMessage(TE::BOLD.TE::GOLD.$sender->getName().TE::RESET.TE::GRAY." is requesting help for the reason: ".TE::WHITE.$reason);
            }
        }
    }
}
