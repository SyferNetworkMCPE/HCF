<?php

namespace SyferHCF\network;

use SyferHCF\Loader;
use SyferHCF\network\packets\CraftingDataPacket;
use SyferHCF\network\packets\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PacketPool;

class NetworkManager {

    /** @var SyferHCF */
    private $core;

    /**
     * NetworkManager constructor.
     *
     * @param SyferHCF $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
        $this->init();
        $core->getServer()->getPluginManager()->registerEvents(new NetworkListener($core), $core);
    }

    public function init() {
        PacketPool::registerPacket(new CraftingDataPacket());
        PacketPool::registerPacket(new InventoryTransactionPacket());
    }
}
