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

REST API
===

- GET `/list` - get the list of blog messages.

    request parameters:

    - offset - integer, the sequence will start at that offset in the array.
    - length - integer, the sequence will have up to that many elements in it.

    response example:

    ```json
    {
        "result": "success",
        "messages":
          {
            "0":
              {
                "id": 3,
                "title": "Message #1",
                "text": "Text for the message #1",
                "created_at": "17:41:19 28.02.2017",
                "updated_at": "17:41:19 28.02.2017"
              },
            "1":
              {
                "id": 4,
                "title": "Message #2",
                "text": "Text for the message #2",
                "created_at": "17:41:34 28.02.2017",
                "updated_at": "17:42:05 28.02.2017"
              }
          }
  }
    ```

- GET `/message/{id}` - get a message by id.

    response example:

    ```json
    {
        "result": "success",
        "message":
          {
            "id": 4,
            "title": "Message #2",
            "text": "Text for the message #2",
            "created_at": "17:41:34 28.02.2017",
            "updated_at": "17:42:05 28.02.2017"
          }
  }
    ```

- POST `/message` - add a new message.

    request parameters:

    - title - title of the message.
    - text - text of the message.

    response example:

    ```json
        {
          "result": "success",
          "message_id": 10
        }
    ```

- PATCH `/message/{id}` - edit a message by id.

    response example:

    ```json
        {
          "result": "success"
        }
    ```

- DELETE `/message/{id}` - delete a messege by id.

    response example:

    ```json
        {
          "result": "success"
        }
    ```

API Error example:

   ```json
        {
            "result": "error",
            "error_code": 404,
            "error_text": "The message #333 isnot found"
        }
   ```

Tests
=====

All tests are in `src/akerbel/BlogBundle/Tests/Controller/` . PHPunit was used. Use `phpunit src/akerbel/BlogBundle/Tests/Controller/` for runing tests.