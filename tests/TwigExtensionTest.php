<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @link      https://github.com/codeguy/Slim
 * @copyright Copyright (c) 2011-2015 Josh Lockhart
 * @license   https://github.com/codeguy/Slim/blob/master/LICENSE (MIT License)
 */
namespace Slim\Tests\Views;

use Slim\Http\Uri;
use Slim\Router;
use Slim\Views\TwigExtension;

require dirname(__DIR__) . '/vendor/autoload.php';

class TwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function isCurrentPathProvider()
    {
        $router = new Router();

        $router->map(['GET'], '/hello/{name}', null)->setName('foo');
        $uri = Uri::createFromString('http://example.com/hello/world');

        $uri2 = $uri->withBasePath('bar');
        $router->map(['GET'], '/bar/hello/{name}', null)->setName('bar');

        return [
            [$router, $uri, 'foo', ['name' => 'world'], true],
            [$router, $uri2, 'bar', ['name' => 'world'], true],
            [$router, $uri, 'bar', ['name' => 'world'], false],
        ];
    }

    /**
     * @dataProvider isCurrentPathProvider
     */
    public function testIsCurrentPath($router, $uri, $name, $data, $expected)
    {
        $extension = new TwigExtension($router, $uri);
        $result = $extension->isCurrentPath($name, $data);

        $this->assertEquals($expected, $result);
    }
}