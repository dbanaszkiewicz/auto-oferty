<?php

namespace ApiBundle\Utils;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Controller extends SymfonyController
{

    public function setContainer(ContainerInterface $container = NULL)
    {
        parent::setContainer($container);

        $this->doSomeStuff();
    }

    protected function doSomeStuff()
    {
    }
}