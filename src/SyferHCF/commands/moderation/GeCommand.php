<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class GeCommand extends PluginCommand {
	
	/**
	 * GeCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("ffaeffects", Loader::getInstance());
		parent::setDescription("give all players ffa effects");
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
        $speed = new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 50000, 1);
        $night_vision = new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 20 * 50000, 1);
        $invisibility = new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 50000, 1);
		foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
            $player->addEffect($speed);
            $player->addEffect($night_vision);
            $player->addEffect($invisibility);
		}
		$sender->sendMessage(TE::BOLD.TE::GOLD."FFA effects have been given to this number of playersf ".TE::BOLD.TE::DARK_GREEN.count(Loader::getInstance()->getServer()->getOnlinePlayers()));
	}
}

?>
