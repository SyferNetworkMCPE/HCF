<?php

namespace BullHCF\Task;

use BullHCF\{Loader, Factions};
use BullHCF\player\Player;

use BullHCF\utils\Time;

use BullHCF\koth\KothManager;

use BullHCF\listeners\event\{SOTW, EOTW, GiftChest};

use Advanced\Data\PlayerBase;

use pocketmine\scheduler\Task;
use pocketmine\utils\{Config, TextFormat as TE};

class Scoreboard extends Task {

    /** @var Player */
    protected $player;

    /**
     * Scoreboard Constructor.
     * @param Loader $plugin
     */
    public function __construct(Player $player){
        $this->player = $player;
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        $player = $this->player;
        if(!$player->isOnline()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if(Factions::isSpawnRegion($player)) $player->setFood(20);

        $config = Loader::getConfiguration("scoreboard_settings");
        $api = Loader::getScoreboard();
        /** @var Array[] */
        $scoreboard = [];
        if($player->isCombatTag()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getCombatTagTime())], $config->get("CombatTag"));
        }
        if($player->isEnderPearl()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getEnderPearlTime())], $config->get("EnderPearl"));
        }
        if($player->isStormBreaker()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getStormBreakerTime())], $config->get("StormBreaker"));
        }
        if($player->isGoldenGapple()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getGoldenAppleTime())], $config->get("Apple"));
        }
        if($player->isLogout()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getLogoutTime())], $config->get("Logout"));
        }
        if($player->isAntiTrapper()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], $config->get("AntiTrapper"));
        }
        if($player->isEgg()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getEggTime())], $config->get("EggPort"));
        }
        if($player->isSpecialItem()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], $config->get("SpecialItem"));
        }
        if($player->isPotionCounter()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getPotionCounterTime())], $config->get("PotionCounter"));
        }
        if($player->isTeleportingHome()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getTeleportingHomeTime())], $config->get("Home"));
        }
        if($player->isTeleportingStuck()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getTeleportingStuckTime())], $config->get("Stuck"));
        }
        if(($kothName = KothManager::kothIsEnabled())){
        	$koth = KothManager::getKoth($kothName);
            $scoreboard[] = str_replace(["&", "{kothName}", "{time}"], ["§", $koth->getName(), Time::getTimeToString($koth->getKothTime())], $config->get("KOTH"));
        }
        if(SOTW::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(SOTW::getTime())], $config->get("SOTW"));
        }
        if(EOTW::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(EOTW::getTime())], $config->get("EOTW"));
        }
        if($player->isInvincibility()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString($player->getInvincibilityTime())], $config->get("Invincibility"));
        }
        if($player->isBardClass()){
            $scoreboard[] = str_replace(["&", "{bardEnergy}"], ["§", $player->getBardEnergy()], $config->get("BardEnergy"));
        }
        if($player->isArcherClass()){
            $scoreboard[] = str_replace(["&", "{archerEnergy}"], ["§", $player->getArcherEnergy()], $config->get("ArcherEnergy"));
        }
        if($player->isFocus()){
            if(!Factions::isFactionExists($player->getFocusFaction())) $player->setFocus(false);
            foreach($config->get("factionFocus") as $message){
                $scoreboard[] = str_replace(["&", "{factionName}", "{factionHome}", "{factionDTR}", "{factionOnlinePlayers}"], ["§", $player->getFocusFaction(), Factions::getFactionHomeString($player->getFocusFaction()), Factions::getStrength($player->getFocusFaction()), Factions::getOnlinePlayers($player->getFocusFaction())], $message);
            }
        }
        if(count($scoreboard) >= 1){
            $scoreboard[] = TE::GRAY."------------------- ";
            $texting = [TE::GRAY.TE::GRAY."------------------- "];
      	  $scoreboard = array_merge($texting, $scoreboard);
        }else{
        	$api->removePrimary($player);
        	return;
        }
        $api->newScoreboard($player, $player->getName(), str_replace(["&"], ["§"], $config->get("scoreboard_name")));
        if($api->getObjectiveName($player) !== null){
            foreach($scoreboard as $line => $key){
                $api->remove($player, $scoreboard);
                $api->newScoreboard($player, $player->getName(), str_replace(["&"], ["§"], $config->get("scoreboard_name")));
            }
        }
        foreach($scoreboard as $line => $key){
            $api->setLine($player, $line + 1, $key);
        }
    }
}

?>