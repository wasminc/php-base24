# carlosafonso/php-base24

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A PHP Base24 binary-to-text encoder, port of the original Kotlin implementation by @kuon at https://github.com/kuon/java-base24.

## Install

Via Composer

``` bash
$ composer require carlosafonso/php-base24
```

## Usage

``` php
$encoder = new \Afonso\Base24\Encoder();
$encoder->encode([0, 0, 0, 0]); // 'ZZZZZZZ'
$encoder->decode('ZZZZZZZ'); // [0, 0, 0, 0]
$encoder->decode('zzzzzzz'); // [0, 0, 0, 0]
```

## Testing

``` bash
$ composer test
```

## Credits

- [Carlos Afonso][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/carlosafonso/php-base24.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/carlosafonso/php-base24/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/carlosafonso/php-base24.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/carlosafonso/php-base24.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carlosafonso/php-base24.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/carlosafonso/php-base24
[link-travis]: https://travis-ci.org/carlosafonso/php-base24
[link-scrutinizer]: https://scrutinizer-ci.com/g/carlosafonso/php-base24/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/carlosafonso/php-base24
[link-downloads]: https://packagist.org/packages/carlosafonso/php-base24
[link-author]: https://github.com/carlosafonso
