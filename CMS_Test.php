<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 11/9/2018
 * Time: 10:59 AM
 */
 //include 'GLOBAL_HEADER.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="pell-master/dist/pell.min.css">
    <script src="pell-master/dist/pell.min.js"></script>
    <script>
        window.pell.init({
            root: 'pell',
            actions: [
                'bold',
                { name: 'italic', icon: '&#9786;', title: 'Zitalic' },
                'underline'
            ],
            classes: {
                actionbar: 'pell-actionbar',
                button: 'pell-button',
                editor: 'pell-editor'
            },
        })

    </script>

</head>
<body>


<div id="pell" class="pell"></div>


</body>
</html>

