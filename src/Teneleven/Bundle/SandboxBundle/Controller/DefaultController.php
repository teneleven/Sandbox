<?php

namespace Teneleven\Bundle\SandboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TenelevenSandboxBundle:Default:index.html.twig', array());
    }
}
