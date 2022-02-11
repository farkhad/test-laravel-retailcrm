## Тестовое задание для программиста PHP

Необходимо создать сайт использую фреймворк Laravel (https://laravel.com/docs/8.x) с единственной страницей "Создание заказа", HTML-верстка не оценивается.

Страница создания заказа должна содержать форму с 4-мя полями: ФИО, Комментарий клиента, Артикул товара, Бренд товара. При отправке формы должен создаваться заказ в RetailCRM (описание API и ключ ниже), можно написать своё решение, можно использовать сторонние пакеты.

Заказ должен создаваться со следующими полями:
- Статус заказа: trouble
- Тип заказа: fizik
- Магазин: test
- Способ оформления: test
- Номер заказа: дата вашего рождения, например 8051987
- ФИО: ваши фио
- Товар: артикул AZ105W бренд Azalita Название Маникюрный набор Solingen, 3 пр., белый футляр

Чтобы добавить товар в заказ ищем его в каталоге /api/v5/store/products по артикулу и бренду (в RetailCRM поле manufacturer) и добавляем в заказ по id первого оффера (products[0][offers][0][id]).

Комментарий клиента: ссылка на Ваш код тестового задания на github.com

Статьи или видео на тему "как создать блог на Laravel" Вам в помощь.

Описание <a href="https://help.retailcrm.ru/Developers/Index" target="_blank">API Retailcrm</a>

API ключ: ------

Поддомен для запросов к API: https://<your-site>.retailcrm.ru/

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
