<?php

namespace Inggo\Utilities;

use Inggo\Utilities\ImageLister;
use Inggo\Utilities\FileLister;

class UnusedRemover
{
    public function __construct($imgdir, $pubdir)
    {
        
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