<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage as BaseHomePage;

class HomePage extends BaseHomePage
{
    public function hasLibrary(string $accountId = ''): bool
    {
        $libResult = strpos($this->getContents(), '<script src="//static.criteo.net/js/ld/ld.js" defer></script>');
        if($libResult === false) {
            return false;
        }

        $accountResult = strpos($this->getContents(), '"setAccount", account: ' . $accountId . ' }');
        if($accountResult === false) {
            return false;
        }

        return true;
    }
}
