<?php

namespace FlyTicket\Task;

use FlyTicket\Main;
use FlyTicket\Manager\FlyTicketManager;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class FlyTicketTask extends Task
{
    public $time;
    public $player;

    public function __construct(Player $player, int $time){
        $this->time = $time;
        $this->player = $player;
    }
    public function onRun(): void
    {
        $player = $this->player;
        if ($player instanceof Player) {
            if ($player->isOnline()) {
                $this->time--;
                $time = gmdate("i:s", $this->time);
                $exp = explode(":", $time);
                $min = (int)$exp[0];
                $second = (int)$exp[1];
                if ($min < 10) str_replace(["0"], [""], $min);
                $exp = explode(":", $time);
                $min = (int)$exp[0];
                $second = (int)$exp[1];
                if ($min < 10) str_replace(["0"], [""], $min);

                if ($exp[0] === 00) {
                    $format = "§f" . $second . " §bsecond";
                } else {
                    $format = "§f" . $min . " §bminutes §f" . $second . " §bsecond";
                }
                if (!$player->getAllowFlight()) {
                    $player->setAllowFlight(true);
                }
                $cfgformat = str_replace(["{minute}", "{second}"], [$min, $second], Main::$config->get("fly-message"));
                $player->sendActionBarMessage($cfgformat);
                $second = str_replace("{second}", $this->time, Main::$config->get("warning-Title"));
                $second_array = [60, 10, 5, 4, 3, 2, 1];
                if (in_array($this->time, $second_array)) {
                    $player->sendTitle($second);
                    $player->getNetworkSession()->sendDataPacket(PlaySoundPacket::create("note.pling", $player->getPosition()->getX(), $player->getPosition()->getY(), $player->getPosition()->getZ(), 2.0, 2.0));
                }
                if ($this->time === 0) {
                    $player->setAllowFlight(false);
                    if ($player->isFlying()) $player->setFlying(false);
                    unset(FlyTicketManager::$array[$player->getName()]);
                    $player->sendTitle("§cFly Ticket expired.");
               #     $format = $player->getDisplayName();
                 #   $player->setNameTag($format);
                    $player->teleport($player->getWorld()->getSafeSpawn());
                    $this->getHandler()->cancel();
                }
            } else {
                $this->getHandler()->cancel();

            }
        }
    }
}