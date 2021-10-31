<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;
use pocketmine\Player;
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\item\{Item, ItemIds};

class StaffModeCommand extends PluginCommand {

    /** @var Loader */
    protected $plugin;

    /**
	 * StaffMode Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("mod", Loader::getInstance());
		$this->setPermission("mod.command.use");
		$this->setAliases(["staff", "staffmode"]);
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender instanceof Player){
            $sender->sendMessage(TE::RED."Use this command in the game!");
            return;
        }
        if(!$sender->hasPermission("mod.command.use")){
        	$sender->sendMessage(TE::RED."You have not permissions to use this command");
        	return;
        }
        if(!isset(Loader::$staffmode[$sender->getName()])){
            Loader::$staffmode[$sender->getName()] = $sender;
            $sender->setGamemode(Player::SPECTATOR);
            $sender->removeAllEffects();
            $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 20 * 999999, 9));
            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            $sender->getInventory()->setItem(0, Item::get(ItemIds::PACKED_ICE, 0, 1)->setCustomName(TE::AQUA."Freeze"));
            $sender->getInventory()->setItem(1, Item::get(ItemIds::COMPASS, 0, 1)->setCustomName(TE::YELLOW."Teleporter"));
            $sender->getInventory()->setItem(2, Item::get(ItemIds::CLOCK, 0, 1)->setCustomName(TE::DARK_AQUA."Random player"));
            $sender->getInventory()->setItem(6, Item::get(ItemIds::SKULL, 0, 1)->setCustomName(TE::DARK_PURPLE."Player Info"));
            $sender->getInventory()->setItem(7, Item::get(ItemIds::DYE, 1, 1)->setCustomName(TE::RED."Disable Vanish"));
            $sender->getInventory()->setItem(8, Item::get(ItemIds::BOOK, 0, 1)->setCustomName(TE::BLUE."Player Inventory"));
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                $player->hidePlayer($sender);
            }
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                if($player->hasPermission("mod.command.use")){
                    $player->sendMessage(TE::GRAY."[§3StaffChat§7]".TE::GRAY."[§b".$sender->getLevel()->getName()."§7]".TE::RESET." ".TE::LIGHT_PURPLE.$sender->getName().TE::WHITE.": ".TE::GREEN."Active StaffMode");
                }
            }
            $sender->sendMessage(TE::GREEN."You activated StaffMode correctly!");
        }else{
            unset(Loader::$staffmode[$sender->getName()]);
            $sender->setGamemode(Player::SURVIVAL);
            $sender->getInventory()->clearAll();
            $sender->removeAllEffects();
            $sender->getArmorInventory()->clearAll();
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                $player->showPlayer($sender);
            }
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                if($player->hasPermission("mod.command.use")){
                    $player->sendMessage(TE::GRAY."[§3StaffChat§7]".TE::GRAY."[§b".$sender->getLevel()->getName()."§7]".TE::RESET." ".TE::LIGHT_PURPLE.$sender->getName().TE::WHITE.": ".TE::RED."Desactive StaffMode");
                }
            }
            $sender->sendMessage(TE::RED."You deactivated StaffMode correctly!");
        }
    }
}

?>
