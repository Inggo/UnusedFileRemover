<?php

namespace Inggo\Utilities;

class HTMLLister extends FileLister
{
    public $htmlMimeTypes = [
        "text/html"
    ];

    private $finfo;

    public function __construct($path)
    {
        parent::__construct($path);
        $this->setMimeTypes($this->htmlMimeTypes);
    }
}
