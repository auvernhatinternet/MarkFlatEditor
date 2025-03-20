<?php

namespace MarkFlatEditor\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private string $adminPassword;

    public function __construct(string $adminPassword)
    {
        $this->adminPassword = $adminPassword;
    }

    #[Route('/admin', name: 'markflat_admin')]
    public function index(Request $request): Response
    {
        $submittedPassword = $request->query->get('password');
        
        if ($submittedPassword !== $this->adminPassword) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        return $this->render('@MarkFlatEditor/admin/index.html.twig');
    }
}
