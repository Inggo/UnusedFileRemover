<?php

include "vendor/autoload.php";

$longopts = [
    "imgdir:",
    "pubdir:",
    "baseurl::",
    "pattern::",
    "also-replace::",
    "also-pattern::",
    "delete",
    "list-unmatched",
];

$options = getopt('', $longopts);

$unusedImageLister = new Inggo\Utilities\UnusedImageLister($options["pubdir"], $options["imgdir"], $options["baseurl"] ?? null);

$unmatched = [];
$toDelete = [];
$alsoDelete = [];

foreach ($unusedImageLister->unusedImages as $image) {
    if (isset($options["pattern"]) && !preg_match($options["pattern"], $image)) {
        $unmatched[] = $image;
        continue;
    }

    $toDelete[] = $image;

    if (isset($options["also-pattern"]) && isset($options["also-replace"])) {
        $also = preg_replace($options["also-pattern"], $options["also-replace"], $image);
        if (file_exists($also)) {
            $alsoDelete[] = $also;
        }
    }
}

$command = -1;

if (isset($options["delete"]) || isset($options["list-unmatched"])) {
    if (isset($options["delete"])) {
        (new Inggo\Utilities\ListDeleter($toDelete))->delete();
        (new Inggo\Utilities\ListDeleter($alsoDelete))->delete();
        if (count($toDelete)) {
            echo count($toDelete) . " files deleted.\n";
        }
        if (count($alsoDelete)) {
            echo count($alsoDelete) . " additional files deleted.\n";
        }
    }

    if (isset($optons["list-unmatched"])) {
        (new Inggo\Utilities\ListPrinter($unmatched))->print();
        if (count($unmatched)) {
            echo count($unmatched) . " unused images that did not match your pattern.\n";
        }
    }
} else {
    // TODO: Turn this into a class
    while (true) {
        switch ($command) {
            case 0:
                exit;
            case 1:
                (new Inggo\Utilities\ListDeleter($toDelete))->delete();
                (new Inggo\Utilities\ListDeleter($alsoDelete))->delete();
                $toDelete = [];
                $alsoDelete = [];
                readline("\nPress ENTER to continue...");
                break;
            case 2:
                (new Inggo\Utilities\ListPrinter($toDelete))->print();
                readline("\nPress ENTER to continue...");
                break;
            case 3:
                (new Inggo\Utilities\ListPrinter($alsoDelete))->print();
                readline("\nPress ENTER to continue...");
                break;
            case 4:
                (new Inggo\Utilities\ListPrinter($unmatched))->print();
                readline("\nPress ENTER to continue...");
                break;
            case 9:
            default:
                echo count($unusedImageLister->allImages) . " all images.\n";
                echo count($unusedImageLister->usedImages) . " used images.\n";
                echo count($unusedImageLister->unusedImages) . " unused images.\n";
                if (count($toDelete)) {
                    echo count($toDelete) . " files to be deleted.\n";
                }
                if (count($alsoDelete)) {
                    echo count($alsoDelete) . " additional files to be deleted.\n";
                }
                if (count($unmatched)) {
                    echo count($unmatched) . " unused images that did not match your pattern.\n";
                }
        }

        echo "[1]: Delete all files.\n";
        echo "[2]: List files to be deleted.\n";
        echo "[3]: List additional files to be deleted.\n";
        echo "[4]: List unmatched files.\n";
        echo "[9]: View Summary.\n";
        echo "[0]: Exit.\n";

        $command = readline("\nEnter command: ");
        echo "\n";
    }
}
