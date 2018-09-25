# Mailer

Yii2-ticket-support includes special component named Mailer, which is used to send emails in four different instances:


## Configuration

Mailer can be configured as followed:

```php
...
'support' => [
    'class' => 'akiraz2\support\Module',
    'mailer' => [
        'sender' => 'support@myhost.com', // or ['support@myhost.com' => 'Technical Support']  
],
...
```