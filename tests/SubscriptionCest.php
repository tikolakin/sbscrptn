<?php
class SubscriptionCest
{
  /**
   * @var \Page\ArticlePage
   */
  protected $page;

  /**
   * @var \AcceptanceTester
   */
  protected $agent;

  /**
   * @var \Faker\Generator
   */
  protected $faker;

  // _inject doesn't work, see #4325
  public function _before(AcceptanceTester $agent, \Page\ArticlePage $page)
  {
    $this->agent = $agent;
    $this->page = $page;
    // @TODO Keep fake info in the actor object
    $this->faker = \Faker\Factory::create();
  }

  /**
   * @dataProvider pageProvider
   * @param \Codeception\Example $example
   */
  public function userCanSubscribeOnTheSite(\Codeception\Example $example)
  {
    $email = $this->faker->email;

    $this->page->open($example['url']);
    $feedBurner = $this->page->submitSubscription($email);

    $this->agent->see('Email Subscription Request', $feedBurner->title);

    $this->agent->see(
      'FeedBurner activates your subscription to “Company Folders” once you respond to this verification message.',
      $feedBurner->form
    );

    $actualEmail = $this->agent->grabValueFrom($feedBurner->emailField);
    \PHPUnit\Framework\Assert::assertEquals($email, $actualEmail);
  }


  /**
   * @return array
   */
  protected function pageProvider()
  {
    return [
      ['url'=>"/folder-design-services"],
      ['url'=>"/blog"],
    ];
  }

}
