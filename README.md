# Plugin Commands for Craft 3

This is intentionally not a plugin, it's a Yii Extension.
There is not need to bootstrap or enable it, it auto-registers itself.

## Install

Require the package:

```sh
composer require ostark/craft-plugin-commands
```

## Usage

Get a list of all plugins

```
./craft plugin/list

5 plugins found
╔═══════════════════════════╤════════════╤═══════════════╤═══════════════╗
║ Handle                    │ Version    │ Developer     │ Status        ║
╟───────────────────────────┼────────────┼───────────────┼───────────────╢
║ aws-s3                    │ 1.0.8      │ Pixel & Tonic │ Disabled      ║
╟───────────────────────────┼────────────┼───────────────┼───────────────╢
║ async-queue               │ 1.3.0      │ Oliver Stark  │ Enabled       ║
╟───────────────────────────┼────────────┼───────────────┼───────────────╢
║ fortrabbit-object-storage │ 0.1.0      │ Oliver Stark  │ Not installed ║
╟───────────────────────────┼────────────┼───────────────┼───────────────╢
║ happy-brad                │ v1.2       │ Matt Stauffer │ Enabled       ║
╟───────────────────────────┼────────────┼───────────────┼───────────────╢
║ upper                     │ 1.3.1      │ Oliver Stark  │ Not installed ║
╚═══════════════════════════╧════════════╧═══════════════╧═══════════════╝
```

Install & enable ALL or a single plugin

```
php craft plugin/install ALL
php craft plugin/install {plugin-handle}
```

Uninstall  ALL or a single plugin

```
php craft plugin/uninstall ALL
php craft plugin/uninstall {plugin-handle}
```

Disable ALL or a single plugin

```
php craft plugin/disable ALL
php craft plugin/disable {plugin-handle}
```

Enable ALL or a single plugin

```
php craft plugin/enable ALL
php craft plugin/enable {plugin-handle}
```

Limit execution to specific environments with the `--env-only` flag. 
This option is only useful in automated deployment processes, e.g. `post-install-cmd`s defined in a shared `composer.json`:

```json
{
    "scripts": {
        "post-install-cmd": [
            "php craft plugin/disable {plugin-handle} --env-only=production",
            "php craft plugin/enable {plugin-handle} --env-only=dev,staging"
        ]
    }
}
