namespace SkullyGamingDM\RulesUI;

use JackMD\UpdateNotifier\UpdateNotifier;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        if ($this->getConfig()->get("config-version") !== 1.2) {
            $this->getLogger()->notice("Your configuration file is outdated, updating the config.yml...");
            $this->getLogger()->notice("The old configuration file can be found at config_old.yml");
            rename($this->getDataFolder()."config.yml", $this->getDataFolder()."config_old.yml");
            $this->saveResource("config.yml");
        }
        $this->getServer()->getCommandMap()->register("RulesUI", new Commands(
            $this, $this->getConfig()->get("command-desc"),
            $this->getConfig()->get("command-aliases")
        ));
        if ($this->getConfig()->get("check-updates", true)) {
            UpdateNotifier::checkUpdate($this->getDescription()->getName(), $this->getDescription()->getVersion());
        }
    }

    public function RulesUI(Player $player) {
        $form = new SimpleForm(function (Player $player, int $data = null) {
            if ($data === null) {
                return true;
            }
        });
        $title = str_replace("{player}", $player->getName(), $this->getConfig()->get("title"));
        $content = str_replace("{player}", $player->getName(), $this->getConfig()->get("content"));
        $button = str_replace("{player}", $player->getName(), $this->getConfig()->get("button"));
        $form->setTitle($title);
        $form->setContent($content);
        $form->addButton($button);
        $player->sendForm($form);
    }

}
