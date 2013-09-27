# Folio Producer API
[![Build Status](https://magnum.travis-ci.com/CoffeeAndCode/folio-producer-api.png?token=PgRq1y9q1wqEUV2w6sXq&branch=master)](https://magnum.travis-ci.com/CoffeeAndCode/folio-producer-api)

### Requirements

This project uses [PHP namespaces](http://www.php.net/manual/en/language.namespaces.rationale.php)
which require version 5.3.0+.


### Testing

PHPUnit is brought into the project with Composer which requires PHP 5.3.2+ to run.

1. change to directory of project
2. install Composer - http://getcomposer.org/doc/00-intro.md
3. install Composer dependencies with `php composer.phar install`
4. run unit tests with `vendor/bin/phpunit test/`

Note: Test files must end in `*Test.php` and test method names must start with `test*`.


#### Test Coverage

If you have xdebug installed, you can create an html code coverage report by running:

    vendor/bin/phpunit --coverage-html ./coverage


### Features

* get list of folios
    * folio name
    * device
    * publication name
    * folio number
    * publication date
    * product ID
    * published?
    * locke
* create folio
    * folio name
    * folio description
    * publication date
    * cover date
    * target devices (need way to populate list of devices available)
    * cover images (can differ by device)
    * articles
    * publication name
    * locked
    * product id
    * orientation
    * target device resolution
    * custom resolution
    * target viewer
    * READ ONLY:
        * version
        * htmlresources?
        * folio ID (ipad sd)
        * folio ID (ipad hd)
        * ...
* delete folio
* edit folio
* create article
    * post name / title
    * post slug
    * post / article ID
    * orientation
    * smooth scrolling
    * synced
* delete article
* edit article
* store / retrieve settings
    * company
    * key
    * secret
    * Apple
        * username
        * password
    * Android
        * username
        * password
    * Amazon
        * username
        * password
    * Default Image Settings
        * Default Asset Format
        * Default JPEG Quality
