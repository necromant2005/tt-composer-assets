<?php

namespace Twee\Composer;

use PHPUnit_Framework_TestCase as TestCase;

class AssetsTest extends TestCase
{
    public function setUp()
    {
        @mkdir(__DIR__ . '/_files/AssetsTest/css', 0777, true);
        @mkdir(__DIR__ . '/_files/AssetsTest/js', 0777, true);
        @mkdir(__DIR__ . '/_files/AssetsTest/fonts', 0777, true);
    }

    public function tearDown()
    {
        system('rm -rf ' . __DIR__ . '/_files/AssetsTest/css');
        system('rm -rf ' . __DIR__ . '/_files/AssetsTest/js');
        system('rm -rf ' . __DIR__ . '/_files/AssetsTest/fonts');
    }

    public function testBootstrap()
    {
        $destination = __DIR__ . '/_files/AssetsTest';
        Assets::bootstrap($destination);
        $this->assertTrue(file_exists($destination . '/css/bootstrap.css'));
        $this->assertTrue(file_exists($destination . '/css/bootstrap.css.map'));
        $this->assertTrue(file_exists($destination . '/js/bootstrap.js'));
        $this->assertEquals(5, count(glob($destination . '/fonts/glyphicons-halflings-regular.*')));
        $this->assertNotRegExp('~url\(\'~', file_get_contents($destination . '/css/bootstrap.css'));
    }

    public function testJquery()
    {
        $destination = __DIR__ . '/_files/AssetsTest';
        Assets::jquery($destination);
        $this->assertTrue(file_exists($destination . '/js/jquery.js'));
    }

    public function testFontAwesome()
    {
        $destination = __DIR__ . '/_files/AssetsTest';
        Assets::fontAwesome($destination);
        $this->assertTrue(file_exists($destination . '/css/font-awesome.min.css'));
        $this->assertEquals(5, count(glob($destination . '/fonts/fontawesome-webfont.*')));
    }

    public function testPackage()
    {
        $destination = __DIR__ . '/_files/AssetsTest';
        $dir = getcwd();
        chdir($destination);
        Assets::package($destination, 'test');
        chdir($dir);
        $this->assertTrue(file_exists($destination . '/css/level-down/test.css'));
        $this->assertEquals('.my-supper-custom-class {}', trim(file_get_contents($destination . '/css/level-down/test.css')));
    }
}