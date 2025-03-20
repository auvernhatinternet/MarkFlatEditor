<?php

namespace MarkFlatEditor;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkFlatEditorBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}