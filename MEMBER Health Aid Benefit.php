<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';



$checkIfConsentedQuery = "SELECT HA_STATUS, USER_STATUS, DATE_APPROVED, FIRSTNAME, LASTNAME FROM member where MEMBER_ID = {$_SESSION['idnum']}";
$checkIfConsentedResult = mysqli_query($dbc,$checkIfConsentedQuery);
$checkIfConsented = mysqli_fetch_array($checkIfConsentedResult);

$userStatus = $checkIfConsented['USER_STATUS'];

$fullName = $checkIfConsented['FIRSTNAME']." ".$checkIfConsented['LASTNAME'];

if($checkIfConsented['HA_STATUS'] == 2){ //meaning this dude has already consented and now its time to boot them out

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

}
// this is for the part time

$dateApproved = new DateTime($checkIfConsented['DATE_APPROVED']); //

$dateNow = new DateTime();

$dateNow->diff($dateApproved)->format("%m");//returns and converts the format to months I guess?


// this is for the full time

$yearApproved = date('Y',strtotime($checkIfConsented['DATE_APPROVED']));

$yearNOW = date('Y');



if(isset($_POST['submit'])){

    $query = "UPDATE member SET HA_STATUS = 2,HA_CONSENT_TIMESTAMP = NOW() where MEMBER_ID = {$_SESSION['idnum']}";
    mysqli_query($dbc,$query);

    $query = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                         VALUES({$_SESSION['idnum']}, 3, 'Health Aid Application Approved!', 0.00 , 2)";
    mysqli_query($dbc,$query);

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

}

$noResidency = null;

$page_title = 'Services - Health Aid Benefit Consent';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';

?>


<div id="page-wrapper">

    <div class="container-fluid">


        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <br>
            <div class="row"> <!-- Title & Breadcrumb -->

                <div class ="row">
                    <?php if(!($yearApproved - $yearNOW) >= 1 && $userStatus == 1){ //pag full time ka at hindi ka pa nakakapag 1 year residency
                        $noResidency = true; // to make things easier for checking later.

                        ?>

                        <div class="col-lg-12" id="alertLocation" >

                            <div id="message" class="alert alert-danger">
                                <strong>
                                    <span id="messageAlert">Sorry! You cannot apply just yet, you have not completed your one year of residency.</span>
                                </strong>

                            </div>

                        </div>
                    <?php }else if(!$dateNow < 4 && $userStatus == 2){
                        $noResidency = true; // to make things easier for checking later.

                        ?>
                    <div class="col-lg-12" id="alertLocation" >
                        <div id="message" class="alert alert-danger">
                            <strong>
                                <span id="messageAlert">Sorry! You cannot apply just yet, you have not completed your 4 Months of residency.</span>
                            </strong>

                        </div>

                    </div>


                    <?php } ?>
                </div>

                <div class="col-lg-12">

                    <h1 class="page-header">Health Aid Application Consent Form</h1>

                </div>

                <div class = "col-lg-12">

                    <label>Before you can acquire for the health aid, you would first have to consent to being deducted from your salary.</label>

                </div>

            </div>

            <div class = "row" style="margin-top: 5px;">

                <div class="col-lg-9">

                    <div class="panel panel-success">

                        <div class="panel-heading">

                            <b> Consent</b>

                        </div>


                        <div class="panel-body">

                            <div class="checkbox">

                                <input id="check" name="checkbox" type="checkbox" style="margin-left: 2px" required>

                                <label for="checkbox">

                                    I, <?php echo $fullName ; ?> consent to be deducted by P100.00 per term to receive the full benefits of the Health Aid. In addition to this, with the consideration of the data privacy law, by sending this application,
                                    I allow AFED Inc to secure a copy of my hospital bill for compliance and requirement purposes of this service.

                                </label>

                            </div>


                        </div>

                    </div>

                </div>


            </div>

            <div class="row">
                <div class="col-lg-1">
                    <?php if($noResidency){ ?>
                        <input type="submit" name="submit" class="btn btn-success" value="Submit Application" disabled>
                    <?php }else { ?>

                        <input type="submit" name="submit" class="btn btn-success" value="Submit Application">

                    <?php }?>
                </div>
            </div>

        </form>

    </div>

</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
