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

/**
 * Class FlyTicketListener
 * Listens to player item use events and handles FlyTicket logic.
 */
class FlyTicketListener implements Listener
{
    private $databaseManager;

    /**
     * FlyTicketListener constructor.
     */
    public function __construct()
    {
        $this->databaseManager = new DatabaseManager();
    }

    /**
     * Handle the player item use event.
     *
     * @param PlayerItemUseEvent $event The event instance.
     */
    public function itemUse(PlayerItemUseEvent $event): void
    {
        $item = $event->getItem();
        $player = $event->getPlayer();
        $playerName = $player->getName();

        if ($item->getNamedTag()->getTag("second")) {
            $second = $item->getNamedTag()->getInt("second");
            
            Await::f2c(function () use ($player, $playerName, $item, $second, $event) {
                $rows = (array) yield from $this->databaseManager->playerExists($playerName);

                if (!empty($rows)) {
                    $event->cancel();
                    $player->sendMessage(MessageManager::alreadyActive());
                    return;
                } else {
                    $this->executeFlyTicket($player, $playerName, $item, $second);
                }
            });
        }
    }

    /**
     * Execute the fly ticket logic.
     *
     * @param Player $player The player instance.
     * @param string $playerName The name of the player.
     * @param $item The item used.
     * @param int $second The duration of the fly ticket.
     */
    private function executeFlyTicket(Player $player, string $playerName, $item, int $second): void
    {
        $motion = new Vector3(0, 1, 0);
        $player->setMotion($motion);
        $player->sendMessage(MessageManager::usage());
        $this->databaseManager->addPlayer($playerName);
        $player->getWorld()->addSound($player->getPosition(), new FireExtinguishSound(), [$player]);
        $player->getInventory()->removeItem($item);
        $time = $second / 60;
        $userName = '"' . $player->getName() . '"';
        $server = Server::getInstance();
        // TODO: USE RankSystem api
        // TODO: SUPPORT CUSTOM ITEMS AS FLYTICKET.
        // TODO: Use timer API to manage time!
        // CREATE MessageManager
        $command = "ranks setpermission " . $userName . " blazinfly.command " . $time . "m";
        //var_dump($command);
        $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), $command);
        $player->sendMessage(MessageManager::flyUsage());
        Main::$main->getScheduler()->scheduleRepeatingTask(new FlyTicketTask($player, (int) $second), 20 * 1);
    }
}
