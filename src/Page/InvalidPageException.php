<?php declare(strict_types = 1);

namespace Example\Page;

use Exception;

class InvalidPageException extends Exception
{
  public function __construct($slug, $code = 0, Exception $previous = null)
  {
    $message = "Страница со словом `$slug` не найдена";
    parent::__construct($message, $code, $previous);
  }
}