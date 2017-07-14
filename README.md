# perezvonok1.0

## Установка

**Загрузить основные файлы в корень!**

Настроить файл конфигураций. Обязательно изменить следующие значения:

- `PATH_SEPARATOR` - разделитель для путей файловой системы (\ или /) 
- `PATH_ROOT` - путь до корневой папки проекта
- `URL_ROOT` - домен
- `DB_*` - заполнить поля для подключения к БД
- `APP_SECRET` - рандомная строка 

## Маршрутизация

`{controller}` - имя контроллера  
`{action}` - имя метода действия 

- Обычный маршрут: `http://site.local/{controller}/{action}/`
- Маршрут с параметрами 1: `http://site.local/controller/action/11/24/` - в контроллере параметры будут доступны по индексу (0 => 11, 1 => 24)
- Маршрут с параметрами 2: `http://site.local/controller/action/id:1/name:2/` - в контроллере параметры будут доступны по ассоциации ('id' => 11, 'name' => 24)
- Корневой маршрут: `http://site.local/` - использует контроллер `Home` и метод действия `Index`
- Маршрут без уточнения действия: `http://site.local/controller/` - использует метод действия `Index`

Обработка маршрута `http://site.local/user/create/` в зависимости от метода HTTP-запроса(GET/POST):

- GET - в контроллере `User` вызываем метод `Create()` 
- POST - в контроллере `User` вызываем метод `CreatePost()`

## Контроллеры 

**Директория:** `\app\lib\`
**Именование:** название файла идентично названию класса
**Базовый класс:** `\app\core\Page`

**Методы базового класса:**

**Система разграничения доступа к страницам:**

По умолчанию доступ к странице получают пользователи всех ролей. 

- `DeleteUserGroup($data = array())` - получаем массив ролей(id). Пользователи с удаленными ролями не имеют доступа к текущей странице.   
- `SetUserGroup($data = array())` - получаем массив ролей(id). Формурием список ролей для которых открыта текущая страница.   
- `HasAccess()` - Проверка прав активного пользователя на просмотр конкретной страницы `true/false`.   

Для управления доступом пользователей без роли используеться `id = 0`.

**Формирование данных для представления:**

- `Get($name)` - Получить значение по указанному имени.  
- `Set($name, $value)` - Добавить в список данные.

**Параметры с HTTP-запроса**

- `Param($index)` - получить данные с HTTP-запроса по индексу или ассоциации.

**Редирект:**

- `GenerateError($code = array())` - перейти на страницу ошибок. Передать необходимо массив с кодами ошибок.
- `NotFound()` - перейти на страницу ошибок c кодом 404.
- `Redirect($controller = 'Home', $action = 'Index', $param = array())` - сформировать ссылку и перейти по ней.
- `RedirectUrl($url = '/Home/Index/')` - перейти по конкретной ссылке.



Then load using:

```JavaScript
var ajax = require('ajax');
```

Or load using a script tag (downloads are available [here](https://component.jit.su/ForbesLindesay/ajax/download))

```html
<script src="ajax.min.js"></script>
```


## Code Example

Show what the library does as concisely as possible, developers should be able to figure out **how** your project solves their problem by looking at the code example. Make sure the API you are showing off is obvious, and that your code is short and concise.

## Motivation

A short description of the motivation behind the creation and maintenance of the project. This should explain **why** the project exists.

## Installation

Provide code examples and explanations of how to get the project.

## API Reference

Depending on the size of the project, if it is small and simple enough the reference docs can be added to the README. For medium size to larger projects it is important to at least provide a link to where the API reference docs live.

## Tests

Describe and show how to run the tests with code examples.

## Contributors

Let people know how they can dive into the project, include important links to things like issue trackers, irc, twitter accounts if applicable.

## License

A short snippet describing the license (MIT, Apache, etc.)

