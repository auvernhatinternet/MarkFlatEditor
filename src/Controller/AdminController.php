<?php

namespace MarkFlat\MarkFlatEditor\Controller;

use MarkFlat\MarkFlatEditor\Service\ContentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly string $adminPassword,
        private readonly ContentManager $contentManager
    ) {
    }

    #[Route('', name: 'mark_flat_editor_admin', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $submittedPassword = $request->query->get('password');
        
        if ($submittedPassword !== $this->adminPassword) {
            return $this->render('@MarkFlatEditor/admin/login.html.twig');
        }

        $contentTree = $this->contentManager->getContentTree();

        return $this->render('@MarkFlatEditor/admin/index.html.twig', [
            'contentTree' => $contentTree,
            'password' => $submittedPassword
        ]);
    }

    #[Route('/edit/{path}', name: 'mark_flat_editor_edit', methods: ['GET'], requirements: ['path' => '.+'])]
    public function edit(Request $request, string $path): Response
    {
        $submittedPassword = $request->query->get('password');
        if ($submittedPassword !== $this->adminPassword) {
            return $this->redirectToRoute('mark_flat_editor_admin');
        }

        try {
            $content = $this->contentManager->getFileContent($path);
        } catch (\Exception $e) {
            $content = '';
        }

        return $this->render('@MarkFlatEditor/admin/edit.html.twig', [
            'path' => $path,
            'content' => $content,
            'password' => $submittedPassword
        ]);
    }

    #[Route('/save/{path}', name: 'mark_flat_editor_save', methods: ['POST'], requirements: ['path' => '.+'])]
    public function save(Request $request, string $path): JsonResponse
    {
        $submittedPassword = $request->query->get('password');
        if ($submittedPassword !== $this->adminPassword) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $content = $request->request->get('content');
        if ($content === null) {
            return new JsonResponse(['error' => 'No content provided'], 400);
        }

        try {
            $this->contentManager->saveFileContent($path, $content);
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/delete/{path}', name: 'mark_flat_editor_delete', methods: ['POST'], requirements: ['path' => '.+'])]
    public function delete(Request $request, string $path): JsonResponse
    {
        $submittedPassword = $request->query->get('password');
        if ($submittedPassword !== $this->adminPassword) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        try {
            $this->contentManager->deleteFile($path);
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/create', name: 'mark_flat_editor_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $submittedPassword = $request->query->get('password');
        if ($submittedPassword !== $this->adminPassword) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $path = $request->request->get('path');
        if (!$path) {
            return new JsonResponse(['error' => 'No path provided'], 400);
        }

        // Ensure the path ends with .md
        if (!str_ends_with($path, '.md')) {
            $path .= '.md';
        }

        try {
            $this->contentManager->createFile($path);
            return new JsonResponse(['success' => true, 'path' => $path]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
