symfony
=======

A Symfony project created on February 28, 2017, 12:09 pm.

Instalation
===========

1. Clone into your project:

    `git clone https://github.com/akerbel/blog-api.git MyProject`
2. Make composer install and setup your database settings:

   `php composer.phar install`
3. Created tables for entities:

    `php bin/console doctrine:schema:update`

API
===

- GET `/list` - get the list of blog messages.

    parameters:

    - offset - integer, the sequence will start at that offset in the array.
    - length - integer, the sequence will have up to that many elements in it.

- GET `/message/{id}` - get a message by id.
- POST `/message` - add a new message.

    parameters:

    - title - title of the message.
    - text - text of the message.

- PATCH `/message/{id}` - edit a message by id.
- DELETE `/message/{id}` - delete a messege by id.

Tests
=====

All tests are in `src/akerbel/BlogBundle/Tests/Controller/` . PHPunit was used. Use `phpunit src/akerbel/BlogBundle/Tests/Controller/` for runing tests.