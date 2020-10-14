<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test_route")
     */
    public function testAction()
    {
        return $this->render('test/test.html.twig');
    }
}
