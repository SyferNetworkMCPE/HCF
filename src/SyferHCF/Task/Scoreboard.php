<?php

namespace SyferHCF\Task;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use SyferHCF\utils\Time;

use SyferHCF\koth\KothManager;
use SyferHCF\Citadel\CitadelManager;

use SyferHCF\listeners\event\{SOTW, EOTW, KEYALL, PP, AIRDROP, EVENT, PURGUE, FFA};
use pocketmine\command\defaults\GamemodeCommand;
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
        if (isset(Loader::$staffmode[$player->getName()])) {
            $scoreboard[] = TE::BLUE . TE::BOLD . "StaffMode" . TE::RESET . TE::WHITE . ": " . ($player->getChat() === Player::STAFF_CHAT ? TE::GREEN . "Activated" : TE::GREEN . "Activated");
            $scoreboard[] = TE::BLUE . TE::BOLD . "TPS" . TE::RESET . TE::WHITE . ": §b" . Loader::getInstance()->getServer()->getTick();
        }
        if($player->isCombatTag()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getCombatTagTime())], $config->get("CombatTag"));
        }
        if($player->isEnderPearl()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getEnderPearlTime())], $config->get("EnderPearl"));
        }
        if($player->isNinjaShear()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getNinjaShearTime())], $config->get("NinjaShear"));
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
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getEggTime())], $config->get("EggPorts"));
        }
        if($player->isSpecialItem()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], $config->get("SpecialItem"));
        }
        if($player->isResetItems()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getResetItemsTime())], $config->get("ResetItems"));
        }
        if($player->isPotionCounter()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getPotionCounterTime())], $config->get("PotionCounter"));
        }
        if($player->isLoggerBait()){
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getLoggerBaitTime())], $config->get("LoggerBait"));
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
        if(($citadelName = CitadelManager::CitadelIsEnabled())){
        	$citadel = CitadelManager::getCitadel($citadelName);
            $scoreboard[] = str_replace(["&", "{citadelName}", "{time}"], ["§", $citadel->getName(), Time::getTimeToString($citadel->getCitadelTime())], $config->get("CITADEL"));
        }
        if (isset(Loader::$rogue[$player->getName()])) {
            $reaming = Loader::$rogue[$player->getName()] - time();
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($reaming)], $config["ROGUE_DELAY"]);
        }
        if (isset(Loader::$mark[$player->getName()])) {
            $reaming = Loader::$mark[$player->getName()] - time();
            $scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToString($reaming)], $config["Archer_Mark"]);
        }
        if(SOTW::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(SOTW::getTime())], $config->get("SOTW"));
        }
        if(EOTW::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(EOTW::getTime())], $config->get("EOTW"));
        }
        if(KEYALL::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(KEYALL::getTime())], $config->get("KEYALL"));
        }
        if(PP::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(PP::getTime())], $config->get("PP"));
        }
        if(AIRDROP::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(AIRDROP::getTime())], $config->get("AIRDROP"));
        }
        if(EVENT::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(EVENT::getTime())], $config->get("EVENT"));
        }
        if(PURGUE::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(PURGUE::getTime())], $config->get("PURGUE"));
        }
        if(FFA::isEnable()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString(FFA::getTime())], $config->get("FFA"));
        }
        if($player->isInvincibility()){
        	$scoreboard[] = str_replace(["&", "{time}"], ["§", Time::getTimeToFullString($player->getInvincibilityTime())], $config->get("Invincibility"));
        }
        if($player->isBardClass()){
            $scoreboard[] = str_replace(["&", "{bardEnergy}"], ["§", $player->getBardEnergy()], $config->get("BardEnergy"));
        }
        if($player->isOpBardClass()){
            $scoreboard[] = str_replace(["&", "{opbardEnergy}"], ["§", $player->getOpBardEnergy()], $config->get("OpBardEnergy"));
        }
        if($player->isArcherClass()){
            $scoreboard[] = str_replace(["&", "{archerEnergy}"], ["§", $player->getArcherEnergy()], $config->get("ArcherEnergy"));
        }
        if($player->isOpArcherClass()){
            $scoreboard[] = str_replace(["&", "{oparcherEnergy}"], ["§", $player->getOpArcherEnergy()], $config->get("OpArcherEnergy"));
        }
        if($player->isMageClass()){
            $scoreboard[] = str_replace(["&", "{mageEnergy}"], ["§", $player->getMageEnergy()], $config->get("MageEnergy"));
        }
          $claim = TE::RED.$player->getRegion();
        if($player->getRegion() === Factions::getFaction($player->getName())){
          $claim = TE::GREEN.$player->getRegion();
        }
        if($player->getRegion() === "Spawn"){
          $claim = TE::GREEN.$player->getRegion();
        }
        $scoreboard[] = TE::DARK_PURPLE.TE::BOLD."Claim".TE::RESET.TE::WHITE.": ".$claim;
        if($player->isFocus()){
            if(!Factions::isFactionExists($player->getFocusFaction())) $player->setFocus(false);
            foreach($config->get("factionFocus") as $message){
                $scoreboard[] = str_replace(["&", "{factionName}", "{factionHome}", "{factionDTR}", "{factionOnlinePlayers}"], ["§", $player->getFocusFaction(), Factions::getFactionHomeString($player->getFocusFaction()), Factions::getStrength($player->getFocusFaction()), Factions::getOnlinePlayers($player->getFocusFaction())], $message);
            }
        }
        if(count($scoreboard) >= 1){
        	$scoreboard[] = TE::WHITE."                  ";
            $scoreboard[] = TE::ITALIC.TE::WHITE."syferhcf.sytes.net:19763";
            $scoreboard[] = TE::GRAY."-----------------------";
            $texting = [TE::GRAY.TE::GRAY."-----------------------"];
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
