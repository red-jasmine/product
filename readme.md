# Product

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require red-jasmine/product
```

## Usage

## 商品状态

```php
    case ON_SALE = 'on_sale'; // 在售

    case OUT_OF_STOCK = 'out_of_stock'; // 缺货

    case SOLD_OUT = 'sale_out'; // 售罄

    case IN_STOCK = 'in_stock'; // 仓库中

    case OFF_SHELF = 'off_shelf'; // 下架

    case PRE_SALE = 'pre_sale'; // 预售

    case FORCED_OFF_SHELF = 'forced_off_shelf'; // 强制下架
    
    case DELETED = 'deleted'; // 删除
```
> 商品的状态只能为 
> 在售、缺货、售停、仓库中、预售 
> 规格状态：在售、售停、缺货、

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email liushoukun66@gmail.com instead of using the issue tracker.

## Credits

- [liushoukun][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/red-jasmine/product.svg?style=flat-square

[ico-downloads]: https://img.shields.io/packagist/dt/red-jasmine/product.svg?style=flat-square

[ico-travis]: https://img.shields.io/travis/red-jasmine/product/master.svg?style=flat-square

[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/red-jasmine/product

[link-downloads]: https://packagist.org/packages/red-jasmine/product

[link-travis]: https://travis-ci.org/red-jasmine/product

[link-styleci]: https://styleci.io/repos/12345678

[link-author]: https://github.com/red-jasmine

[link-contributors]: ../../contributors
