<?php

namespace Inggo\Utilities;

use Inggo\Utilities\HTMLLister;
use DOMDocument;

class UsedImageLister
{
    public $htmlLister;

    public $images = [];
    public $files = [];

    public function __construct($pubDir)
    {
        $this->htmlLister = new HTMLLister($pubDir);
        $this->files = $this->htmlLister->getFiles();
    }

    public function listUsedImages($force = false)
    {
        if (!$force && !empty($this->images)) {
            return $this->images;
        }

        foreach ($this->files as $file) {
            $this->extractImages($file);
        }

        return $this->images;
    }

    public function extractImages($file)
    {
        $doc = new DOMDocument;
        $doc->loadHTML(file_get_contents($file));
       
        $imgs = $doc->getElementsByTagName('img');
        foreach ($imgs as $img) {
            $this->extractImage($img->getAttribute('src'));
        }
    }

    public function extractImage($img)
    {
        if (!in_array($img, $this->images)) {
            $this->images[] = $img;
        }
    }
}