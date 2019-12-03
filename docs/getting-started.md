# Getting started with Yii2-ticket-support

Yii2-ticket-support is designed to work out of the box for yii2-advanced-template. It means that installation requires
minimal steps. Only one configuration step should be taken and you are ready to
have user management on your Yii2 website.

### 1. Download

Yii2-ticket-support can be installed using composer. Run following command to download and
install Yii2-ticket-support:

```bash
composer require akiraz2/Yii2-ticket-support "dev-master"
```

### 2. Configure

Add following lines to your main configuration file:

```php
'modules' => [
    'support' => [
        'class' => 'akiraz2\support\Module',
        'userModel' => 'common\models\User',
        'yii2basictemplate' => false,
    ],
],
```

### 3. Update database schema

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

Module uses namespaced migrations, so change it in `console\config\main.php`:

```
return [
    'id' => 'app-console',
    
    'controllerMap' => [        
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                //'console\migrations',                
                'akiraz2\support\migrations'
            ],
        ],
    ],
```

```bash
$ php yii migrate
```

## Where do I go now?

You have Yii2-ticket-support installed. Now you can check out the [list of articles](README.md)
for more information.

## Troubleshooting

If you're having troubles with Yii2-ticket-support, make sure to check out the 
[troubleshooting guide](troubleshooting.md).
