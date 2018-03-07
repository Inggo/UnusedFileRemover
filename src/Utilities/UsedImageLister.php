<?php

namespace Inggo\Utilities;

use Inggo\Models\ImageReference;
use Inggo\Utilities\HTMLLister;
use DOMDocument;
use DOMXPath;

class UsedImageLister
{
    public $htmlLister;

    public $images = [];
    public $files = [];

    public $pubDir = "";
    public $baseUrl = null;

    public function __construct($pubDir, $baseUrl = null)
    {
        $this->pubDir = $pubDir;
        $this->baseUrl = $baseUrl;
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
        libxml_use_internal_errors(true);
        $doc->loadHTML(file_get_contents($file));
       
        $imgs = $doc->getElementsByTagName('img');
        foreach ($imgs as $img) {
            $this->extractImage($img->getAttribute('src'));
        }

        $sources = $doc->getElementsByTagName('source');
        foreach ($sources as $source) {
            $this->extractImage($source->getAttribute('srcset'));
        }

        $xpath = new DOMXPath($doc);
        $nodes = $xpath->query("//@style");

        foreach ($nodes as $node) {
            $this->extractImageFromAttribute($node->value);
        }
    }

    public function extractImage($img)
    {
        if ($this->baseUrl) {
            $img = str_replace($this->baseUrl, "/", $img);
        }
        $image = new ImageReference($img, $this->pubDir);
        if (!in_array($image->path, $this->images)) {
            $this->images[] = $image->path;
        }
    }

    public function extractImageFromAttribute($attr)
    {
        preg_match_all('/background(-image)??\s*?:.*?url\(["|\']??(.+)["|\']??\)/', $attr, $matches, PREG_SET_ORDER);
        if (count($matches) && isset($matches[0][2])) {
            $this->extractImage($matches[0][2]);
        }
    }
}