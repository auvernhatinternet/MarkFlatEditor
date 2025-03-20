<?php

namespace MarkFlat\MarkFlatEditor;

use MarkFlat\MarkFlatEditor\DependencyInjection\Compiler\TwigExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkFlatEditorBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new TwigExtensionPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}