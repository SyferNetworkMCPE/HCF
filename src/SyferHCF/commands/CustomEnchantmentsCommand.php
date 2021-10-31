<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\FormAPI\{FormData, MenuForm};

use SyferHCF\enchantments\Enchantments;

use pocketmine\item\Armor;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

use pocketmine\item\enchantment\EnchantmentInstance;

class CustomEnchantmentsCommand extends PluginCommand {
	
	/**
	 * CEnchantmentsCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("customenchants", Loader::getInstance());
		
		parent::setDescription("Can enchant your armor with custom enchantments");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
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
			$item->setLore([TE::AQUA.$enchantment->getNameWithFormat()]);
			
			$player->reduceBalance($enchantment->getEnchantmentPrice());
       		$player->getInventory()->setItemInHand($item);
		});
		$form->setTitle(TE::LIGHT_PURPLE.TE::BOLD."CE MENU");
		foreach(array_values(Enchantments::getEnchantments()) as $enchantment){
			$form->addButton(TE::BOLD.TE::DARK_PURPLE.$enchantment->getName().TE::RESET."\n".TE::GOLD."Price".TE::WHITE.": ".TE::YELLOW."$".$enchantment->getEnchantmentPrice(), -1, "", $enchantment->getName());
		}
		$player->sendForm($form);
		return $form;
	}
}

?>
