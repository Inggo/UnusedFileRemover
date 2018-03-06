<?php

include "vendor/autoload.php";

$longopts = [
    "imgdir:",
    "pubdir:",
    "baseurl::",
];

$options = getopt('', $longopts);

var_dump((new Inggo\Utilities\UsedImageLister($options["pubdir"]))->listUsedImages());
