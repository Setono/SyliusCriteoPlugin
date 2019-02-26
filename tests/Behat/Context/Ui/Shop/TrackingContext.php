<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\Setono\SyliusCriteoPlugin\Behat\Page\Shop\HomePage;
use Webmozart\Assert\Assert;

final class TrackingContext implements Context
{
    /**
     * @var HomePage
     */
    private $homePage;

    public function __construct(HomePage $homePage)
    {
        $this->homePage = $homePage;
    }

    /**
     * @When a customer visits the home page
     */
    public function aCustomerVisitsTheHomePage(): void
    {
        $this->homePage->open();
    }

    /**
     * @Then he will find the tracking library in the code
     */
    public function heWillFindTheTrackingLibraryInTheCode(): void
    {
        Assert::true($this->homePage->hasLibrary());
    }
}
