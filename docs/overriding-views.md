# Overriding views

When you start using Yii2-ticket-support you will probably find that you need to override the default views provided by the module.
Although view names are not configurable, Yii2 provides a way to override views using themes. To get started you should
configure your view application component as follows:

```php
...
'components' => [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@akiraz2/support/views' => '@app/views/support'
            ],
        ],
    ],
],
...
```

In the above `pathMap` means that every view in @akiraz2/support/views will be first searched under `@app/views/support` and
if a view exists in the theme directory it will be used instead of the original view.

## How to change controller's layout?

You can change controller's layout using `controllerMap` module's property:

```php
        'modules' => [
            'support' => [
                'class' => 'akiraz2\support\Module',
                'controllerMap' => [
                    'ticket' => [
                        'class'  => 'akiraz2\support\controllers\TicketController',
                        'layout' => '//cabinet',//@frontend/views/layouts/cabinet
                    ],
                ],
            ],
],
```