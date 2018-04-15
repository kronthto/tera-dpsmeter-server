# TERA DPS-Meter Server - Collect stats

[![Software License][ico-license]](LICENSE.md)

This app allows you to host your own DPS-meter statistic server.

Developed using [ShinraMeter](https://github.com/neowutran/ShinraMeter) as DPS-Meter.

## Features

* Store encounters in database
* Show latest encounters
* Show Top-DPS by boss of recent time period
* Encounter detail page
* Define list of "allowed" regions in config
* Dynamic Filter: Stats since, playerName, guild
* Skill/Buff Icons

## Endpoints

* `/api/submitdps`
* `/api/allowed`
* `/shared/servertime`
* `/api/shinra/servertime`

## Install

*Make sure to recursively clone also the teradata submodule.*

``` bash
$ composer install (--no-dev -o)
$ cp .env.example .env
$ ./artisan key:generate
$ mkdir public/img/icons && unzip teradata/icons.zip -d public/img/icons/
```
Adjust *.env* to your environment (database).
``` bash
$ ./artisan migrate
```

## Usage

Refer to https://github.com/neowutran/ShinraMeter/wiki/Private-DPS-server#dps-meter-configuration , using the [endpoints](#endpoints).

## Credits

- [All Contributors][link-contributors]
- https://github.com/neowutran/TeraDpsMeterData

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[link-contributors]: ../../contributors
