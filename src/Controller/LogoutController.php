<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Security\Authentication\Service\LogoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Logout", name="logout")
 */
class LogoutController extends AbstractController
{
    /**
     * @Route("", name="")
     */
    public function index(LogoutService $logoutService): Response
    {
        $logoutService->logout();
        return $this->redirectToRoute('login');
    }
}
