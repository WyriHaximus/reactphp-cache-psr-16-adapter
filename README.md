# [ReactPHP](https://reactphp.org/) [PSR-16](https://www.php-fig.org/psr/psr-16/) cache adapter

[![Continuous Integration](https://github.com/WyriHaximus/reactphp-cache-psr-16-adapter/actions/workflows/ci.yml/badge.svg)](https://github.com/WyriHaximus/reactphp-cache-psr-16-adapter/actions/workflows/ci.yml)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/react-cache-psr-16-adapter/v/stable.png)](https://packagist.org/packages/WyriHaximus/react-cache-psr-16-adapter)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/react-cache-psr-16-adapter/downloads.png)](https://packagist.org/packages/WyriHaximus/react-cache-psr-16-adapter/stats)
[![License](https://poser.pugx.org/WyriHaximus/react-cache-psr-16-adapter/license.png)](https://packagist.org/packages/wyrihaximus/react-cache-psr-16-adapter)

### Installation ###

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/react-cache-psr-16-adapter
```

## Usage ##

Take any [`react/cache`](https://reactphp.org/cache/) implementation, like [`wyrihaximus/react-cache-redis `](https://github.com/WyriHaximus/reactphp-cache-redis) we'll be using in this example.

```php
$cache = new PSR16Adapter(new Redis($reditClient, 'react:cache:your:key:prefix:'));

React\Async\async(function () {
    $cache->set('key', 'value');
    echo $cache->get('key'); // echos: value
})();
```

## Contributing ##

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License ##

Copyright 2022 [Cees-Jan Kiewiet](https://wyrihaximus.net/)

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
