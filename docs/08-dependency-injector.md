[<< Предыдущая тема](07-inversion-of-control.md) | [Следующая тема >>](09-templating.md)

### Внедрение зависимости

Инжектор зависимостей разрешает зависимости вашего класса и следит за тем, чтобы при инстанцировании класса были инжектированы нужные объекты.

Я могу порекомендовать только один инжектор: [Auryn](https://github.com/rdlowrey/Auryn). К сожалению, все известные мне альтернативы используют в своей документации и примерах [антипаттерн service locator](http://blog.ploeh.dk/2010/02/03/ServiceLocatorisanAnti-Pattern/).

Установите пакет Auryn и создайте новый файл `Dependencies.php` в папке `src/`. Добавьте в него следующий код:

```php
<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

return $injector;
```

Прежде чем продолжить, убедитесь, что вы понимаете, что делают `alias`, `share` и `define`. Вы можете прочитать о них в [документации к Auryn](https://github.com/rdlowrey/Auryn).

Вы совместно используете HTTP-объекты, поскольку нет особого смысла добавлять содержимое в один объект, а затем возвращать другой. Поэтому при совместном использовании вы всегда получаете один и тот же экземпляр.

Псевдоним позволяет вводить подсказку интерфейса вместо имени класса. Это позволяет легко менять реализацию без необходимости возвращаться и редактировать все классы, использующие старую реализацию.

Разумеется, потребуется изменить и файл `Bootstrap.php`. Раньше вы создавали `$request` и `$response` используя вызов `new`. Теперь переключите их на инжектор, чтобы везде использовать один и тот же экземпляр этих объектов.

```php
$injector = include('Dependencies.php');

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');
```

Еще необходимо изменить диспетчеризацию маршрута. Раньше мы использовали следующий код:

```php
$class = new $className($response);
$class->$method($vars);
```

Исправьте его так, как показано ниже:

```php
$class = $injector->make($className);
$class->$method($vars);
```

Теперь все зависимости конструктора контроллера будут автоматически разрешены с помощью Auryn.

Перепишите код контроллер `Homepage`:

```php
<?php declare(strict_types = 1);

namespace Example\Controllers;

use Http\Request;
use Http\Response;

class Homepage
{
    private $request;
    private $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function show()
    {
        $content = '<h1>Hello World</h1>';
        $content .= 'Hello ' . $this->request->getParameter('name', 'stranger');
        $this->response->setContent($content);
    }
}
```

Как видно, теперь класс имеет две зависимости. Попробуйте обратиться к странице с параметром GET `http://localhost:8000/?name=Arthur%20Dent`.

Поздравляем, теперь вы успешно заложили основу для своего приложения. 

[<< Предыдущая тема](07-inversion-of-control.md) | [Следующая тема >>](09-templating.md)
