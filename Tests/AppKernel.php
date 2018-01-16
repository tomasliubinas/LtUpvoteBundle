<?php

namespace Lt\UpvoteBundle\Tests;

use Symfony\Component\HttpKernel\Kernel;
use Lt\UpvoteBundle\LtUpvoteBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * AppKernel provides framework bootstrapping for standalone bundle testing
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new LtUpvoteBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/app/config/config_'.$this->getEnvironment().'.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__.'/app/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__.'/app/cache';
    }
}