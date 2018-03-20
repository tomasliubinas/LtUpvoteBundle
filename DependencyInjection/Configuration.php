<?php

namespace Lt\UpvoteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
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
                            ->booleanNode('allow_upvote')->defaultTrue()->end()
                            ->booleanNode('allow_downvote')->defaultTrue()->end()
                            ->booleanNode('allow_anonymous_upvote')->defaultTrue()->end()
                            ->booleanNode('allow_anonymous_downvote')->defaultTrue()->end()
                        ->end()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
