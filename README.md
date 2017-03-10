# Weelnk

![Screenshot](http://i.imgur.com/UlxBJ5A.png)

## Install

`composer create-project carc1n0gen/weelnk path/to/install`

`cd path/to/install && composer install`

### Environment Variables

For development it's good enough to just copy the example .env file and modify it as needed

`cp .env.example .env`

In production you should set real environment variables for the process.  The `.env.example` contains the required variables.

### Migrations

`php vendor/bin/phpmig migrate`

## License

Weelnk is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).