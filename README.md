# twig-color-extension

[![Build Status](https://travis-ci.org/mikealmond/twig-color-extension.svg?branch=master)](https://travis-ci.org/mikealmond/twig-color-extension)
[![Code Coverage](https://scrutinizer-ci.com/g/mikealmond/twig-color-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikealmond/twig-color-extension/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikealmond/twig-color-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikealmond/twig-color-extension/?branch=master)


This library will allow you to alter colors, check readability, and generate different palettes based on a base color.

## Install

Via Composer

``` bash
$ composer require mikealmond/twig-color-extension
```

## Usage

```twig
{{ '0099FF'|color_darken(20)|color_css_rgba(0.9) }}
{{ '0099FF' is color_dark ? 'dark' : 'light' }}
{{ '0099FF'|color_complementary(30)|color_css_hex }}
{{ '0099FF' is color_low_contrast ? 'default-color' : '0099FF'|color_css_hex }}


```


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mike Almond](https://github.com/mikealmond)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
