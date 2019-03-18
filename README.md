# Sylius Criteo Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]

Sylius plugin that integrates Criteo tracking scripts

## Installation

### Step 1: Download the plugin

Open a command console, enter your project directory and execute the following command to download the latest stable version of this plugin:

```bash
# Omit setono/sylius-tag-bag-plugin if you want to
# override layout.html.twig as described at https://github.com/Setono/TagBagBundle#usage
$ composer require setono/sylius-criteo-plugin setono/sylius-tag-bag-plugin
```

This command requires you to have Composer installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

### Step 2: Enable the plugin

Then, enable the plugin by adding it to the list of registered plugins/bundles
in the `config/bundles.php` file of your project:

```php
<?php
# config/bundles.php
return [
    Setono\TagBagBundle\SetonoTagBagBundle::class => ['all' => true],

    // Use this bundle or override layout.html.twig as described at https://github.com/Setono/TagBagBundle#usage
    Setono\SyliusTagBagPlugin\SetonoSyliusTagBagPlugin::class => ['all' => true],

    Setono\SyliusCriteoPlugin\SetonoSyliusCriteoPlugin::class => ['all' => true],
];
```

### Step 3: Setup account

Login to your Sylius app admin and go to the Criteo page and click "Create" to create a new account. Fill in the account id of your Criteo account, make sure "enable" is toggled on, and choose which channel the Criteo account should be applied to. Please notice you should only make one account for each channel, or else you will end up with undefined behaviour.

[ico-version]: https://img.shields.io/packagist/v/setono/sylius-criteo-plugin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.com/Setono/SyliusCriteoPlugin.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusCriteoPlugin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/setono/sylius-criteo-plugin
[link-travis]: https://travis-ci.com/Setono/SyliusCriteoPlugin
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusCriteoPlugin
