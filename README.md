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
- POST - в контроллере `User` вызываем метод `CreatePost()`. После POST-запроса представление не возвращаеться, необходимо произвести редирект.   

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

## Представления 

**Директория:** `\app\view\`
**Шаблоны(Layout):** `\app\view\Shared\`
**Именование:** в папке view создаеться папка с именем Контроллера. Внутри папки-контроллера создаються папки с именами как у методов действия.

По умолчанию используеться шаблон `\app\view\Shared\mainLayout.php` и представление `\app\view\{controller}\{action}.php`. В контроллере можно переопределить оба значения по необходимости.

```php
	$this->view->Set('layout', __DIR__ . Config::PATH_LAYOUT . 'errorLayout.php');
	$this->view->Set('template', __DIR__ .  Config::PATH_VIEW . 'otherLayout.php');
``` 

Получить данные в шаблоне и представлении можно так:

```php
	<h1><?php echo $this->Get('content');?></h1>
```

## Модель


