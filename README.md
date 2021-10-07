# SilverStripe Articles

## Introduction
Article page type, can be used as news system or as a blog

## Requirements
* silverstripe/cms ^4.0
* silverstripe/lumberjack ^2.0

## Installation
```
composer require thewebmen/silverstripe-articles
```

## PHPCS Fixer/PHPStan
PHPCSFixer and PHPStan are included to resolve codestyle fixes and do static analytic on the code.

You can run the following commands in your **project root directory**.

### PHP CodeStyle fixer
Dry-run to spot codestyle issues:\
`./vendor/bin/php-cs-fixer fix ./vendor/thewebmen/silverstripe-articles --diff --dry-run`\
Fix issues:\
`./vendor/bin/php-cs-fixer ./vendor/thewebmen/silverstripe-articles fix`

## Maintainers
* [Webmen](https://www.webmen.nl/) <development@webmen.nl>

## Development and contribution
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.\
See read our [contributing](CONTRIBUTING.md) document for more information.
