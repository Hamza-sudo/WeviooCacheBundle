<?php

namespace Wevioo\WeviooCacheBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wevioo\WeviooCacheBundle\DependencyInjection\WeviooCacheExtension;

class WeviooCacheBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new WeviooCacheExtension();
        }
        return $this->extension;
    }

}