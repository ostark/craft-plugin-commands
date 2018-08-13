<?php

namespace ostark\PluginCommands;

use Craft;
use craft\helpers\ArrayHelper;
use craft\helpers\Console;
use yii\base\InvalidArgumentException;
use yii\console\Controller as BaseConsoleController;
use yii\console\widgets\Table;


/**
 * Plugin Commands
 */
class Commands extends BaseConsoleController
{
    public $defaultAction = 'list';

    /**
     * @var string Limit execution to these environments, separate multiple with comma.
     */
    public $envOnly = null;

    protected $actionVerbMap = [
        'installPlugin'   => 'installed',
        'uninstallPlugin' => 'uninstalled',
        'enablePlugin'    => 'enabled',
        'disablePlugin'   => 'disabled'
    ];


    public function options($actionID)
    {
        return ['envOnly'];
    }

    /**
     * Before Action filter
     *
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!$this->environmentCheck($this->envOnly)) {
            $currentEnv = Craft::$app->env;
            $this->stdout("Skip command 'craft plugin/{$action->id}' - wrong env: '{$currentEnv}'" . PHP_EOL, Console::FG_YELLOW);

            return false;
        }

        return true;

    }


    /**
     * List all plugins
     *
     * @throws \Exception
     */
    public function actionList()
    {
        $plugins = Craft::$app->getPlugins()->getAllPluginInfo();
        $count   = count($plugins);
        $rows    = [];

        if (0 === $count) {
            $this->stdout("No plugins", Console::FG_RED);
        }

        $this->stdout(PHP_EOL . "$count plugins found" . PHP_EOL, Console::FG_CYAN);
        foreach ($plugins as $handle => $plugin) {

            $rows[] = [
                "$handle",
                $plugin['version'],
                $plugin['developer'],
                ($plugin['isInstalled']) ? (Craft::$app->getPlugins()->isPluginEnabled($handle) ? 'Enabled' : 'Disabled') : 'Not installed'
            ];
        }

        echo Table::widget([
            'headers' => ['Handle', 'Version', 'Developer', 'Status'],
            'rows'    => $rows,
        ]);

    }

    /**
     * Install & enable
     *
     * @param string $plugin
     */
    public function actionInstall($plugin = 'ALL')
    {
        if ($plugin === 'ALL') {
            $handles = $this->filterPlugins(['isInstalled' => false]);
        } else {
            $handles = [$plugin];
        }

        $this->apply('installPlugin', $handles);

    }

    /**
     * Uninstall
     *
     * @param string $plugin
     */
    public function actionUninstall($plugin = 'ALL')
    {
        if ($plugin === 'ALL') {
            $handles = $this->filterPlugins(['isInstalled' => true, 'isEnabled' => true]);
        } else {
            $handles = [$plugin];
        }

        $this->apply('uninstallPlugin', $handles);
    }

    /**
     * Enable
     *
     * @param string $plugin
     */
    public function actionEnable($plugin = 'ALL')
    {
        if ($plugin === 'ALL') {
            $handles = $this->filterPlugins(['isInstalled' => true, 'isEnabled' => false]);
        } else {
            $handles = [$plugin];
        }

        $this->apply('enablePlugin', $handles);
    }

    /**
     * Disable
     *
     * @param string $plugin
     */
    public function actionDisable($plugin = 'ALL')
    {
        if ($plugin === 'ALL') {
            $handles = $this->filterPlugins(['isInstalled' => true, 'isEnabled' => true]);
        } else {
            $handles = [$plugin];
        }

        $this->apply('disablePlugin', $handles);
    }


    /**
     * Get filtered plugin handles
     *
     * @param array $filters
     *
     * @return array
     */
    protected function filterPlugins($filters = [])
    {
        $plugins = Craft::$app->getPlugins()->getAllPluginInfo();

        if (count($plugins) == 0) {
            return [];
        }

        foreach ($filters as $key => $value) {
            $plugins = ArrayHelper::filterByValue($plugins, $key, $value);
        }

        return array_keys($plugins);

    }

    /**
     * Calls a method on craft\services\Plugins for multiple plugins
     * and outputs a success/error message
     *
     * @param       $pluginAction
     * @param array $handles
     */
    protected function apply($pluginAction, $handles = [])
    {
        if (!in_array($pluginAction, array_keys($this->actionVerbMap))) {
            throw new InvalidArgumentException("Unknown action '$pluginAction'");
        }

        if (!$handles) {
            $this->stdout("Nothing to do" . PHP_EOL, Console::FG_YELLOW);

            return;
        }

        $verb = ucfirst($this->actionVerbMap[$pluginAction]);

        foreach ($handles as $handle) {
            try {
                $handleBold = $this->ansiFormat($handle, Console::BOLD);
                Craft::$app->plugins->$pluginAction($handle);
            } catch (\Throwable $e) {
                $this->stderr("> {$handleBold}: {$e->getMessage()}" . PHP_EOL, Console::FG_RED);

                return;
            }

            $this->stdout("> {$verb} {$handleBold} successfully" . PHP_EOL, Console::FG_GREEN);
        }
    }


    /**
     * @param string|null $allowedEnvironment
     *
     * @return bool
     */
    protected function environmentCheck(string $allowedEnvironment = null)
    {
        // No env limitations, continue
        if (is_null($allowedEnvironment) || strlen($allowedEnvironment) == 0) {
            return true;
        }

        $allowedEnvs = array_map('trim', explode(',', $allowedEnvironment));

        // No match
        if (!in_array(Craft::$app->env, $allowedEnvs)) {
            return false;
        }

        // Match, continue
        return true;

    }

}
