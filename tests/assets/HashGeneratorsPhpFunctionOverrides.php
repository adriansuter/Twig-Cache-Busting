<?php

declare(strict_types=1);

namespace AdrianSuter\TwigCacheBusting\HashGenerators;

/**
 * @param string $path
 * @return false|int
 */
function filemtime(string $path)
{
    if (isset($GLOBALS['filemtime_return'])) {
        return $GLOBALS['filemtime_return'];
    }

    return \filemtime($path);
}

/**
 * @param string $path
 * @return false|string
 */
function md5_file(string $path)
{
    if (isset($GLOBALS['md5_file_return'])) {
        return $GLOBALS['md5_file_return'];
    }

    return \md5_file($path);
}

/**
 * @param string $path
 * @return false|string
 */
function sha1_file(string $path)
{
    if (isset($GLOBALS['sha1_file_return'])) {
        return $GLOBALS['sha1_file_return'];
    }

    return \sha1_file($path);
}
