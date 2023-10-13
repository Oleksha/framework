[<< Предыдущая тема](01-front-controller.md) | [Следующая тема >>](03-error-handler.md)

### Composer

[Composer](https://getcomposer.org/) – это менеджер зависимостей для PHP.

Если вы не используете фреймворк, это не значит, что вам каждый раз придется изобретать колесо, если вы захотите что-то сделать. С помощью `Composer` вы можете подключить сторонние библиотеки к своему приложению.

Если у вас еще не установлен `Composer`, зайдите на [этот сайт](https://getcomposer.org/) скачайте и установите его. Пакеты со сторонними библиотеками которые можно добавить в ваш проект с помощью `Composer` можно найти на ресурсе [Packagist](https://packagist.org/).

Создайте в корневой папке проекта новый файл `composer.json`. Это файл конфигурации `Composer`, который будет использоваться для настройки проекта и его зависимостей. Он должен быть в корректном формате `JSON`, иначе `Composer` не сможет с ним работать.

Добавьте в созданный файл следующий код:

```json
{
    "name": "Project name",
    "description": "Your project description",
    "keywords": ["Your keyword", "Another keyword"],
    "license": "MIT",
    "authors": [
        {
            "name": "Your Name",
            "email": "your@email.com",
            "role": "Creator / Main Developer"
        }
    ],
    "require": {
        "php": ">=7.0.0"
    },
    "autoload": {
        "psr-4": {
            "Example\\": "src/"
        }
    }
}
```

In the autoload part you can see that I am using the `Example` namespace for the project. You can use whatever fits your project there, but from now on I will always use the `Example` namespace in my examples. Just replace it with your namespace in your own code.

Open a new console window and navigate into your project root folder. There run `composer update`.

Composer creates a `composer.lock` file that locks in your dependencies and a vendor directory. 

Committing the `composer.lock` file into version control is generally good practice for projects. It allows continuation testing tools (such as [Travis CI](https://travis-ci.org/)) to run the tests against the exact same versions of libraries that you're developing against. It also allows all people who are working on the project to use the exact same version of libraries i.e. it eliminates a source of "works on my machine" problems.

That being said, [you don't want to put the actual source code of your dependencies in your git repository](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md). So let's add a rule to our `.gitignore` file:

```
vendor/
```

Now you have successfully created an empty playground which you can use to set up your project.

[<< Предыдущая тема](01-front-controller.md) | [Следующая тема >>](03-error-handler.md)
