# Console commands

## Setup
To enable console commands, you need to add module into console config of you app.
`/config/console.php` in yii2-app-basic template, or `/console/config/main.php` in yii2-app-advanced.

```php

    return [
        'id' => 'app-console',
        'modules' => [
            'user' => [
                'class' => 'akiraz2\support\Module',
            ],
        ],
        ...

```

## Available console actions

- **ticket/close** auto close ticket.


