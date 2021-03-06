<?php

class Session {
  public function __construct($accessToken = null) {
    if ($accessToken !== null) {
      $this->set('accessToken', $accessToken);
    }
  }

  public function id() {
    if (!$this->get('id')) {
      $this->set('id', $this->fetchId());
    }
    return $this->get('id');
  }

  public function isSignedIn() {
    return $this->get('accessToken') !== null;
  }

  public function signOut() {
    $this->set('accessToken', null);
    $this->set('id', null);
  }

  public function accessToken() {
    return $this->get('accessToken');
  }

  private function get($key) {
    $actualKey = C()->sessionprefix() . $key;
    if (!isset($_SESSION[$actualKey])) {
      return null;
    }
    return $_SESSION[$actualKey];
  }

  private function set($key, $value) {
    $_SESSION[C()->sessionprefix() . $key] = $value;
  }

  private function fetchId() {
    if ($this->get('accessToken') === null) {
      return null;
    }
    $me = (new Facebook\FacebookRequest(
      new Facebook\FacebookSession($this->get('accessToken')), 'GET', '/me'
    ))->execute()->getGraphObject(Facebook\GraphUser::className());
    return $me->getId();
  }
}
