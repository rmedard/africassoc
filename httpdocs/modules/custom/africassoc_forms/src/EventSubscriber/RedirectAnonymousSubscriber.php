<?php

namespace Drupal\africassoc_forms\EventSubscriber;


use Drupal;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;

class RedirectAnonymousSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  private $account;

  public function __construct() {
    $this->account = Drupal::currentUser();
  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkAuthStatus'];
    return $events;
  }

  public function checkAuthStatus() {
    if ($this->account->isAnonymous() && Drupal::service('path.current')
        ->getPath() == '/node/add/event') {
      Drupal::messenger()->addStatus('User Id: ' . $this->account->id());
      $response = new RedirectResponse('/event-creator-register-form', 302);
      $response->send();
    }
  }

}
