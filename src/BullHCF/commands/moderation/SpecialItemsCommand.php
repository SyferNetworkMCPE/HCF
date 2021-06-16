<?php

namespace BullHCF\commands\moderation;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\item\specials\{AntiTrapper, NinjaShear, StormBreaker, EggPorts, Strength, Resistance, Invisibility, PotionCounter, Firework};

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class SpecialItemsCommand extends PluginCommand {
	
	/**
	 * SpecialItemsCommand Constructor.
	 */
	public function __construct(){
        parent::__construct("items", Loader::getInstance());
        parent::setDescription("Get all special items from the server");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
        }
        $stormbreaker = new StormBreaker();
        $antitrapper = new AntiTrapper();
		$eggports = new EggPorts();
		$strength = new Strength();
		$resistance = new Resistance();
		$invisibility = new Invisibility();
		$potionCounter = new PotionCounter();
		$firework = new Firework();

        $sender->getInventory()->addItem($stormbreaker);
        $sender->getInventory()->addItem($antitrapper);
		$sender->getInventory()->addItem($eggports);
		$sender->getInventory()->addItem($strength);
		$sender->getInventory()->addItem($resistance);
		$sender->getInventory()->addItem($invisibility);
        $sender->getInventory()->addItem($potionCounter);
        $sender->getInventory()->addItem($firework);
	}
}

?>