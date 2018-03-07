<?php

namespace Inggo\Utilities;

class ListPrinter
{
    public $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function print()
    {
        foreach ($this->list as $item) {
            echo $item . "\n";
        }
    }
}