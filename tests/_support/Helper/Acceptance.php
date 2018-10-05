<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
  private $webDriverModule;
  private $webDriver;

  /**
   * Event hook before a test starts.
   *
   * @throws \Exception
   */
  public function _before(\Codeception\TestInterface $test)
  {
    // Use WebDriver
    if ($this->hasModule('WebDriver')) {
      $this->webDriverModule = $this->getModule('WebDriver');
      $this->webDriver = $this->webDriverModule->webDriver;
    }
  }

  /**
   * Wait page to laod
   *
   * @param $timeout
   */
  public function waitPageLoad($timeout = 5)
  {
    if ($this->webDriverModule) {
      $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
      $this->webDriverModule->waitForJS(
        'return (typeof(jQuery) === "undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))',
        $timeout
      );
    }
  }

  /**
   *
   */
  public function switchToWindow($name)
  {
    // sometimes browser slow to open a new tab
    $this->spin(function() {
      // for basic usage check for more than 1 tab is enough. Extend when needed.
      return 1 < count($this->webDriver->getWindowHandles());
    });
    $this->webDriver->switchTo()->window($name);
  }

  /**
   * @param     $lambda
   * @param int $wait
   *
   * @return mixed
   * @throws \Exception
   */
  private function spin(callable $lambda, $wait = 5)
  {
    $exception_message = '';
    for ($i = 0; $i < $wait; $i++) {
      try {
        $returnVal = $lambda($this);
        if ($returnVal) {
          return $returnVal;
        }
      } catch (\Exception $e) {
        $exception_message = $e->getMessage();
      }
      sleep(1);
    }

    $backtrace = debug_backtrace();

    $message = $exception_message . PHP_EOL .
      "Timeout thrown by " . $backtrace[1]['class'] .
      "::" . var_export($backtrace[1]['function'], true) . PHP_EOL .
      var_export($backtrace[1]['args'], true) . PHP_EOL;

    if (isset($backtrace[1]['file'])) {
      $message .= $backtrace[1]['file'] . ", line " . $backtrace[1]['line'];
    }
    throw new \Exception($message);
  }

}
