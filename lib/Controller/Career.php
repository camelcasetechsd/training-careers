<?php

include_once '../Model/Career.php';

/*
 * Function to convert any array to utf-8 encoded array
 * 
 *  @param  array assosiative array
 *  @return utf-8 encoded assosiative array ready for json_encoding 
 */

function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key) {
        if (!mb_detect_encoding($item, 'utf-8', true)) {
            $item = utf8_encode($item);
        }
    });

    return $array;
}

$db = new Career();
$result = $db->getCareers();
$encodedResult = utf8_converter($result);
$careers = json_encode($encodedResult);
echo $careers;
