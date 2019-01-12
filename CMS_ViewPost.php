<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/31/2018
 * Time: 10:57 AM
 **/

//Database interaction imports
include 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

//Sticky UI elements imports

$title = "title from SQL query";
$content = "Content from SQL query: The quick brown fox jumps over the lazy dog";

$head = $title;
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';
?>
<script>

</script>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    <?php echo $head; ?>
                </h3>
                <?php
                if(isset($message)){
                    echo"  
                        <div class='alert alert-warning'>
                            ". $message ."
                        </div>
                        ";
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $content; ?>
            </div>
            <div class="col-lg-6">
                List of referenced documents/files/artcles etc...
            </div>

        </div>
    </div>
</div>
<?php include 'GLOBAL_FOOTER.php'; ?>
