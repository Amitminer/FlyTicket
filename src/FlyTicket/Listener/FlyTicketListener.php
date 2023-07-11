<?php

namespace FlyTicket\Listener;

use FlyTicket\Main;
use FlyTicket\Task\FlyTicketTask;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use FlyTicket\Manager\DatabaseManager;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use FlyTicket\Manager\MessageManager;
use pocketmine\Server;
use pocketmine\world\sound\FireExtinguishSound;
use poggit\libasynql\libs\SOFe\AwaitGenerator\Await;

class FlyTicketListener implements Listener
{
    private $databaseManager;

    public function __construct() {
        $this->databaseManager = new DatabaseManager();
    }
    public function itemUse(PlayerItemUseEvent $event) {
        $item = $event->getItem();
        $player = $event->getPlayer();
        $playerName = $player->getName();
        if ($item->getNamedTag()->getTag("second")) {
            $second = $item->getNamedTag()->getInt("second");
            Await::f2c(function () use ($player,$playerName,$item,$second,$event) {
                $rows = (array)yield from $this->databaseManager->playerExists($playerName);
                if (!empty($rows)) {
                    $event->cancel();
                    $player->sendMessage(MessageManager::alreadyactive());
                    return;
                } else {
                    $this->executeflyticket($player,$playerName,$item,$second);
                }
            });
        }
    }
    private function executeflyticket($player,$playerName,$item,$second): void {

        $motion = new Vector3(0,1,0);
        $player->setMotion($motion);
        $player->sendMessage(MessageManager::usage());
        $this->databaseManager->addPlayer($playerName);
        $player->getWorld()->addSound($player->getPosition(),
            new FireExtinguishSound(),
            [$player]);
        $player->getInventory()->removeItem($item);
        $time = $second / 60;
        $userName = '"' . $player->getName() . '"';
        $server = Server::getInstance();
        // TODO: USE RankSystem api
        // TODO: SUPPORT CUSTOM ITEMS AS FLYTICKET.
        //TODO: use timer api to manage time!
        // CREATE MessageManager
        $command = "ranks setpermission " . $userName . " blazinfly.command " . $time . "m";
        //var_dump($command);
        $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()),
            $command);
        $player->sendMessage(MessageManager::fly_usage());
        Main::$main->getScheduler()->scheduleRepeatingTask(new FlyTicketTask($player, (int)$second),
            20 * 1);
    }
}
