<?php
namespace Page;

use Codeception\Util\Locator;

class BasePage
{

  public $url = '';

  /**
   * @var \AcceptanceTester
   */
  protected $agent;

  public function __construct(\AcceptanceTester $I)
  {
    $this->agent = $I;
  }

  public function __get($element)
  {
    return Locator::firstElement($this->$element);
  }

  public function open($path)
  {
    $this->agent->amOnPage($path);
    $this->agent->waitPageLoad();
    $this->agent->canSeeInCurrentUrl($path);
  }

}
