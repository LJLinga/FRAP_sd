<?php

require '../vendor/froala/wysiwyg-editor-php-sdk/lib/FroalaEditor.php';

// Store the image.
try {
    //the address is at the root of localhost
    $response = FroalaEditor_Image::upload('/FRAP_sd/uploads/cms/images/');
    echo stripslashes(json_encode($response));
}
catch (Exception $e) {
    http_response_code(404);
}

?>