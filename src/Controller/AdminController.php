<?php

namespace MarkFlat\MarkFlatEditor\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly string $adminPassword
    ) {
    }

    #[Route('', name: 'mark_flat_editor_admin', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $submittedPassword = $request->query->get('password');
        
        if ($submittedPassword !== $this->adminPassword) {
            return $this->render('@MarkFlatEditor/admin/login.html.twig');
        }

        return $this->render('@MarkFlatEditor/admin/index.html.twig');
    }
}
