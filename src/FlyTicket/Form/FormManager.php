<?php

namespace FlyTicket\Form;

use FlyTicket\Main;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use davidglitch04\libEco\libEco;
use jojoe77777\FormAPI\ {
    CustomForm,
    SimpleForm
};

class FormManager {

    /**
    * @param Player $player
    */
    public function FlyTicketForm(Player $player) {
    $form = new CustomForm(function(Player $player, $data) {
        $minute = $data[1];
        $second = $minute * 60;
        $price = $minute * Main::$config->get("Price");
       # var_dump($price);
       # var_dump($minute);
        $message = str_replace(["{total}", "{minute}"], [$price, $minute], Main::$config->get("message"));
      # var_dump($message);
        $item_name = Main::$config->get("ItemName");
        $item = ItemFactory::getInstance()->get(ItemIds::FEATHER, 0, 1)->setCustomName("§a" . $item_name)->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(0), 10));
        $item->getNamedTag()->setInt("second", $second);
        $item->getNamedTag()->setString("date", date("d-m-Y-H-i-s-"));
        
        $lore = ["§b" . $minute . " §cminute(s) fly ticket"];
        $item->setLore($lore);
        var_dump($lore);
        $priceToSend = $price;
        $minuteToSend = $minute;

        libEco::reduceMoney($player, $price, function(bool $success) use ($player, $priceToSend, $minuteToSend, $message, $item) : void {
            if($success){
                if (!$player->getInventory()->canAddItem($item)) {
                    $player->sendMessage("§cYour Inventory is full!");
                    return;
                }
                $player->sendMessage($message);
                $player->getInventory()->addItem($item);
            } else{
                $player->sendMessage("§cYou don't have enough money!");
            }
        });
    });
    $min = Main::$config->get("Min");
    $max = Main::$config->get("Max");
    $content = Main::$config->get("content");
    $price = Main::$config->get("Price");
    $label = $content.$price;
    $title = Main::$config->get("FormTitle");
    $form->setTitle($title);
    $form->addLabel($label);
    $form->addSlider("§eSelect Minute",$min,$max,$min);
    $player->sendForm($form);
  }
}