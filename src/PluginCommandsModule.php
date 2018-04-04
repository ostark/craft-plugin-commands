<?php

namespace ostark\PluginCommands;


use Craft;
use craft\console\Application as ConsoleApplication;
use yii\base\Module;

/**
 * Class PluginCommandsModule
 *
 * @author    ostark
 * @package   PluginCommandsModule
 * @since     1.0.0
 *
 */
class PluginCommandsModule extends Module
{
    /**
     * @var PluginCommandsModule
     */
    public static $instance;

    /**
     * @var string Module id
     */
    public $id = 'PluginCommands';


    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@PluginCommands', $this->getBasePath());
        $this->controllerNamespace = 'PluginCommands';

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        if (Craft::$app instanceof ConsoleApplication) {

            // Register console commands
            Craft::$app->controllerMap['plugin'] = Commands::class;

        }


    }

}
