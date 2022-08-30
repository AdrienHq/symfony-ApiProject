<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class index extends AbstractController
{
    #[Route('/')]
    public function landingPage()
    {
        return $this->render('base.html.twig');
    }

}