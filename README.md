# Twig-Cache-Busting

[![Build Status](https://travis-ci.org/adriansuter/Twig-Cache-Busting.svg?branch=master)](https://travis-ci.org/adriansuter/Twig-Cache-Busting)
[![Coverage Status](https://coveralls.io/repos/github/adriansuter/Twig-Cache-Busting/badge.svg?branch=master)](https://coveralls.io/github/adriansuter/Twig-Cache-Busting?branch=master)
[![Total Downloads](https://poser.pugx.org/adriansuter/twig-cache-busting/downloads)](https://packagist.org/packages/adriansuter/twig-cache-busting)
[![License](https://poser.pugx.org/adriansuter/twig-cache-busting/license)](https://packagist.org/packages/adriansuter/twig-cache-busting)

Twig Cache Busting is an add-on for [Twig](https://twig.symfony.com/) to support cache busting on template compilation.


## Installation

```bash
$ composer require adriansuter/twig-cache-busting
```


## Description

This add-on would extend Twig by a cache busting mechanism. The cache busting is taking
place upon compilation of the template (not rendering). So whenever you update an asset, you
would need to recompile the templates that reference this asset (or clear the cache and let
Twig rebuilding it dynamically).

### Cache Busters

There are three cache busting methods.

- **Query Param Cache Buster**
  This cache buster would append a query param to the file path. So an asset with the path `image.jpg` gets transformed for example to `image.jpg?c=abcd`.
- **File Name Cache Buster**
  This cache buster would alter the file name. So an asset with the path `image.jpg` gets transformed for example to `image.abcd.jpg`.
- **Dictionary Cache Buster**
  This cache buster would use a lookup table (map) to find the target file path.

### Hash Generators

The **Query Param Cache Buster** and the **File Name Cache Buster** both use a hash generator
to generate a hash for the given asset (in the examples above, the hash is `abcd`). The following hash
generators are possible.

- **FileMD5HashGenerator**
  This hash generator calculates the MD5 hash of the file.
- **FileSHA1HashGenerator**
  This hash generator calculates the SHA1 hash of the file.
- **FileModificationTimeHashGenerator**
  This hash generator uses the file modification time as Unix timestamp.


## Usage

### Query Param Cache Buster

Assume you have a file `/home/htdocs/public/assets/image.jpg` and your template is as follows:

```twig
<img src="{% cache_busting 'assets/image.jpg' %}">
```

To use the **Query Param Cache Buster** you need to add the `CacheBustingTwigExtension` to Twig
and pass the `QueryParamCacheBuster`.

```php
use AdrianSuter\TwigCacheBusting\CacheBusters\QueryParamCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;

//...

$twig->addExtension(new CacheBustingTwigExtension(
    new CacheBustingTokenParser(
        new QueryParamCacheBuster('/home/htdocs/public')
	)
));
```

By default, the `QueryParamCacheBuster` uses the `FileModificationTimeHashGenerator`. But you can set another
generator by passing a second argument to the constructor. For example:

 ```php
use AdrianSuter\TwigCacheBusting\HashGenerators\FileMD5HashGenerator;

new QueryParamCacheBuster('/home/htdocs/public', new FileMD5HashGenerator())
```

The third argument can be used to indicate that your project has a base path.


### File Name Cache Buster

Assume you have a file `/home/htdocs/public/assets/image.jpg` and your template is as follows:

```twig
<img src="{% cache_busting 'assets/image.jpg' %}">
```

To use the **File Name Cache Buster** you need to add the `CacheBustingTwigExtension` to Twig
and pass the `FileNameCacheBuster`.

```php
use AdrianSuter\TwigCacheBusting\CacheBusters\FileNameCacheBuster;
use AdrianSuter\TwigCacheBusting\CacheBustingTokenParser;
use AdrianSuter\TwigCacheBusting\CacheBustingTwigExtension;

// ...

$twig->addExtension(new CacheBustingTwigExtension(
    new CacheBustingTokenParser(
        new FileNameCacheBuster('/home/htdocs/public')
    )
));
```

Your web server needs to be configured such that the cache busting requests get 
redirected. For Apache you need to set
```apacheconfig
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)\.(\d+)\.(js|css|jpg|png|svg)$ $1.$3 [L]
```

*Note that you might want to add more extensions to the rewrite rule.*

By default, the `FileNameCacheBuster` uses the `FileModificationTimeHashGenerator`. But you can set another
generator by passing a second argument to the constructor. For example:

 ```php
use AdrianSuter\TwigCacheBusting\HashGenerators\FileMD5HashGenerator;

new FileNameCacheBuster('/home/htdocs/public', new FileMD5HashGenerator())
```

If your hash generator returns hexadecimal hashes, the you would need to adapt the Apache 
rewrite rule appropriately. For example:

```apacheconfig
RewriteRule ^(.+)\.([a-f0-9]+)\.(js|css|jpg|png)$ $1.$3 [L]
```

The third argument can be used to indicate that your project has a base path.


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
