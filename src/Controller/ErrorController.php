<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Error", name="error")
 */
class ErrorController extends AbstractController
{
    /**
     * @Route("", name="")
     */
    public function index(): Response
    {
        $this->addFlash('error','An error occured');
        return $this->render('error');
    }

    /**
     * @Route("/NotFound", name="notfound")
     */
    public function notFound(): Response
    {
        $this->addFlash('error','Not found');
        return $this->render('error');
    }

    /**
     * @Route("/PermissionError", name="permissionerror")
     */
    public function permissionError(): Response
    {
        $this->addFlash('error','Permission error');
        return $this->render('error');
    }
}
