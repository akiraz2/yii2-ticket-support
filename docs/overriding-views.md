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

## Example

An example of overriding the registration page view is demonstrated below. First make sure you have configured view
application component.

In order to override the registration view file you should create `@app/views/support/ticket/index.php`. Open it
and paste in the following code:


Then open registration page and make sure that you see **'This view file has been overriden!'**. If you don't see it
make sure you have properly configured your view component and created view file in right location.
