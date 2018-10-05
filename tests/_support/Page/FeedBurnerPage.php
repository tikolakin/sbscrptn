<?php
namespace Page;

class FeedBurnerPage extends BasePage
{
  // this page is not supposed to be opened directly. Extend when needed.
  public $url = 'https://feedburner.google.com/fb/a/mailverify';

  protected $title = 'h2';

  protected $form = 'form[name="emailSyndicationVerificationForm"]';

  protected $emailField = 'input[name="email"]';

}
