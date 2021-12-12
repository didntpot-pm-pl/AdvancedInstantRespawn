<?php

namespace DidntPot\AdvancedInstantRespawn;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;

class EventListener implements Listener
{
    /** @var Loader */
    public Loader $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param EntityDamageEvent $ev
     */
    public function onEntityDamage(EntityDamageEvent $ev)
    {
        $player = $ev->getEntity();

        if(!$player instanceof Player) return;
        if($ev->isCancelled()) return;

        if($ev->getCause() === EntityDamageEvent::CAUSE_VOID)
        {
            $ev->cancel();

            $player->setHealth(20);
            $player->getHungerManager()->setFood(20);

            $player->getEffects()->clear();

            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();

            $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
            return;
        }

        if($ev instanceof EntityDamageByEntityEvent)
        {
            $player = $ev->getEntity();
            $damager = $ev->getDamager();

            if($player instanceof Player and $damager instanceof Player)
            {
                if($ev->getFinalDamage() >= $player->getHealth())
                {
                    $ev->cancel();

                    $player->setHealth(20);
                    $player->getHungerManager()->setFood(20);

                    $player->getEffects()->clear();

                    $player->getInventory()->clearAll();
                    $player->getArmorInventory()->clearAll();

                    $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                    return;
                }
            }
        }
    }
}