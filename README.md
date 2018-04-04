# Plugin Commands for Craft 3

This is intentionally not a plugin, it's a Yii Module :-)

## Install

Require the package:
```
composer require ostark/craft-plugin-commands
```

Register the module in your `config/app.php`:
```
<?php
return [    
    'modules'    => [
        'plugin-commands' => \ostark\PluginCommands\PluginCommandsModule::class

    ],
    'bootstrap' => [
        'plugin-commands'        
    ]
];

```




## Usage

Get a list plugins with status
```
./craft plugin/list 
```

Install & enable ALL or a single plugin
```
./craft plugin/install ALL
./craft plugin/install {plugin-handle}
```

Uninstall  ALL or a single plugin
```
./craft plugin/uninstall ALL
./craft plugin/uninstall {plugin-handle}
```

Disable ALL or a single plugin
```
./craft plugin/disable ALL
./craft plugin/disable {plugin-handle}
```

Enable ALL or a single plugin
```
./craft plugin/enable ALL
./craft plugin/enable {plugin-handle}
```
