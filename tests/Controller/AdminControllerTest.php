<?php

namespace MarkFlat\MarkFlatEditor\Tests\Controller;

use MarkFlat\MarkFlatEditor\Controller\AdminController;
use MarkFlat\MarkFlatEditor\Service\ContentManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AdminControllerTest extends TestCase
{
    private AdminController&MockObject $controller;
    private ContentManager&MockObject $contentManager;
    private const ADMIN_PASSWORD = 'test_password';

    protected function setUp(): void
    {
        $this->contentManager = $this->createMock(ContentManager::class);
        $this->controller = $this->getMockBuilder(AdminController::class)
            ->setConstructorArgs([self::ADMIN_PASSWORD, $this->contentManager])
            ->onlyMethods(['render', 'redirectToRoute'])
            ->getMock();
    }

    public function testIndexWithoutPassword(): void
    {
        $request = new Request();

        $this->controller
            ->expects($this->once())
            ->method('render')
            ->with('@MarkFlatEditor/admin/login.html.twig')
            ->willReturn(new Response('login content'));

        $response = $this->controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIndexWithValidPassword(): void
    {
        $request = new Request(['password' => self::ADMIN_PASSWORD]);

        $this->contentManager
            ->expects($this->once())
            ->method('getContentTree')
            ->willReturn(['test.md' => []]);

        $this->controller
            ->expects($this->once())
            ->method('render')
            ->with(
                '@MarkFlatEditor/admin/index.html.twig',
                [
                    'contentTree' => ['test.md' => []],
                    'password' => self::ADMIN_PASSWORD
                ]
            )
            ->willReturn(new Response('index content'));

        $response = $this->controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditWithoutPassword(): void
    {
        $request = new Request();

        $this->controller
            ->expects($this->once())
            ->method('redirectToRoute')
            ->with('mark_flat_editor_admin')
            ->willReturn(new RedirectResponse('/admin'));

        $response = $this->controller->edit($request, 'test.md');

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testEditWithValidPassword(): void
    {
        $request = new Request(['password' => self::ADMIN_PASSWORD]);

        $this->contentManager
            ->expects($this->once())
            ->method('getFileContent')
            ->with('test.md')
            ->willReturn('test content');

        $this->controller
            ->expects($this->once())
            ->method('render')
            ->with(
                '@MarkFlatEditor/admin/edit.html.twig',
                [
                    'path' => 'test.md',
                    'content' => 'test content',
                    'password' => self::ADMIN_PASSWORD
                ]
            )
            ->willReturn(new Response('edit content'));

        $response = $this->controller->edit($request, 'test.md');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
