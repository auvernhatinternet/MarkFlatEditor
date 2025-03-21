<?php

namespace MarkFlat\MarkFlatEditor\Tests;

use MarkFlat\MarkFlatEditor\MarkFlatEditorBundle;
use MarkFlat\MarkFlatEditor\DependencyInjection\Compiler\TwigExtensionPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkFlatEditorBundleTest extends TestCase
{
    public function testBundle(): void
    {
        $bundle = new MarkFlatEditorBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);

        $container = new ContainerBuilder();
        $bundle->build($container);

        $passes = $container->getCompilerPassConfig()->getPasses();
        $hasTwigExtensionPass = false;

        foreach ($passes as $pass) {
            if ($pass instanceof TwigExtensionPass) {
                $hasTwigExtensionPass = true;
                break;
            }
        }

        $this->assertTrue($hasTwigExtensionPass, 'Bundle should register TwigExtensionPass');
    }
}
