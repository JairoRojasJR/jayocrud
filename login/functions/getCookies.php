<?php
function getCookies()
{
  $cookiesSort = (object) [];
  if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach ($cookies as $cookie) {
      $cookie = explode('=', trim($cookie));
      $cookieKey = $cookie[0];
      $cookieValue = $cookie[1];
      $cookiesSort->$cookieKey = $cookieValue;
    };
  }
  return $cookiesSort;
}
