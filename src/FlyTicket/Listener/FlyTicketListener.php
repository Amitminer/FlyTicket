<?php

namespace FlyTicket\Listener;

use FlyTicket\Main;
use FlyTicket\Manager\FlyTicketManager;
use FlyTicket\Task\FlyTicketTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\Server;
use pocketmine\world\sound\FireExtinguishSound;

class FlyTicketListener implements Listener
{
    public function itemUse(PlayerItemUseEvent $event)
{
    $item = $event->getItem();
    $player = $event->getPlayer();
    if ($item->getNamedTag()->getTag("second")) {
        $second = $item->getNamedTag()->getInt("second");
        if (isset(FlyTicketManager::$array[$player->getName()])) {
            $event->cancel();
            $player->sendMessage("§cYou already have an active fly ticket.");
            return;
        }
        $motion = new Vector3(0, 1, 0);
        $player->setMotion($motion);
        $player->sendMessage(Main::$config->get("usage-message"));
        FlyTicketManager::$array[$player->getName()] = true;
        $player->getWorld()->addSound($player->getPosition(), new FireExtinguishSound(), [$player]);
        $player->getInventory()->removeItem($item);
       # $format = $player->getName();
       # if you want to give a rank to player who purchased fly ticket & used it.remove # from $format & $player!
        #$player->setDisplayName("§8[§gFLY§8] ".$format);
        Main::$main->getScheduler()->scheduleRepeatingTask(new FlyTicketTask($player, (int)$second), 20 * 1);
    }
  }
}