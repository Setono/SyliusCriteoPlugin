<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage as BaseHomePage;

class HomePage extends BaseHomePage
{
    public function hasLibrary(string $accountId = ''): bool
    {
        $libResult = strpos($this->getContent(), '<script src="//static.criteo.net/js/ld/ld.js" defer></script>');
        if (false === $libResult) {
            return false;
        }

        $accountResult = strpos($this->getContent(), '"setAccount", account: ' . $accountId . ' }');

        return !($accountResult === false);
    }
}
