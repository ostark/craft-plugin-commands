<?php

namespace ostark\PluginCommands;

use craft\console\Application as CraftConsoleApp;
use yii\base\BootstrapInterface;

/**
 * Class BootstrapPluginCommands
 *
 * @author Oliver Stark <os@fortrabbit.com>
 * @since  1.0.0
 */
class BootstrapPluginCommands implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {

        if ($app instanceof CraftConsoleApp) {

            // Register console commands
            \Craft::$app->controllerMap['plugin'] = Commands::class;

        }
    }
}
