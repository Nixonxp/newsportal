# Новостной портал

Читать на другом языке: [English](README.en.md)

Данный проект новостного портала создавался для быстрого постинга новостей,
поиска и агрегации информации с внешних ресурсов.

## Предварительные требования
* PHP ^8.0
* Composer
* Node.js (v14+) & NPM (6+)
* PostgreSQL
* Redis
* Elasticsearch

## Функционал сервиса
* Получение и вывод данных валют с внешних сервисов через REST API (freecurrencyapi,exchangerate)
* Парсинг популярных новостей, модерация и публикация с внеших ресурсов
* ЛК пользователя с возможностю управления подписками на определенных авторов
* Управление категориями
* Управление и вывод рекламных баннеров по времени
* Поиск новостей по заголовку и содержанию
* Управление списками пользователей
* Просмотр журнала событий
* Уведомление пользователя о новой подписке
* Уведомление администратора о критических событий сервиса
* Виджеты в админке основных метрик сервиса
