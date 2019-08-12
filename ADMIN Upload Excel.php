<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


$output = '';
if(isset($_POST["import"]))
{
    $extension = end(explode(".", $_FILES["excel"]["name"])); // For getting Extension of selected file
    $allowed_extension = array("xls", "xlsx", "csv"); //allowed extension
    if(in_array($extension, $allowed_extension)) //check selected file extension is present in allowed extension array
    {
        $file = $_FILES["excel"]["tmp_name"]; // getting temporary source of excel file
        include("PHPExcel/PHPExcel/Classes/PHPExcel/IOFactory.php"); // Add PHPExcel Library in this code
        $objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file

        $output .= "<label class='text-success'>Data Inserted</label><br /><table class='table table-bordered'>";
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
        {
            $highestRow = $worksheet->getHighestRow();
            for($row=2; $row<=$highestRow; $row++)
            {
                $output .= "<tr>";
                $id = mysqli_real_escape_string($dbc, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                $fullname = mysqli_real_escape_string($dbc, $worksheet->getCellByColumnAndRow(1, $row)->getValue());


                $query = "INSERT INTO excel_members(DLSU_ID, DLSU_NAME) VALUES ('".$id."', '".$fullname."')";
                mysqli_query($dbc, $query);
                $output .= '<td>'.$id.'</td>';
                $output .= '<td>'.$fullname.'</td>';
                $output .= '</tr>';
            }
        }
        $output .= '</table>';

    }
    else
    {
        $output = '<label class="text-danger">Invalid File</label>'; //if non excel file then
    }
}

$page_title = 'Admin - Upload Excel Database';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';

?>

<html>
<head>
    <title>Import Excel to Mysql using PHPExcel in PHP</title>

    <style>
        body
        {
            margin:0;
            padding:0;
            background-color:#f1f1f1;
        }
        .box
        {
            width:700px;
            border:1px solid #ccc;
            background-color:#fff;
            border-radius:5px;
            margin-top:100px;
        }

    </style>
</head>
<body>
<div class="container box">
    <h3 align="center">Import Excel to Mysql using PHPExcel in PHP</h3><br />
    <form method="post" enctype="multipart/form-data">
        <label>Select Excel File</label>
        <input type="file" name="excel" />
        <br />
        <input type="submit" name="import" class="btn btn-info" value="Import" />
    </form>
    <br />
    <br />
    <?php
    echo $output;
    ?>
</div>
</body>
</html>