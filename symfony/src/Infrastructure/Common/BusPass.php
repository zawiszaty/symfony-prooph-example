<?php

declare(strict_types=1);

namespace App\Infrastructure\Common;

use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\QueryHandler\QueryBus;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BusPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CommandBus::class)) {
            return;
        }
        $definition = $container->findDefinition(CommandBus::class);
        $taggedServices = $container->findTaggedServiceIds('app.command_handler');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addCommandHandler', [new Reference($id)]);
        }

        if (!$container->has(QueryBus::class)) {
            return;
        }
        $definition = $container->findDefinition(QueryBus::class);
        $taggedServices = $container->findTaggedServiceIds('app.query_handler');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addQueryHandler', [new Reference($id)]);
        }
    }
}
