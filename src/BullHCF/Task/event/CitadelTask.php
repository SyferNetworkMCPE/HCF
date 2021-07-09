<?php

namespace BullHCF\Task\event;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\crate\CrateManager;

use BullHCF\Task\asynctask\DiscordMessage;

use BullHCF\Citadel\{Citadel, CitadelManager};

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class CitadelTask extends Task {

    /** @var String */
    protected $citadelName;

    /** @var Int */
    protected $citadelTime;

    /**
     * CitadelTask Constructor.
     * @param String $citadelName
     * @param Int $cidatelTime
     */
    public function __construct(String $citadelName, Int $citadelTime = null){
        $this->citadelName = $citadelName;
        $this->citadelTime = $citadelTime;

        Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{citadelName}"], ["§", $citadelName], Loader::getConfiguration("messages")->get("citadel_was_started")));
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        $citadel = CitadelManager::getCitadel($this->citadelName);
        if(empty($citadel)){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        $citadel->setEnable(true);
        if(!$citadel->isEnable()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        if($citadel->getCapturer() === null||!$citadel->getCapturer()->isOnline()||(!$citadel->isInPosition($citadel->getCapturer()))){
            $citadel->setCapture(false);
            $citadel->setCitadelTime(!empty($this->CitadelTime) ? $this->CitadelTime : $citadel->getDefaultCitadelTime());
            $citadel->setCapturer(null);
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                if($citadel->isInPosition($player) && !$player->isInvincibility()){
                    if(empty($citadel->getCapturer())) $citadel->setCapturer($player);
                }
            }
            if(!empty($citadel->getCapturer())){

                Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{citadelName}", "{playerName}"], ["§", $citadel->getName(), $citadel->getCapturer()->getName()], Loader::getConfiguration("messages")->get("citadel_is_capturing")));
            }
        }
        if($citadel->getCitadelTime() === 0){
            if(empty($citadel->getCapturer())) return;

            CrateManager::giveKey($citadel->getCapturer(), "Koth", rand(1, 3));

            Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{citadelName}", "{playerName}"], ["§", $citadel->getName(), $koth->getCapturer()->getName()], Loader::getConfiguration("messages")->get("citadel_is_captured")));

            # Webhook to send the message to discord
            $message = $koth->getName()." Citadel was captured by ".$koth->getCapturer()->getName();
            Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new DiscordMessage(Loader::getDefaultConfig("URL"), $message, "DiverHCF | Citadel Information"));

            $citadel->setEnable(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $citadel->setCitadelTime($citadel->getCitadelTime() - 1);
        }
    }
}

?>