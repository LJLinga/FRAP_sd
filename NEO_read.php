<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

//Pre load LOGIC

$userId = $_SESSION['idnum'];
$page_title = 'TESTPAGE';
include 'GLOBAL_HEADER.php';

?>

</div>
</nav>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6">
            <?php
                if()

            ?>
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>

    </div>
</div>

    <script>
        $(document).ready(function(){

            fetch_poll_data();

            function fetch_poll_data()
            {
                $.ajax({
                    url:"fetch_poll_data.php",
                    method:"POST",
                    success:function(data)
                    {
                        $('#poll_result').html(data);
                    }
                })
            }

        });
    </script>

<?php include 'GLOBAL_FOOTER.php'?>
