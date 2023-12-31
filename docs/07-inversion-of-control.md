[<< Предыдущая тема](06-dispatching-to-a-class.md) | [Следующая тема >>](08-dependency-injector.md)

### Инверсия управления

В прошлой части вы создали класс контроллера и сгенерировали вывод с помощью `echo`. Но не будем забывать, что у нас есть хорошая объектно-ориентированная абстракция HTTP. Но сейчас она недоступна внутри вашего класса.

Разумным вариантом является использование [инверсии управления](http://ru.wikipedia.org/wiki/Инверсия_управления). Это означает, что вместо того, чтобы возлагать на класс ответственность за создание необходимого ему объекта, вы просто просите его об этом. Это делается с помощью [внедрения зависимости](http://ru.wikipedia.org/wiki/Внедрение_зависимости).

Если сейчас это звучит несколько сложно, не волнуйтесь. Просто следуйте этому руководству, и как только вы увидите, как это делается, все сразу станет понятно.

Измените контроллер `Homepage` следующим образом:

```php
<?php declare(strict_types = 1);

namespace Example\Controllers;

use Http\Response;

class Homepage
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function show()
    {
        $this->response->setContent('Привет мир!');
    }
}
```

Обратите внимание, что мы [импортируем](https://www.php.net/manual/ru/language.namespaces.importing.php) `Http\Response` в верхней части файла. Это означает, что всякий раз, когда вы будете использовать `Response` внутри этого файла, он будет преобразовываться в полное имя.

В конструкторе мы теперь явно запрашиваем `Http\Response`. В данном случае, `Http\Response` это интерфейс. Поэтому любой класс, реализующий этот интерфейс, может быть инжектирован. Для справки см. раздел [Подсказка типов](http://php.net/manual/en/language.oop5.typehinting.php) и [Интерфейсы объектов](http://php.net/manual/en/language.oop5.interfaces.php). 

Теперь код приведет к ошибке, поскольку на самом деле мы ничего не инжектируем. Поэтому давайте исправим это в файле `Bootstrap.php`, где мы выполняем диспетчеризацию при обнаружении маршрута:

```php
$class = new $className($response);
$class->$method($vars);
```

Объект `Http\HttpResponse` реализует интерфейс `Http\Response`, поэтому он удовлетворяет условиям контракта и может быть использован.

Теперь все снова должно работать. Но если вы последуете этому примеру, то во все ваши объекты, инстанцированные таким образом, будут внедряться одни и те же объекты. Это, конечно, нехорошо, поэтому в следующей части мы это исправим.

[<< Предыдущая тема](06-dispatching-to-a-class.md) | [Следующая тема >>](08-dependency-injector.md)
