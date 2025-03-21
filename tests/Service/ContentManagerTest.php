<?php

namespace MarkFlat\MarkFlatEditor\Tests\Service;

use MarkFlat\MarkFlatEditor\Service\ContentManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ContentManagerTest extends TestCase
{
    private ContentManager $contentManager;
    private string $testDir;
    private string $contentDir;

    protected function setUp(): void
    {
        $this->testDir = sys_get_temp_dir() . '/markflat_test_' . uniqid();
        $this->contentDir = $this->testDir . '/content';

        if (!is_dir($this->testDir)) {
            mkdir($this->testDir);
        }

        $this->contentManager = new ContentManager($this->testDir);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->testDir)) {
            $this->removeDirectory($this->testDir);
        }
    }

    public function testGetContentTree(): void
    {
        // Content directory is created by ContentManager constructor
        file_put_contents($this->contentDir . '/test1.md', 'content1');
        mkdir($this->contentDir . '/subdir');
        file_put_contents($this->contentDir . '/subdir/test2.md', 'content2');

        $tree = $this->contentManager->getContentTree();

        $this->assertIsArray($tree);
        $this->assertArrayHasKey('test1.md', $tree);
        $this->assertArrayHasKey('subdir', $tree);
        $this->assertIsArray($tree['subdir']);
        $this->assertArrayHasKey('test2.md', $tree['subdir']);
    }

    public function testGetContentTreeEmptyDir(): void
    {
        $tree = $this->contentManager->getContentTree();
        $this->assertIsArray($tree);
        $this->assertEmpty($tree);
    }

    public function testGetFileContent(): void
    {
        $testContent = "# Test Content\nThis is a test";
        file_put_contents($this->contentDir . '/test.md', $testContent);

        $content = $this->contentManager->getFileContent('test.md');
        $this->assertEquals($testContent, $content);
    }

    public function testGetFileContentNotFound(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->contentManager->getFileContent('nonexistent.md');
    }

    public function testSaveFileContent(): void
    {
        $testContent = "# New Content\nThis is new content";

        $this->contentManager->saveFileContent('new.md', $testContent);

        $this->assertFileExists($this->contentDir . '/new.md');
        $this->assertEquals($testContent, file_get_contents($this->contentDir . '/new.md'));
    }

    public function testSaveFileContentInSubdirectory(): void
    {
        $testContent = "# Subdirectory Content\nThis is in a subdirectory";

        $this->contentManager->saveFileContent('subdir/test.md', $testContent);

        $this->assertFileExists($this->contentDir . '/subdir/test.md');
        $this->assertEquals($testContent, file_get_contents($this->contentDir . '/subdir/test.md'));
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
