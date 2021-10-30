<?php

namespace SyferHCF\level\task;

use SyferHCF\Loader;
use SyferHCF\level\LevelManager;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class GlowstoneResetTask extends Task {

    /** @var LevelManager */
    private $manager;

    /**
     * GlowstoneResetTask constructor.
     *
     * @param LevelManager $manager
     */
    public function __construct(LevelManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) {
        $this->manager->getGlowstoneMountain()->reset();
        Loader::getInstance()->getServer()->broadcastMessage(TextFormat::BLUE . "[Discord] §fputo el que lea xd");
    }
}
