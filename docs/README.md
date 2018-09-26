# Guide to Yii2-ticket-support

## Getting Started

- [Installation](getting-started.md)
- Configuration - not ready
- [Console commands](console.md)

## Usage

Available urls for admins:
- `/support/default/index` - start page for admin
- `/support/category/index`
- `/support/ticket/manage`

Start page for users

- `/support/ticket/index`

## Overriding

- [Overriding views](overriding-views.md)

## Basics

- [Mailer](mailer.md)

## Translations

Please translate to your language! Edit config (or copy to your path) `@vendor/akiraz2/yii2-blog/src/messages/config.php`, add your language and run script:
```php
php ./yii message/extract @vendor/akiraz2/yii2-ticket-support/messages/config.php
```
translate file will be in `@vendor/akiraz2/yii2-ticket-support/messages/` or your configured path


## RBAC

- Not ready
