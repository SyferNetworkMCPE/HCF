<?php

namespace SyferHCF\network;

use SyferHCF\Loader;
use SyferHCF\network\packets\InventoryTransactionPacket;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;

class NetworkListener implements Listener {

    /** @var SyferHCF */
    private $core;

    /**
     * NetworkListener constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
    }

    /**
     * @priority LOWEST
     * @param DataPacketReceiveEvent $event
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if($packet instanceof InventoryTransactionPacket) {
            if($packet->transactionType == InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY){
                if($packet->trData->actionType == InventoryTransactionPacket::USE_ITEM_ON_ENTITY_ACTION_INTERACT){
                    $entity = $player->getLevel()->getEntity($packet->trData->entityRuntimeId);
                    $item = $player->getInventory()->getItemInHand();
                    $slot = $packet->trData->hotbarSlot;
                    $clickPos = $packet->trData->clickPos;
                    if(method_exists($entity, "onInteract")) {
                        $entity->onInteract($player, $item, $slot, $clickPos);
                    }
                }
            }
        }
    }
}
