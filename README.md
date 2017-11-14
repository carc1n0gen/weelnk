![Screenshot](https://i.imgur.com/VxXhcqv.png)

## Install

`composer create-project carc1n0gen/weelnk path/to/install && cd path/to/install`

The `master` branch *should* always be stable, but it is recommended to checkout the latest tagged release.  Check out 
the [release page](https://github.com/carc1n0gen/weelnk/releases) for the latest updates, and upgrade/migrations guides.

`git checkout v2.0.2`

Then install the dependencies

`composer install`

### Environment Variables

For development it's good enough to just copy the example .env file and modify it as needed

`cp .env.example .env`

In production you should set real environment variables for the process.  The `.env.example` contains the required variables.

<!--
In nginx this can be done like so:

```nginx
location ~ \.php$ {
  ...
  fastcgi_param SOME_VARIABLE some_value;
  ...
}
```
-->

### Database Setup

Run the database migrations to create the database schema

`vendor/bin/doctrine-migrations migrations:migrate`

### Permissions

The `storage` directory will need write permissions for the web server.

**Ubuntu/Debian**: `sudo chown -R www-data:www-data storage`

**FreeBSD**: `sudo chown -R www:www storage`

## Upgrading

### The Safe Way

Take a look at the [releases page](https://github.com/carc1n0gen/weelnk/releases) to see the latest releases. You can also subscribe to that page as an rss feed, as it's url doubles as a feed.  Each release will contain the release notes and other information like if migrations are required.

When you see you are behind, you can upgrade with the following commands:

`git fetch --tags && git checkout v[version-number]` - This will fetch the updates and then switch to the version number you specified.  **Make sure you replace [version-number] with the version number you want to upgrade to**

`composer install` - To install new dependencies in case there were any

`vendor/bin/doctrine-migrations migrations:migrate` - Runs database migrations in case any are needed.  This is safe to run regardless of if there are migrations or not.

### The Less Safe Way

If you're living on the edge and use the master branch, you can just regularly do a `git pull origin master` to fetch the latest updates, `composer install` to update dependencies, and finally `vendor/bin/doctrine-migrations migrations:migrate` in case there were any migrations to be run since you last updated.

## License

Weelnk is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
