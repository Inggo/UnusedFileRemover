<?php

namespace Inggo\Utilities;

use Inggo\Utilities\ImageLister;
use Inggo\Utilities\UsedImageLister;

class UnusedImageLister
{
    public $usedImageLister;
    public $imageLister;

    public $allImages = [];
    public $usedImages = [];
    public $unusedImages = [];

    public function __construct($pubDir, $imgDir, $baseUrl = null)
    {
        $this->usedImageLister = new UsedImageLister($pubDir, $baseUrl);
        $this->usedImages = $this->usedImageLister->listUsedImages();

        $this->imageLister = new ImageLister($imgDir);
        $this->allImages = $this->imageLister->getImages();

        $this->unusedImages = array_diff($this->allImages, $this->usedImages);
    }
}