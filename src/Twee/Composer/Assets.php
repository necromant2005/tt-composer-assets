<?php

namespace Twee\Composer;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Assets
{
    public static function bootstrap(string $destination)
    {
        echo 'copying - getbootstrap' . PHP_EOL;

        $content = file_get_contents('vendor/twbs/bootstrap/dist/css/bootstrap.css');
        $content = preg_replace('~url\(\'\.\.([^\']+)\'\)~s', 'url("\\1")', $content);
        file_put_contents($destination . '/css/bootstrap.css', $content, LOCK_EX);

        $content = file_get_contents('vendor/twbs/bootstrap/dist/css/bootstrap.min.css');
        $content = preg_replace('~url\(\'\.\.([^\']+)\'\)~s', 'url("\\1")', $content);
        file_put_contents($destination . '/css/bootstrap.min.css', $content, LOCK_EX);

        copy('vendor/twbs/bootstrap/dist/css/bootstrap.css.map', $destination . '/css/bootstrap.css.map');
        copy('vendor/twbs/bootstrap/dist/css/bootstrap.min.css.map', $destination . '/css/bootstrap.min.css.map');
        copy('vendor/twbs/bootstrap/dist/js/bootstrap.js', $destination . '/js/bootstrap.js');
        copy('vendor/twbs/bootstrap/dist/js/bootstrap.min.js', $destination . '/js/bootstrap.min.js');

        self::copy('vendor/twbs/bootstrap/dist/fonts', $destination . '/fonts');
    }

    public static function jquery(string $destination)
    {
        echo 'copying - jquery' . PHP_EOL;
        copy('vendor/components/jquery/jquery.js', $destination . '/js/jquery.js');
        copy('vendor/components/jquery/jquery.min.js', $destination . '/js/jquery.min.js');
        copy('vendor/components/jquery/jquery.min.map', $destination . '/js/jquery.min.map');
    }

    public static function fontAwesome(string $destination)
    {
        echo 'copying - font-awesome' . PHP_EOL;

        $content = file_get_contents('vendor/components/font-awesome/css/font-awesome.css');
        $content = preg_replace('~url\(\'\.\.([^\']+)\'\)~s', 'url("\\1")', $content);
        $content = preg_replace('~(\?|&)v=\d+\.\d+\.\d+\"~s', '"', $content);
        file_put_contents($destination . '/css/font-awesome.css', $content, LOCK_EX);

        $content = file_get_contents('vendor/components/font-awesome/css/font-awesome.min.css');
        $content = preg_replace('~url\(\'\.\.([^\']+)\'\)~s', 'url("\\1")', $content);
        $content = preg_replace('~(\?|&)v=\d+\.\d+\.\d+\"~s', '"', $content);
        file_put_contents($destination . '/css/font-awesome.min.css', $content, LOCK_EX);

        self::copy('vendor/components/font-awesome/fonts', $destination . '/fonts');
    }

    public static function package(string $destination, string $package)
    {
        echo 'assets: ' . $package . PHP_EOL;
        foreach (['css', 'js', 'fonts', 'images'] as $type) {
            self::copy('vendor/' . $package . '/assets/' . $type, $destination . '/' . $type);
        }
    }

    public static function copy(string $source, string $destination)
    {
        if (!file_exists($source)) {
            return;
        }
        $directory = new RecursiveDirectoryIterator($source);
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $key => $file) {
            if ($iterator->isDot()) {
                continue;
            }
            $_path = trim(substr($iterator->getPath(), strlen($source)) . '/');
            if ($_path && !file_exists($destination . '/' . $_path)) {
                @mkdir($destination . '/' . $_path, 0777, true);
            }
            // echo $iterator->getPathName() . PHP_EOL;
            copy($file, $destination . '/' . $_path . '/' . $iterator->getFilename());
        }
    }
}