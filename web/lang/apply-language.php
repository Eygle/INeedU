<?php
/**
 * Created by PhpStorm.
 * User: Johan_2
 * Date: 01/10/2015
 * Time: 13:51
 */

if ($argc < 3) {
    echo "Usage: ".$argv[0]." path {lang: fr-FR | en-US | ...}\n";
    exit(-1);
}

$AVAILABLE_LANGUAGES = array("fr-FR", "en-US");

$path = $argv[1];
$lang = $argv[2];
if (!in_array($lang, $AVAILABLE_LANGUAGES)) {
    echo "Invalid value '$lang' for lang parameter\n";
    exit(-1);
}

$EXTENSIONS_TO_CHECK = array("html", "js");

$sentences = json_decode(file_get_contents(dirname(__FILE__) . "/lang-$lang.json"), true);

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
foreach($objects as $file => $object){
    if (strstr($file, "bower_components"))
        continue;
    if (is_file($file)) {
        $path_parts = pathinfo($file);
        if (isset($path_parts['extension']) && in_array($path_parts['extension'], $EXTENSIONS_TO_CHECK)) {
            $data = file_get_contents($file);
            echo "Process $file\n";
            foreach ($sentences as $k => $v) {
                echo "\tReplace %$k% by $v\n";
                $data = str_replace("%$k%", $v, $data);
            }
            file_put_contents($file, $data);
        }
    }
}