[<< Предыдущая тема](04-http.md) | [Следующая тема >>](06-dispatching-to-a-class.md)

### Маршрутизатор

Маршрутизатор отправляет запросы к различным обработчикам в зависимости от установленных правил.

При текущей настройке не имеет значения, какой `URL` используется для доступа к приложению, ответ всегда будет одинаковым. Поэтому давайте сейчас это исправим.

В этом руководстве я буду использовать [FastRoute](https://github.com/nikic/FastRoute). Но, как всегда, вы можете выбрать свой любимый пакет.

В качестве альтернативы вы можете попробовать следующие пакеты: [symfony/Routing](https://github.com/symfony/Routing), [Aura.Router](https://github.com/auraphp/Aura.Router), [fuelphp/routing](https://github.com/fuelphp/routing), [Klein](https://github.com/chriso/klein.php)

Вы уже наверное научились устанавливать сторонние библиотеки с помощью `Composer`, попробуйте сделать это сами.

Теперь добавьте указанный ниже блок кода в файл `Bootstrap.php` вместо строчки где мы выводили приветствие 'Привет мир!' в предыдущих главах.

```php
$dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/hello-world', function () {
        echo 'Привет мир!';
    });
    $r->addRoute('GET', '/another-route', function () {
        echo 'Это тоже работает';
    });
});

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        call_user_func($handler, $vars);
        break;
}
```

В первой части кода происходит регистрация доступных маршрутов для вашего приложения. Во второй части вызывается диспетчер и выполняется соответствующая часть оператора switch. Если маршрут был найден, то будет выполнен вызов обработчика.

Но такая схема может подойти для небольших приложений. Как только вы начнете добавлять больше маршрутов, ваш bootstrap-файл быстро разрастется и станет нечитабельным. Поэтому давайте вынесем их в отдельный файл.

Создайте файл `Routes.php` в папке `src/`. Он должен выглядеть следующим образом:

```php
<?php declare(strict_types = 1);

return [
    ['GET', '/hello-world', function () {
        echo 'Привет мир!';
    }],
    ['GET', '/another-route', function () {
        echo 'Это тоже работает';
    }],
];
```

Теперь перепишем часть диспетчера маршрутов, чтобы использовать файл `Routes.php`.

```php
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
```

Стало намного лучше, но теперь весь код обработчика находится в файле `Routes.php`. Это неоптимально, поэтому в следующей части мы это исправим.

Не забывайте фиксировать изменения в конце каждой главы.

[<< Предыдущая тема](04-http.md) | [Следующая тема >>](06-dispatching-to-a-class.md)
