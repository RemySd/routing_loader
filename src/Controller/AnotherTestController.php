<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AnotherTestController extends Controller
{
    /**
     * @Route("/another-test", name="another_test_route")
     */
    public function testAction()
    {
        return $this->render('test/test.html.twig');
    }
}
