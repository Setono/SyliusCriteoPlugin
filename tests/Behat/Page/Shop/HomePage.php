<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\Behat\Page\Shop;

use Sylius\Behat\Page\Shop\HomePage as BaseHomePage;

class HomePage extends BaseHomePage
{
    public function hasLibrary(): bool
    {
        $res = strpos($this->getContents(), '<script src="//static.criteo.net/js/ld/ld.js" defer></script>');

        return !($res === false);
    }
}
