<?php declare(strict_types = 1);

return [
  ['GET', '/hello-world', function () {
    echo 'Привет мир!';
  }],
  ['GET', '/another-route', function () {
    echo 'Это тоже работает';
  }],
];