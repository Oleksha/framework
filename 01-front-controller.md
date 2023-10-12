[Следующая тема >>](02-composer.md)

### Front Controller

[Front Controller](http://ru.wikipedia.org/wiki/Единая_точка_входа_(шаблон_проектирования)) – это единая точка входа в приложение.

Для начала создайте каталог в котором будет располагаться ваше приложение. Также нам нужна точка входа, куда будут направляться все запросы. Это будет файл `index.php`.

Обычно для этого достаточно поместить файл `index.php` в корневую папку проекта. Так делают некоторые фреймворки. Почему так делать не следует я вам сейчас объясню.

Файл `index.php` – это начальная точка, поэтому он должен находиться в каталоге веб-сервера. Это означает, что веб-сервер имеет доступ ко всем подкаталогам той папки в которой он находится. Если все настроить правильно, то в этом случае можно запретить ему доступ к подкаталогам, в которых находятся служебные файлы вашего приложения.

Но не всегда все идет как мы запланировали. И если что-то пойдет не так, а файл `index.php` находится в корне вашего приложения, то весь исходный код вашего приложения может быть открыт для посетителей. Не буду объяснять, почему это нехорошо.

Поэтому поступим правильно, создадим в папке проекта папку с названием `public`, в которой будут находиться видимые пользователю файлы и папку `src` в котором будут находиться файлы нашего приложения.

Теперь в папке `public` создадим файл `index.php`. Запомните, что в этом файле должно быть минимум информации, поэтому поместите туда только следующий код:

```php
<?php declare(strict_types = 1); 

require __DIR__ . '/../src/Bootstrap.php';
```

`__DIR__` это [предопределенная константа](http://php.net/manual/ru/language.constants.predefined.php) языка `PHP` содержащая путь к каталогу нашего приложения. Используя ее, можно добиться того, чтобы `require` всегда содержал корректный относительный путь к файлу независимо от того, куда мы захотим перенести наше приложение. Если мы этого не сделаем, то вызвав `index.php` из другой папки, наше приложение вызовет ошибку.

`declare(strict_types = 1);` устанавливает для текущего файла [строгую типизацию](http://php.net/manual/ru/functions.arguments.php#functions.arguments.type-declaration.strict). В данном руководстве мы будем использовать ее для всех файлов `PHP`. Это означает, что если вы передадите целое число вместо строки в качестве параметра методу, это вызовет ошибку. Если не использовать строгую типизацию, то переданный параметр будет автоматически приведен к требуемому типу. При строгой типизации в такой ситуации будет выброшено исключение.

`Bootstrap.php` – это файл, который и является файлом нашего приложения. К нему мы перейдем в ближайшее время.

Также в папке `public` будут размещены другие публичные файлы (например, JavaScript-файлы и таблицы стилей CSS).

Теперь в папке `src` создайте файл `Bootstrap.php` содержащий следующий код:

```php
<?php declare(strict_types = 1);

echo 'Hello World!';
```

Now let's see if everything is set up correctly. Open up a console and navigate into your projects `public` folder. In there type `php -S localhost:8000` and press enter. This will start the built-in webserver and you can access your page in a browser with `http://localhost:8000`. You should now see the 'hello world' message.

If there is an error, go back and try to fix it. If you only see a blank page, check the console window where the server is running for errors.

Now would be a good time to commit your progress. If you are not already using Git, set up a repository now. This is not a Git tutorial so I won't go over the details. But using version control should be a habit, even if it is just for a tutorial project like this.

Some editors and IDE's put their own files into your project folders. If that is the case, create a `.gitignore` file in your project root and exclude the files/directories. Below is an example for PHPStorm:

```
.idea/
```

[next >>](02-composer.md)
