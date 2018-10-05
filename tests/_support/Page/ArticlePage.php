<?php
namespace Page;

class ArticlePage extends BasePage
{
  // include url of current page
  public $url = '';

  protected $widgetSubscribeInput = '.widget form[action="https://feedburner.google.com/fb/a/mailverify"] input[name="email"]';
  protected $widgetSubscribeSubmit = '.widget form[action="https://feedburner.google.com/fb/a/mailverify"] input[type="image"]';

  public function submitSubscription($email)
  {
    $this->agent->fillField($this->widgetSubscribeInput, $email);
    $this->agent->click($this->widgetSubscribeSubmit);

    $this->agent->switchToWindow('popupwindow');
    return new FeedBurnerPage($this->agent);
  }
}
