[<< Предыдущая тема](09-templating.md) | [Следующая тема >>](11-page-menu.md)

### Динамические страницы

До сих пор у нас была только статическая страница, не обладающая большой функциональностью. Иметь только пример вывода фразы `Привет мир!` недостаточно, поэтому давайте пойдем дальше и добавим в наше приложение реальную функциональность.

Нашей первой функцией будут динамические страницы, генерируемые из файлов формата [markdown](https://ru.wikipedia.org/wiki/Markdown).

Создайте контроллер `Page` со следующим содержимым:

```php
<?php declare(strict_types = 1);

namespace Example\Controllers;

class Page
{
    public function show($params)
    {
        var_dump($params);
    }
}
```
После этого добавьте новый маршрут:

```php
['GET', '/{slug}', ['Example\Controllers\Page', 'show']],
```

Теперь попробуйте зайти по нескольким адресам, например `http://localhost:8000/test` и `http://localhost:8000/hello`. Как видите, контроллер `Page` вызывается каждый раз, а в массив `$params` передается slug страницы.

Итак, давайте для начала создадим несколько страниц. Мы пока не будем использовать базу данных, поэтому создайте новую папку `pages` в корневой папке вашего проекта. В нее добавьте несколько файлов с расширениями `.md` и добавьте в них текст. Например, `page-one.md` с содержанием:

```
This is a page.
```

Теперь нам нужно написать код для чтения соответствующего файла и отображения содержимого. Может показаться заманчивым просто поместить весь этот код в контроллер `Page`. Но помните о [разделении ответственности](https://ru.wikipedia.org/wiki/Разделение_ответственности). Вполне вероятно, что нам придется читать страницы и в других местах приложения (например, в области администрирования).

Поэтому давайте вынесем эту функциональность в отдельный класс. Существует большая вероятность того, что в дальнейшем мы перейдем от файлов к базе данных, поэтому давайте снова воспользуемся интерфейсом, чтобы сделать читателя страниц отделенным от реальной реализации.

В папке `src` создайте новую папку `Page`. В нее мы поместим все классы, связанные со страницами. Добавьте туда новый файл `PageReader.php` с таким содержанием:

```php
<?php declare(strict_types = 1);

namespace Example\Page;

interface PageReader
{
    public function readBySlug(string $slug) : string;
}
```

Для реализации создадим файл `FilePageReader.php`. Файл будет выглядеть следующим образом:

```php
<?php declare(strict_types = 1);

namespace Example\Page;

use InvalidArgumentException;

class FilePageReader implements PageReader
{
    private $pageFolder;

    public function __construct(string $pageFolder)
    {
        $this->pageFolder = $pageFolder;
    }

    public function readBySlug(string $slug) : string
    {
        return 'I am a placeholder';
    }
}
```

Как видно, в качестве аргумента конструктора мы требуем указать путь к папке страницы. Это делает класс гибким, и если мы решим переместить файлы или написать модульные тесты для класса, мы сможем легко изменить местоположение с помощью аргумента конструктора.

Можно также поместить все связанные со страницей вещи в отдельный пакет и повторно использовать его в различных приложениях. Поскольку мы не связываем вещи жестко, все получается очень гибко.

Этого пока достаточно. Давайте создадим файл шаблона для наших страниц с именем `Page.html` в папке `templates`. Пока просто добавим туда `{{ content }}`.

Добавим следующее в файл `Dependencies.php`, чтобы приложение знало, какую реализацию нашего нового интерфейса следует внедрить. Там же мы определяем `pageFolder`.

```php
$injector->define('Example\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);

$injector->alias('Example\Page\PageReader', 'Example\Page\FilePageReader');
$injector->share('Example\Page\FilePageReader');
```


Теперь вернитесь к контроллеру `Page` и измените метод `show` на следующий:

```php
public function show($params)
{
    $slug = $params['slug'];
    $data['content'] = $this->pageReader->readBySlug($slug);
    $html = $this->renderer->render('Page', $data);
    $this->response->setContent($html);
}
```

Для того чтобы это работало, нам потребуется инжектировать `Response`, `Renderer` и `PageReader`. Я оставлю это на ваше усмотрение. Не забудьте `использовать` все соответствующие пространства имен. В качестве эталона используйте контроллер `Homepage`.

У вас все получилось?

Если нет, то вот как должно выглядеть начало вашего контроллера:

```php
<?php declare(strict_types = 1);

namespace Example\Controllers;

use Http\Response;
use Example\Template\Renderer;
use Example\Page\PageReader;

class Page
{
    private $response;
    private $renderer;
    private $pageReader;

    public function __construct(
        Response $response,
        Renderer $renderer,
        PageReader $pageReader
    ) {
        $this->response = $response;
        $this->renderer = $renderer;
        $this->pageReader = $pageReader;
    }
...
```

Пока все хорошо, теперь давайте заставим наш `FilePageReader` действительно выполнять какую-то работу.

Нам нужно иметь возможность сообщить, что страница не найдена. Для этого мы можем создать пользовательское исключение, которое мы сможем перехватить позже. В папке `src/Page` создайте файл `InvalidPageException.php` с таким содержанием:

```php
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
```

Затем в файле `FilePageReader` добавьте этот код в конец метода `readBySlug`:

```php
$path = "$this->pageFolder/$slug.md";

if (!file_exists($path)) {
    throw new InvalidPageException($slug);
}

return file_get_contents($path);
```

Теперь при переходе на несуществующую страницу должно возникнуть исключение `InvalidPageException`. Если же файл существует, то должно отображаться его содержимое.

Конечно, показывать пользователю исключение для недействительного URL не имеет смысла. Поэтому давайте перехватим исключение и вместо него покажем ошибку 404.

Перейдите в контроллер `Page` и изменим метод `show` так, чтобы он выглядел следующим образом:

```php
public function show($params)
{
    $slug = $params['slug'];

    try {
        $data['content'] = $this->pageReader->readBySlug($slug);
    } catch (InvalidPageException $e) {
        $this->response->setStatusCode(404);
        return $this->response->setContent('404 - Page not found');
    }
    
    $html = $this->renderer->render('Page', $data);
    $this->response->setContent($html);
}
```

Убедитесь, что в верхней части файла используется оператор `use` для `InvalidPageException`.

Попробуйте несколько различных URL-адресов, чтобы убедиться, что все работает так, как нужно. Если что-то не так, вернитесь назад и отлаживайте до тех пор, пока все не заработает.

И, как всегда, не забудьте зафиксировать изменения.

[<< Предыдущая тема](09-templating.md) | [Следующая тема >>](11-page-menu.md)
