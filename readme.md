<p align="center"><img src="https://nem.io/wp-content/themes/nem/img/logo-nem.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/evias/nem-php"><img src="https://travis-ci.org/evias/nem-php.svg" alt="nem-php Build Status"></a>
<a href="https://packagist.org/packages/evias/nem-php"><img src="https://poser.pugx.org/evias/nem-php/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/evias/nem-php"><img src="https://poser.pugx.org/evias/nem-php/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/evias/nem-php"><img src="https://poser.pugx.org/evias/nem-php/license.svg" alt="License"></a>
</p>

## About this package

This package provides with *example integrations* of the [evias/nem-php](https://github.com/evias/nem-php) NEM SDK for PHP.

It should provide with features that are easy to reproduce and showcase how the `evias/nem-php` library can be integrated in your projects.

The laravel application *is only a wrapper* to provide with a web application that can be served on any of Apache or Nginx. Please use composer as mentioned in the following section.

# Usage

## Step 1: (Required) Installation

You will need to install `composer` first. Then clone this repository and install its dependencies as follows:

```bash
$ git clone https://github.com/evias/nem-php-examples
$ cd nem-php-examples
$ composer install
```

Now you are ready to test out the NEM SDK for PHP!

NEMjoy :)

## Step 2: (Required) Configure & Run the Migrations

Check your **.env** file. If it doesn't exist yet, execute the following: `cp .env.example .env`.

Then please edit the **.env** file and make sure that the `NEM_` and `APP_` prefixed settings are set correctly.

Now look at your `DB_NAME` and other database related settings and connect to your MySQL server to create the correct database.

The `homestead` username and database *should be changed to different values on a production environment.* Following is an example of the database creation on the MySQL Server:

```mysql
> create database homestead;
> create user 'homestead'@'localhost' identified by 'secret';
> grant all privileges on homestead.* to 'homestead'@'localhost';
```

After this is done, you can run the migrations of the application and run the database seeder as shows the following terminal execution:

```bash
$ php artisan migrate
$ php artisan db:seed
```

Now you can login with your `APP_ADMIN_EMAIL` email address and the password `APP_ENCRYPTION_SEED` to manage the Processor application.

## Step 3: (Optional) Running the PHP server (included if you want)

Execute the following to use Laravel's housebaked PHP Server that is provided with this package.

```bash
$ php artisan serve --host=127.0.0.1 --port=8888
```

And now simply open your browser to the following URL: `http://127.0.0.1:8888`.

## (Optional) Install build tools (for Development purpose only)

You will need to install `nodejs` such that you can install the frontend template dependencies such as Bootstrap, jQuery and VueJS.

Following commands list will set you up for the template development:

```bash
$ sudo apt install nodejs
$ npm install
```

Now whenever you do some changes to the template files, you can run the following: 

```bash
$ npm run production
```

## Pot de vin

If you like the initiative, and for the sake of good mood, I recommend you take a few minutes to Donate a beer or Three [because belgians like that] by sending some XEM (or whatever Mosaic you think pays me a few beers someday!) to my Wallet:

    NB72EM6TTSX72O47T3GQFL345AB5WYKIDODKPPYW

| Username | Role |
| --- | --- |
| [eVias](https://github.com/evias) | Project Lead |

## License

This software is released under the [MIT](LICENSE) License.

© 2017-2018 Grégory Saive <greg@evias.be>, All rights reserved.
