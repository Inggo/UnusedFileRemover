<?php

namespace Inggo\Utilities;

class ImageLister extends FileLister
{
    public $imageMimeTypes = [
        "image/gif",
        "image/jpeg",
        "image/jpg",
        "image/png"
    ];

    private $finfo;

    public function __construct($path)
    {
        parent::__construct($path);
        $this->setMimeTypes($this->imageMimeTypes);
    }

    public function getImages($force = false)
    {
        return $this->getFiles($force);
    }
}
