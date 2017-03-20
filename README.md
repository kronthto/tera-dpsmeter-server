# TERA DPS-Meter Server - Collect stats

[![Software License][ico-license]](LICENSE.md)
[![Latest Stable Version][ico-githubversion]][link-releases]

This app allows you to host your own DPS-meter statistic server.

Developed using [ShinraMeter](https://github.com/neowutran/ShinraMeter) as DPS-Meter.

## Features

* Store encounters in database
* Show latest encounters

## Endpoints

* `/api/submitdps`
* `/shared/servertime`

## Install

``` bash
$ composer install (--no-dev -o)
$ cp .env.example .env
$ ./artisan key:generate
```
Adjust *.env* to your environment (database).
``` bash
$ ./artisan migrate
```

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-githubversion]: https://poser.pugx.org/kronthto/tera-dpsmeter-server/v/stable

[link-releases]: https://github.com/kronthto/tera-dpsmeter-server/releases
[link-contributors]: ../../contributors