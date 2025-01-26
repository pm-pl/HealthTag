<?php

declare(strict_types=1);

namespace shelly7w7\HealthTag;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

	use SingletonTrait;

	public array $configdata = [];

	public function onEnable(): void {
		self::setInstance($this);
		$this->getServer()->getCommandMap()->register("healthtag", new HealthTagCommand($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

		$config = $this->getConfig();
		$config->reload();
		$this->configdata = $this->getConfig()->getAll();
	}

	public function onDisabel(): void {
		$this->getConfig()->setAll($this->configdata);
		$this->getConfig()->save();
	}

	public function updateScoreTag(Player $player): void{
		if($this->configdata["type"] === "custom") {
			$player->setScoreTag(str_replace(["{health}", "{maxhealth}"], [$player->getHealth(), $player->getMaxHealth()], $this->configdata["customformat"]));
		} else if($this->configdata["type"] === "bar") {
			$player->setScoreTag(str_repeat("§a|", (int)round($player->getHealth(), 0)) . str_repeat("§c|", (int)round($player->getMaxHealth() - $player->getHealth(), 0)));
		} else {
			$player->setScoreTag("Invalid type chosen for healthtag");
		}
	}

}
