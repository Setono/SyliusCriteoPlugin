<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusCriteoPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Setono\SyliusCriteoPlugin\Model\Account;
use Setono\SyliusCriteoPlugin\Repository\AccountRepositoryInterface;
use Sylius\Component\Channel\Context\CachedPerRequestChannelContext;
use Tests\Setono\SyliusCriteoPlugin\Behat\Page\Shop\HomePage;
use Webmozart\Assert\Assert;

final class TrackingContext implements Context
{
    /**
     * @var HomePage
     */
    private $homePage;

    /**
     * @var AccountRepositoryInterface
     */
    private $accountRepository;

    /**
     * @var CachedPerRequestChannelContext
     */
    private $channelContext;

    public function __construct(HomePage $homePage, AccountRepositoryInterface $accountRepository, CachedPerRequestChannelContext $channelContext)
    {
        $this->homePage = $homePage;
        $this->accountRepository = $accountRepository;
        $this->channelContext = $channelContext;
    }

    /**
     * @Given a criteo account with account id :accountId is made for current channel
     */
    public function aCriteoAccountWithAccountIdIsMadeForCurrentChannel($accountId)
    {
        $account = new Account();
        $account->setChannel($this->channelContext->getChannel());
        $account->setAccountId($accountId);
        $account->enable();
        $this->accountRepository->add($account);
    }

    /**
     * @When a customer visits the home page
     */
    public function aCustomerVisitsTheHomePage(): void
    {
        $this->homePage->open();
    }

    /**
     * @When there is no criteo account for current channel
     */
    public function thereIsNoCriteoAccountForCurrentChannel()
    {
        Assert::null($this->accountRepository->findOneByChannel($this->channelContext->getChannel()));
    }

    /**
     * @When there is an enabled criteo account for current channel
     */
    public function thereIsAnEnabledCriteoAccountForCurrentChannel()
    {
        Assert::notNull($this->accountRepository->findOneByChannel($this->channelContext->getChannel()));
    }

    /**
     * @When there is an disabled criteo account for current channel
     */
    public function thereIsAnDisabledCriteoAccountForCurrentChannel()
    {
        Assert::null($this->accountRepository->findOneByChannel($this->channelContext->getChannel()));
    }

    /**
     * @Then he will find the tracking library with account id :accountId in the code
     */
    public function heWillFindTheTrackingLibraryWithAccountIdInTheCode($accountId)
    {
        Assert::true($this->homePage->hasLibrary($accountId));
    }

    /**
     * @Then he will not find the tracking library in the code
     */
    public function heWillNotFindTheTrackingLibraryInTheCode()
    {
        Assert::false($this->homePage->hasLibrary());
    }
}
