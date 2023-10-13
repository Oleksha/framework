Все указанное ниже являтся переводом авторского репозитория, который создал [Patrick Louys](https://github.com/PatrickLouys/no-framework-tutorial)

Хорошие отзывы об этом руководстве вдохновили меня на написание книги. Книга представляет собой более современную версию этого руководства и охватывает гораздо больше вопросов. Щелкните на ссылке ниже, чтобы ознакомиться с ней (там также есть пример главы).

### [Профессиональный PHP: Создание надежных и безопасных приложений](http://patricklouys.com/professional-php/)

![](http://patricklouys.com/img/professional-php-thumb.png)

Руководство по-прежнему доступно в исходном виде ниже.

## Создание PHP-приложения без фреймворка

### Введение

Если вы новичок в языке, то это руководство не для вас. Оно предназначено для тех, кто освоил основы PHP и знает немного об объектно-ориентированном программировании.

Вы наверняка слышали о [SOLID](http://ru.wikipedia.org/wiki/SOLID_(программирование)) (Аббревиатура пяти основных принципов проектирования классов в объектно-ориентированной парадигме). Но если вы не знакомы с этими принципами, у вас есть возможноть кратко ознакомиться со значением этих принципов до начала работы с этим руководством.

Я часто видел комментарии людей, которые в чате Stack Overflow PHP спрашивали, хорош ли фреймворк X и чем он лучше фреймворка Y. В большинстве случаев им отвечали: для создания приложения нужно использовать PHP, а не фреймворк. И такой ответ многих из них ставил в тупик, потому что они просто не знали с чего начать.

Поэтому моя цель – предоставить таким людям простое руководство, на которое они могли бы ориентироваться при создании своих приложений. В большинстве случаев использование готового и громоздкого фреймворка не имеет смысла, и написать приложение с нуля с помощью некоторых сторонних пакетов гораздо проще, чем вам кажется.

**Это руководство написано для PHP 7.0 или более новых версий.** Если вы используете более старую версию, пожалуйста, обновите ее перед началом работы. Я рекомендую использовать [текущую стабильную версию](http://php.net/downloads.php).

Итак, давайте сразу же начнем обучение и обратимся к [первой части](01-front-controller.md).

### Содержание

1. [Front Controller](01-front-controller.md)
2. [Composer](02-composer.md)
3. [Обработчик ошибок](03-error-handler.md)
4. [HTTP](04-http.md)
5. [Router](05-router.md)
6. [Dispatching to a Class](06-dispatching-to-a-class.md)
7. [Inversion of Control](07-inversion-of-control.md)
8. [Dependency Injector](08-dependency-injector.md)
9. [Templating](09-templating.md)
10. [Dynamic Pages](10-dynamic-pages.md)
11. [Page Menu](11-page-menu.md)
12. [Frontend](12-frontend.md)
