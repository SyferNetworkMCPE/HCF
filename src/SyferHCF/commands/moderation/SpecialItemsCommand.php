<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\item\specials\{AntiTrapper,
    SecondChance,
    StormBreaker,
    KnockBack,
    EggPorts,
    Strength,
    ResetItems,
    NinjaShear,
    RageBall,
    Camuflaje,
    Resistance,
    Invisibility,
    Refill,
    AntiFall,
    PotionCounter,
    Cocaine,
    UnBan,
    Rank,
    NoPotions,
    CloseCall,
    Firework,
    LoggerBait,
    EffectsBard,
    RemoveEffects,
    RareBrick};

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class SpecialItemsCommand extends PluginCommand {
	
	/**
	 * SpecialItemsCommand Constructor.
	 */
	public function __construct(){
        parent::__construct("specialitems", Loader::getInstance());
        parent::setDescription("give all special items on the server");
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
        if ($sender instanceof Player) {
            $stormbreaker = new StormBreaker();
            $antitrapper = new AntiTrapper();
            $secondchance = new SecondChance();
            $eggports = new EggPorts();
            $strength = new Strength();
            $antifall = new AntiFall();
            $rageball = new RageBall();
            $cocaine = new Cocaine();
            $closecall = new CloseCall();
            $removeeffects = new RemoveEffects();
            $camuflaje = new Camuflaje();
            $refill = new Refill();
            $resetitems = new ResetItems();
            $resistance = new Resistance();
            $invisibility = new Invisibility();
            $knockback = new KnockBack();
            $unban = new UnBan();
            $rank = new Rank();
            $effectsbard = new EffectsBard();
            $nopotions = new NoPotions();
            $potionCounter = new PotionCounter();
            $ninjashear = new NinjaShear();
            $firework = new Firework();
            $loggerbait = new LoggerBait();

            $sender->getInventory()->addItem($stormbreaker);
            $sender->getInventory()->addItem($antitrapper);
            $sender->getInventory()->addItem($loggerbait);
            $sender->getInventory()->addItem($eggports);
            $sender->getInventory()->addItem($rageball);
            $sender->getInventory()->addItem($knockback);
            $sender->getInventory()->addItem($nopotions);
            $sender->getInventory()->addItem($strength);
            $sender->getInventory()->addItem($resistance);
            $sender->getInventory()->addItem($invisibility);
            $sender->getInventory()->addItem($camuflaje);
            $sender->getInventory()->addItem($refill);
            $sender->getInventory()->addItem($effectsbard);
            $sender->getInventory()->addItem($resetitems);
            $sender->getInventory()->addItem($cocaine);
            $sender->getInventory()->addItem($closecall);
            $sender->getInventory()->addItem($removeeffects);
            $sender->getInventory()->addItem($unban);
            $sender->getInventory()->addItem($rank);
            $sender->getInventory()->addItem($potionCounter);
            $sender->getInventory()->addItem($ninjashear);
            $sender->getInventory()->addItem($firework);
            $sender->getInventory()->addItem($antifall);
            $sender->getInventory()->addItem($secondchance);
        }
	}
}

?>
