# Twig-Cache-Busting

[![Build Status](https://travis-ci.org/adriansuter/Twig-Cache-Busting.svg?branch=master)](https://travis-ci.org/adriansuter/Twig-Cache-Busting)
[![Coverage Status](https://coveralls.io/repos/github/adriansuter/Twig-Cache-Busting/badge.svg?branch=master)](https://coveralls.io/github/adriansuter/Twig-Cache-Busting?branch=master)
[![Total Downloads](https://poser.pugx.org/adriansuter/twig-cache-busting/downloads)](https://packagist.org/packages/adriansuter/twig-cache-busting)
[![License](https://poser.pugx.org/adriansuter/twig-cache-busting/license)](https://packagist.org/packages/adriansuter/twig-cache-busting)

Twig Cache Busting is an add-on for Twig to support cache busting

## Installation

```bash
composer require adriansuter/twig-cache-busting
```

## Description

This add-on would extend Twig by a cache-busting mechanism. The cache-busting is taking
place upon compilation of the template (not rendering). So whenever you update an asset, you
would need to recompile the templates that reference this asset (or clear the cache and let
Twig rebuilding it dynamically).

### Cache Busters

There are three cache busting methods.

- **Query Param Cache Buster**
- **File Name Cache Buster**
- **Dictionary Cache Buster**

### Hash Generators

The **Query Param Cache Buster** and the **File Name Cache Buster** both use a hash generator
to generate a hash for the given asset. The following hash generators are possible.

- **FileMD5HashGenerator**
- **FileSHA1HashGenerator**
- **FileModificationTimeHashGenerator**


## Usage

### Query Param Cache Buster

This cache busting method would add a query param to the generated paths.

```php
use AdrianSuter\TwigCacheBusting\CacheBusters\QueryParamCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;

//...

$twig->addExtension(new CacheBustingTwigExtension(
    new CacheBustingTokenParser(
        new QueryParamCacheBuster(__DIR__)
	)
));
```




### File Name Cache Buster

In order to use the cache busting add-on, you need to add the following code
```php
use AdrianSuter\TwigCacheBusting\CacheBusters\FileNameCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;

// ...

$pathToEndPoint = '/home/htdocs/public';

$twig->addExtension(new CacheBustingTwigExtension(
	new CacheBustingTokenParser(new FileNameCacheBuster($pathToEndPoint))
));
```

Assume you do have a file `/home/htdocs/public/assets/image.jpg`. To build a cache busting
path, you can write in your template
```twig
<img src="{% cache_busting 'assets/image.jpg' %}">
```
which would be compiled such that instead of `assets/image.jpg` the compiled template would
be `/assets/image.1274364743.jpg`.

If you want to use the file name cache busting method, then your web server needs to be configured such that the cache busting
requests get redirected. For Apache you need to set
```apacheconfig
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)\.(\d+)\.(js|css|jpg|png|svg)$ $1.$3 [L]
```


### Dictionary Cache Buster

This cache busting method would use a dictionary to lookup file names. The dictionary is
basically a mapping between the original file path and the cache busting version.

```php
use AdrianSuter\TwigCacheBusting\CacheBusters\DictionaryCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;
use AdrianSuter\TwigCacheBusting\Dictionaries\ArrayDictionary;

// ...

$twig->addExtension(new CacheBustingTwigExtension(
	new CacheBustingTokenParser(
		new DictionaryCacheBuster(
			new ArrayDictionary([
				'assets/image.jpg' => 'assets/cb-1c2d7c4s36d47d.jpg',
			])
		)
	)
));
```


## Contribution

Is much welcomed.
