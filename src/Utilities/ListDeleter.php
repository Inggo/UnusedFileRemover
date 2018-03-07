<?php

namespace Inggo\Utilities;

class ListDeleter
{
    public $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function delete()
    {
        foreach ($this->list as $item) {
            echo "Deleting " . $item . ".\n";
            unlink($item);
        }
    }
}