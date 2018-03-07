<?php

namespace Inggo\Models;

class ImageReference
{
    public $path;

    public function __construct($file, $pubDir)
    {
        $pubDir = substr($pubDir, -1) == '/' ? $pubDir : $pubDir . '/';
        $file = substr($file, 0, 1) == '/' ? substr($file, 1, strlen($file) - 1) : $file;
        $this->path = realpath($pubDir . $file);
    }
}