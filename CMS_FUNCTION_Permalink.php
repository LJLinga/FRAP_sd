<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/17/2019
 * Time: 6:52 PM
 */

function generate_permalink($string)
{
    if($string !== mb_convert_encoding( mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
        $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
    $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $string);
    $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
    $string = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $string);
    $string = strtolower( trim($string, '-') );
    return $string;
}

?>