<?php

namespace BullHCF\commands;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\FormAPI\{FormData, MenuForm};

use BullHCF\enchantments\Enchantments;

use pocketmine\item\Armor;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

use pocketmine\item\enchantment\EnchantmentInstance;

class CEnchantmentsCommand extends PluginCommand {
	
	/**
	 * CEnchantmentsCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("cenchantments", Loader::getInstance());
		
		parent::setPermission("cenchantments.command.use");
		parent::setDescription("Can enchant your armor with custom enchantments");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->hasPermission("cenchantments.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        $this->open($sender);
	}
	
	/**
	 * @param Player $player
	 */
	protected function open(Player $player){
		$form = new MenuForm(function (Player $player, $data){
			if($data === null){
				return;
			}
			$enchantment = Enchantments::getEnchantmentByName($data);
			$item = $player->getInventory()->getItemInHand();
			if($item->isNull()||!$item instanceof Armor){
				return;
			}
			if($player->getBalance() < $enchantment->getEnchantmentPrice()){
				$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_money_not_enough")));
				return;
			}
			$item->addEnchantment(new EnchantmentInstance($enchantment, 1));
			$item->setLore([TE::RESET."\n\n".TE::AQUA.$enchantment->getNameWithFormat()]);
			
			$player->reduceBalance($enchantment->getEnchantmentPrice());
       		$player->getInventory()->setItemInHand($item);
		});
		$form->setTitle(TE::GOLD.TE::BOLD."CUSTOM ENCHANTMENTS SELECTOR!");
		foreach(array_values(Enchantments::getEnchantments()) as $enchantment){
			$form->addButton(TE::BOLD.TE::RED.$enchantment->getName().TE::RESET."\n".TE::YELLOW."Price".TE::WHITE.": ".TE::YELLOW."$".$enchantment->getEnchantmentPrice(), -1, "", $enchantment->getName());
		}
		$player->sendForm($form);
		return $form;
	}
}

?>