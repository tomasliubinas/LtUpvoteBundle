<?php

namespace Lt\UpvoteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration from app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lt_upvote');

        $rootNode
            ->children()
                ->arrayNode('types')->requiresAtLeastOneElement()->normalizeKeys(false)->ignoreExtraKeys(true)
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('show_upvote')->defaultTrue()->end()
                            ->booleanNode('show_downvote')->defaultTrue()->end()
                            ->booleanNode('allow_unauthenticated_upvote')->defaultTrue()->end()
                            ->booleanNode('allow_unauthenticated_downvote')->defaultTrue()->end()
                        ->end()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
