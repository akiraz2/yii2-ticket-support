# Mailer

Yii2-ticket-support includes special component named Mailer, which is used to send emails in four different instances:


## Configuration

Mailer can be configured as followed:

```php
...
'user' => [
    'class' => 'akiraz2\support\Module',
    'mailer' => [
        'sender'                => 'no-reply@myhost.com', // or ['no-reply@myhost.com' => 'Sender name']
        
],
...
```