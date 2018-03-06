<?php

namespace Inggo\Utilities;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class FileLister
{
    public $files = [];
    public $path;

    public $fileMimeTypes = [];

    private $finfo;

    public function __construct($path)
    {
        $this->path = $path;
        $this->finfo = finfo_open(FILEINFO_MIME_TYPE);
    }

    protected function setMimeTypes(array $mimeTypes)
    {
        $this->fileMimeTypes = $mimeTypes;
    }

    public function getFiles($force = false)
    {
        if (!$force && !empty($this->files)) {
            return $this->files;
        }

        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));
        $it->rewind();

        while ($it->valid())
        {
            if ($it->isDot()) {
                $it->next();
                continue;
            }
            
            if ($mime = $this->matchMime($it->key())) {
                $this->files[] = $it->key();
            }

            $it->next();
        }

        return $this->files;
    }

    private function matchMime($file)
    {
        $mime = finfo_file($this->finfo, $file);
        return in_array($mime, $this->fileMimeTypes) ? $mime : false;
    }
}