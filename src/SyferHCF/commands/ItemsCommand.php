<?php

namespace SyferHCF\commands;

use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;
use SyferHCF\provider\YamlProvider;
use SyferHCF\utils\Extensions;
use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class ItemsCommand extends PluginCommand {
    
	/**
	 * ViewCommand Constructor.
	 */
	
	public function __construct(){
		parent::__construct("items", Loader::getInstance());
		parent::setDescription("select your unlocked views or buy them in the store");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	
	public function execute(CommandSender $sender, String $label, Array $args) : void {
	    if(!$sender instanceof Player) return;
	    $this->plugin = Loader::getInstance();
	    $data = YamlProvider::getData($sender->getName())->get("items.menu");
	    if(!isset($args[0])) {
            if ($data === true) {
                Extensions::getViewMenu()->sendMenu($sender);
             }
          }
       }
	}

?>
