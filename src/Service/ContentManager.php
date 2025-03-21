<?php

namespace MarkFlat\MarkFlatEditor\Service;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ContentManager
{
    private string $contentDir;

    public function __construct(string $projectDir)
    {
        $this->contentDir = $projectDir . '/content';
        if (!is_dir($this->contentDir)) {
            mkdir($this->contentDir, 0755, true);
        }
    }

    public function getContentTree(): array
    {
        if (!is_dir($this->contentDir)) {
            return [];
        }

        $finder = new Finder();
        $finder->files()
            ->in($this->contentDir)
            ->name('*.md')
            ->sortByName();

        $tree = [];
        foreach ($finder as $file) {
            $relativePath = $this->getRelativePath($file);
            $parts = explode('/', $relativePath);
            $current = &$tree;
            
            foreach ($parts as $part) {
                if (!isset($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
        }

        return $tree;
    }

    public function getFileContent(string $path): string
    {
        $fullPath = $this->contentDir . '/' . $path;
        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($fullPath);
        }
        return file_get_contents($fullPath);
    }

    public function saveFileContent(string $path, string $content): void
    {
        $fullPath = $this->contentDir . '/' . $path;
        $directory = dirname($fullPath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($fullPath, $content);
    }

    public function deleteFile(string $path): void
    {
        if (str_starts_with($path, 'elements/')) {
            throw new \InvalidArgumentException('Cannot delete files from elements directory');
        }

        $fullPath = $this->contentDir . '/' . $path;
        if (!file_exists($fullPath)) {
            throw new FileNotFoundException($fullPath);
        }

        unlink($fullPath);
    }

    public function createFile(string $path): void
    {
        if (str_starts_with($path, 'elements/')) {
            throw new \InvalidArgumentException('Cannot create files in elements directory');
        }

        $fullPath = $this->contentDir . '/' . $path;
        if (file_exists($fullPath)) {
            throw new \InvalidArgumentException('File already exists');
        }

        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($fullPath, '');
    }

    private function getRelativePath(SplFileInfo $file): string
    {
        return str_replace('\\', '/', $file->getRelativePathname());
    }
}
