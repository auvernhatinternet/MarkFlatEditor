<?php

namespace MarkFlat\MarkFlatEditor\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigExtensionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        // Remove our bundle's routing extension if it exists
        if ($container->hasDefinition('mark_flat_editor.twig.extension')) {
            $container->removeDefinition('mark_flat_editor.twig.extension');
        }
    }
}
