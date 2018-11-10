<?php

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';

$query = "SELECT MAX(RECORD_ID), APP_STATUS from health_aid where member_id = {$_SESSION['idnum']} and APP_STATUS = 1 ";
$result = mysqli_query($dbc,$query);
$row = mysqli_fetch_assoc($result);

    if($row['APP_STATUS'] == 1){ //checks if you have a pending application

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA appsent.php");

    }else if($row['APP_STATUS'] == 2){ //checks if you have an approved application

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }

$_SESSION['HA_RecordID'] == 0;

$_SESSION['SI_Sibling'] == 0;

//This retrieves the Sibling's maximum
$querySISIB = "SELECT MAX(SIBLING_ID) as SIBLING_ID FROM SIBLINGS;";
$resultSISIB = mysqli_query($dbc, $querySISIB);
$rowSISIB = mysqli_fetch_array($resultSISIB);

//If Null
If($rowSISIB['SIBLING_ID'] == NULL){
    $_SESSION['SI_Sibling'] = 1;
}
else{
    $_SESSION['SI_Sibling'] = $rowSISIB['SI_SIBLING'] + 1;
}

$_SESSION['CI_Child'] == 0;

//This retrieves the Child's maximum
$queryCIC = "SELECT MAX(CHILD_ID) as CHILD_ID FROM CHILDREN;";
$resultCIC = mysqli_query($dbc, $queryCIC);
$rowCIC = mysqli_fetch_array($resultCIC);

//If Null
If($rowCIC['CHILD_ID'] == NULL){
    $_SESSION['CI_Child'] = 1;
}
else{
    $_SESSION['CI_Child'] = $rowCIC['CHILD_ID'] + 1;
}

$message = NULL;
$idnum = $_SESSION['idnum'];
$siblingfirstarray = array();
$siblinglastarray = array();
$siblingmiddlearray = array();
$siblingdayarray = array();
$siblingmontharray = array();
$siblingyeararray = array();
$siblingbirthdatearray = array();
$siblingsexarray = array();
$siblingstatusarray = array();

$childdayarray = array();
$childmontharray = array();
$childyeararray = array();
$childfirstarray = array();
$childlastarray = array();
$childmiddlearray = array();
$childbirthdatearray = array();
$childsexarray = array();
$childstatusarray = array();
$appstatusid = 1;


$queryCiv = "SELECT CIV_STATUS FROM MEMBER WHERE MEMBER_ID = '{$idnum}'";
$resultCiv = mysqli_query($dbc, $queryCiv);
$rowCiv = mysqli_fetch_array($resultCiv);

$queryForm = "SELECT H.APP_STATUS FROM HEALTH_AID H JOIN MEMBER M ON H.MEMBER_ID = M.MEMBER_ID WHERE M.MEMBER_ID = '{$idnum}'";
$resultForm = mysqli_query($dbc, $queryForm);
$rowForm = mysqli_fetch_array($resultForm);

/* check if empty fields */

if (isset($_POST['submit'])) {

    $flag = 0;

    //Father
    if (empty($_POST['fatherfirst']) || empty($_POST['fathermiddle']) || empty($_POST['fatherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fathermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherlast'])) {

        $message.='<p>You forgot to fill up all name fields or inputed an invalid input on Father\'s info!';
        $flag++;

    }

    else {

        $fatherfirst = $_POST['fatherfirst'];
        $fathermiddle = $_POST['fathermiddle'];
        $fatherlast = $_POST['fatherlast'];

    }

    if (empty($_POST['fathermonth']) || empty($_POST['fatherday']) || empty($_POST['fatheryear'])) {

        $message.='<p>You forgot to fill up all birth date fields on Father\'s info!';
        $flag++;

    }

    else {

        $fatherbirthdate = $_POST['fatheryear'] . "-" . $_POST['fathermonth'] . "-" . $_POST['fatherday'];

    }

    //Mother
    if (empty($_POST['motherfirst']) || empty($_POST['mothermiddle']) || empty($_POST['motherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['mothermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherlast'])) {

        $message.='<p>You forgot to fill up all name fields or inputed an invalid input on Mother\'s info!';
        $flag++;

    }

    else {

        $motherfirst = $_POST['motherfirst'];
        $mothermiddle = $_POST['mothermiddle'];
        $motherlast = $_POST['motherlast'];

    }

    if (empty($_POST['mothermonth']) || empty($_POST['motherday']) || empty($_POST['motheryear'])) {

        $message.='<p>You forgot to fill up all birth date fields on Mother\'s info!';
        $flag++;

    }

    else {

        $motherbirthdate = $_POST['motheryear'] . "-" . $_POST['mothermonth'] . "-" . $_POST['motherday'];

    }
    //Spouse - If the member is married
    if($rowCiv['CIV_STATUS'] == 2){
        if (empty($_POST['spousefirst']) || empty($_POST['spousemiddle']) || empty($_POST['spouselast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['spousefirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['spousemiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['spouselast'])) {

            $message.='<p>You forgot to fill up all name fields or inputed an invalid input on Spouse\'s info!';
            $flag++;

        }

        else {

            $spousefirst = $_POST['spousefirst'];
            $spousemiddle = $_POST['spousemiddle'];
            $spouselast = $_POST['spouselast'];

        }

        if (empty($_POST['spousemonth']) || empty($_POST['spouseday']) || empty($_POST['spouseyear'])) {

            $message.='<p>You forgot to fill up all birth date fields on Spouse\'s info!';
            $flag++;

        }

        else {

            $spousebirthdate = $_POST['spouseyear'] . "-" . $_POST['spousemonth'] . "-" . $_POST['spouseday'];

        }
    }

    if (!isset($_POST['hasSibling'])) {

        foreach ($_POST['siblingfirst'] as $siblingfirst) {

            if (empty($siblingfirst) || !preg_match("/^[a-zA-Z ]*$/", $siblingfirst)) {

                echo $siblingfirst + " first ";
                $message.='<p>You forgot to fill up a first name field or inputed an invalid input on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingfirstarray, $siblingfirst);

            }

        }

        foreach ($_POST['siblinglast'] as $siblinglast) {

            if (empty($siblinglast) || !preg_match("/^[a-zA-Z ]*$/", $siblinglast)) {

                $message.='<p>You forgot to fill up a last name field or inputed an invalid input on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblinglastarray, $siblinglast);

            }

        }

        foreach ($_POST['siblingmiddle'] as $siblingmiddle) {

            if (empty($siblingmiddle) || !preg_match("/^[a-zA-Z ]*$/", $siblingmiddle)) {

                $message.='<p>You forgot to fill up a middle name field or inputed an invalid input on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingmiddlearray, $siblingmiddle);

            }

        }

        foreach ($_POST['siblingmonth'] as $siblingmonth) {

            if (empty($siblingmonth)) {

                $message.='<p>You forgot to fill up a month field on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingmontharray, $siblingmonth);

            }

        }

        foreach ($_POST['siblingday'] as $siblingday) {

            if (empty($siblingday)) {

                $message.='<p>You forgot to fill up a day field on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingdayarray, $siblingday);

            }

        }

        foreach ($_POST['siblingyear'] as $siblingyear) {

            if (empty($siblingyear)) {

                $message.='<p>You forgot to fill up a year field on siblings\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingyeararray, $siblingyear);

            }

        }

        foreach ($_POST['siblingsex'] as $siblingsex) {

            if (empty($siblingsex)) {

                $message.='<p>You forgot to choose the sex on sibling\'s info!';
                $flag++;
                break;

            }

            else {

                array_push($siblingsexarray, $siblingsex);

            }

        }

        foreach ($_POST['siblingstatus'] as $siblingstatus) {

            if (empty($siblingstatus)) {

                $message.='<p>You forgot to choose the status on siblings\'s info!';
                $flag++;
                break;

            }

            else {
                array_push($siblingstatusarray, $siblingstatus);

            }

        }
    }

    if ($rowCiv['CIV_STATUS'] != 1) {

        if (!isset($_POST['hasChild'])) {

            foreach ($_POST['childstatus'] as $childstatus) {

                if (empty($childstatus)) {

                    $message.='<p>You forgot to fill up a first name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childstatusarray, $childstatus);

                }

            }

            foreach ($_POST['childfirst'] as $childfirst) {

                if (empty($childfirst) || !preg_match("/^[a-zA-Z ]*$/", $childfirst)) {

                    $message.='<p>You forgot to fill up a first name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childfirstarray, $childfirst);

                }

            }

            foreach ($_POST['childlast'] as $childlast) {

                if (empty($childlast) || !preg_match("/^[a-zA-Z ]*$/", $childlast)) {

                    $message.='<p>You forgot to fill up a last name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childlastarray, $childlast);

                }

            }

            foreach ($_POST['childmiddle'] as $childmiddle) {

                if (empty($childmiddle) || !preg_match("/^[a-zA-Z ]*$/", $childmiddle)) {

                    $message.='<p>You forgot to fill up a middle name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childmiddlearray, $childmiddle);

                }

            }

            foreach ($_POST['childmonth'] as $childmonth) {

                if (empty($childmonth)) {

                    $message.='<p>You forgot to fill up a month field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childmontharray, $childmonth);

                }

            }

            foreach ($_POST['childday'] as $childday) {

                if (empty($childday)) {

                    $message.='<p>You forgot to fill up a day field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childdayarray, $childday);

                }

            }

            foreach ($_POST['childyear'] as $childyear) {

                if (empty($childyear)) {

                    $message.='<p>You forgot to fill up a year field on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childyeararray, $childyear);

                }

            }

            foreach ($_POST['childsex'] as $childsex) {

                if (empty($childsex)) {

                    $message.='<p>You forgot to fill up a first name field or inputed an invalid input on child\'s info!';
                    $flag++;
                    break;

                }

                else {

                    array_push($childsexarray, $childsex);

                }

            }

        }

    }

    if ($flag == 0) {

        $idnum = $_SESSION['idnum'];

        for ($x = 0; $x < count($siblingmontharray); $x++) {

            $siblingbirthday = $siblingyeararray[$x] . "-" . $siblingmontharray[$x] . "-" . $siblingdayarray[$x];

            array_push($siblingbirthdatearray, $siblingbirthday);

        }

        for ($y = 0; $y < count($childmontharray); $y++) {

            $childbirthday = $childyeararray[$y] . "-" . $childmontharray[$y] . "-" . $childdayarray[$y];

            array_push($childbirthdatearray, $childbirthday);

        }

        $queryMem = "INSERT INTO HEALTH_AID (MEMBER_ID, APP_STATUS, DATE_APPLIED, DATE_APPROVED, EMP_ID) 

            VALUES('{$idnum}', '1', NOW(), NULL, NULL); ";

        $resultMem = mysqli_query($dbc, $queryMem);




        //This retrieves the Health Aid Table's maximum
        $queryHARI = "SELECT MAX(RECORD_ID) as RECORD_ID FROM HEALTH_AID;";
        $resultHARI = mysqli_query($dbc, $queryHARI);
        $rowHARI = mysqli_fetch_array($resultHARI);
        $_SESSION['HA_RecordID'] = $rowHARI['RECORD_ID'];

        //Insert into transaction table
        $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
            VALUES({$idnum}, '1', 'Health Aid Application', 0, NOW(),  {$_SESSION['HA_RecordID']}, NULL, '2'); ";
        $resultTnx = mysqli_query($dbc, $queryTnx);



        /* father insert */

        $fatherstatus = $_POST['fatherstatus'];

        $queryFather = "INSERT INTO FATHER (RECORD_ID, MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS) 

                VALUES ('{$_SESSION['HA_RecordID']}', '{$idnum}', '{$fatherlast}', '{$fatherfirst}', '{$fathermiddle}', CAST('{$fatherbirthdate}' AS DATE), '{$fatherstatus}');";

        $resultFather = mysqli_query($dbc, $queryFather);

        /* mother insert */

        $motherstatus = $_POST['motherstatus'];

        $queryMother = "INSERT INTO MOTHER (RECORD_ID, MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS) 

                VALUES ('{$_SESSION['HA_RecordID']}','{$idnum}', '{$motherlast}', '{$motherfirst}', '{$mothermiddle}', CAST('{$motherbirthdate}' AS DATE), '{$motherstatus}');";

        $resultMother = mysqli_query($dbc, $queryMother);

        /* spouse insert */

        $spousestatus = $_POST['spousestatus'];

        $querySpouse = "INSERT INTO SPOUSE (RECORD_ID, MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS) 

                VALUES ('{$_SESSION['HA_RecordID']}','{$idnum}', '{$spouselast}', '{$spousefirst}', '{$spousemiddle}', CAST('{$spousebirthdate}' AS DATE), '{$spousestatus}');";

        $resultSpouse = mysqli_query($dbc, $querySpouse);

        /* sibling insert */

        for ($s = 0; $s < count($siblinglastarray); $s++) {

            $siblingf = $siblingfirstarray[$s];
            $siblingl = $siblinglastarray[$s];
            $siblingm = $siblingmiddlearray[$s];
            $siblings = $siblingsexarray[$s];
            $siblingb = $siblingbirthdatearray[$s];
            $siblingst = $siblingstatusarray[$s];

            $querySibling = "INSERT INTO SIBLINGS ( MEMBER_ID, RECORD_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS, SEX)

                    VALUES ('{$idnum}','{$_SESSION['HA_RecordID']}','{$siblingl}', '{$siblingf}', '{$siblingm}', CAST('{$siblingb}' AS DATE), '{$siblingst}', '{$siblings}');";

            $resultSibling = mysqli_query($dbc, $querySibling);

            $_SESSION['SI_Sibling']++;
        }

        /* children insert */

        if ($rowCiv['CIV_STATUS'] != 1) {

            for ($c = 0; $c < count($childlastarray); $c++) {

                $childf = $childfirstarray[$c];
                $childl = $childlastarray[$c];
                $childm = $childmiddlearray[$c];
                $childb = $childbirthdatearray[$c];
                $childs = $childsexarray[$c];
                $childst = $childstatusarray[$c];

                $queryChild = "INSERT INTO CHILDREN ( MEMBER_ID, RECORD_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS, SEX)

                    VALUES ('{$idnum}','{$_SESSION['HA_RecordID']}', '{$childl}', '{$childf}', '{$childm}', CAST('{$childb}' AS DATE),'{$childst}', '{$childs}');";

                $resultChild = mysqli_query($dbc, $queryChild);

                $_SESSION['CI_Child']++;
            }

        }

        $queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
                             VALUES ({$_SESSION['idnum']}, 0, NOW(), 1, 'Health Aid Application Submitted')";

        $resultTransaction = mysqli_query($dbc, $queryTransaction);

    }

}

if ($rowForm['APP_STATUS'] == 1) { /* PENDING */

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member HA appsent.php");

}

else if ($rowForm['APP_STATUS'] == 2) { /* ACCEPTED */

    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member HA summary.php");

}

else if ($rowForm['APP_STATUS'] == 3) { /* REJECTED */

    $message .=  "Your last application for Health Aid Benefits was rejected. You can apply for another one.";

}

$page_title = 'Loans - Health Aid Application';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Member.php';

?>
<script>

    $(document).ready(function(){

        var iS = 1;
        var iC = 1;
        var sibfields = $('.siblingfields:first');
        var childfields = $('.childfields:first');

        /* adding child and sibling fields */

        $('body').on('click', '.addsibling', function() {

            iS++;

            var section = sibfields.clone().find(':input').each(function() {

                var newId = this.id + iS;

                this.id = newId;

            }).end().appendTo('#siblingform');

            return false;

        });

        $('body').on('click', '.addchild', function() {

            iC++;

            var section2 = childfields.clone().find(':input').each(function() {

                var newId2 = this.id + iC;

                this.id = newId2;

            }).end().appendTo('#childform');

            return false;

        });

        /* removing child and sibling fields */

        $('#siblingform').on('click', '.removesibling', function() {

            $this.parent().fadeOut(300, function() {

                $(this).parent().parent().empty();
                return false;

            });

            return false;

        });

        $('#childform').on('click', '.removechild', function() {

            $this.parent().fadeOut(300, function() {

                $(this).parent().parent().empty();
                return false;

            });

            return false;

        });

    });

</script>
    <div id="page-wrapper">

        <div class="container-fluid">

            <div class="row"> <!-- Title & Breadcrumb -->

                <div class="col-lg-12">
                    <h1 class="page-header">Health Aid Application Form</h1>
                </div>

            </div>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- SERVER SELF -->

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Father's Information</h3>

                        <label>
                            <span class="labelspan">Father's First Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="fatherfirst" value="<?php if (isset($_POST['fatherfirst'])) {echo $_POST['fatherfirst'];}?> ">
                        </label>

                        <label>
                            <span class="labelspan">Father's Middle Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="fathermiddle" value="<?php if (isset($_POST['fathermiddle'])) {echo $_POST['fathermiddle'];}?> ">
                        </label>

                        <label>
                            <span class="labelspan">Father's Last Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="fatherlast" value="<?php if (isset($_POST['fatherlast'])) {echo $_POST['fatherlast'];}?> ">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                            <p class="dbirthlabel"><b>Date of Birth</b></p>
                            <select name="fathermonth" class="form-control datedropdown health">
                                <option value="">Select Month</option>
                                <option value=""> ----- </option>

                                <?php
                                if(isset($_POST['fathermonth'])){
                                    for($m = 1; $m <= 12; $m++){
                                        $mon = null;
                                        switch($m){
                                            case 1:
                                                $mon = "January";
                                                break;
                                            case 2:
                                                $mon = "February";
                                                break;
                                            case 3:
                                                $mon = "March";
                                                break;
                                            case 4:
                                                $mon = "April";
                                                break;
                                            case 5:
                                                $mon = "May";
                                                break;
                                            case 6:
                                                $mon = "June";
                                                break;
                                            case 7:
                                                $mon = "July";
                                                break;
                                            case 8:
                                                $mon = "August";
                                                break;
                                            case 9:
                                                $mon = "September";
                                                break;
                                            case 10:
                                                $mon = "October";
                                                break;
                                            case 11:
                                                $mon = "November";
                                                break;
                                            case 12:
                                                $mon = "December";
                                                break;
                                        }
                                        if($m == $_POST['fathermonth']) echo "<option value='". $m ."' selected>". $mon ."</option>";
                                        else echo "<option value='". $m ."'>". $mon ."</option>";
                                    }

                                }
                                else {
                                    echo"
										<option value='1'>January</option>
										<option value='2'>February</option>
										<option value='3'>March</option>
										<option value='4'>April</option>
										<option value='5'>May</option>
										<option value='6'>June</option>
										<option value='7'>July</option>
										<option value='8'>August</option>
										<option value='9'>September</option>
										<option value='10'>October</option>
										<option value='11'>November</option>
										<option value='12'>December</option>
									";
                                }
                                ?>



                            </select>
                        </label>

                        <label>
                            <select name="fatherday" class="form-control datedropdown health">

                                <option value="">Select Day</option>
                                <option value=""> ----- </option>

                                <?php for($x = 1; $x <= 31; $x++) {
                                    if(isset($_POST['fatherday']) && $x == $_POST['fatherday']){
                                        echo "<option value='". $x ."' selected>". $x ."</option>";
                                    }
                                    else echo "<option value='". $x ."'>". $x ."</option>";
                                }
                                ?>

                            </select>
                        </label>

                        <label class="yeardrop">
                            <select name="fatheryear" class="form-control datedropdown health">

                                <option value="">Select Year</option>
                                <option value=""> ----- </option>

                                <?php for($y = date("Y"); $y >= 1900; $y--) {

                                    if(isset($_POST['fatheryear']) && $x == $_POST['fatheryear']){
                                        echo "<option value='". $y ."' selected>". $y ."</option>";
                                    }
                                    else echo "<option value='". $y ."'>". $y ."</option>";

                                }
                                ?>

                            </select>
                        </label>

                        <label>
                            <p class="dbirthlabel">Father's Status</p>
                            <select name="fatherstatus" class="form-control datedropdown health">
                                <?php
                                if(isset($_POST['fatherstatus'])){
                                    Switch ($_POST['fatherstatus']){
                                        case 1:
                                            echo "<option value='1' selected>Normal</option>";
                                            echo "<option value='0'>Deceased</option>";
                                            break;
                                        case 0:
                                            echo "<option value='1'>Normal</option>";
                                            echo "<option value='0' selected>Deceased</option>";
                                            break;
                                    }
                                }
                                else{
                                    echo "<option value='1'>Normal</option>";
                                    echo "<option value='0'>Deceased</option>";
                                }
                                ?>

                            </select>
                        </label>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Mother's Information</h3>

                        <label>
                            <span class="labelspan">Mother's First Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="motherfirst" value="<?php if (isset($_POST['motherfirst'])) {echo $_POST['motherfirst'];}?> ">
                        </label>

                        <label>
                            <span class="labelspan">Mother's Middle Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="mothermiddle" value="<?php if (isset($_POST['mothermiddle'])) {echo $_POST['mothermiddle'];}?> ">
                        </label>

                        <label>
                            <span class="labelspan">Mother's Last Name<big class="req"> *</big></span>
                            <input type="text" class="form-control memname" placeholder="Name" name="motherlast" value="<?php if (isset($_POST['motherlast'])) {echo $_POST['motherlast'];}?> ">
                        </label>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <label>
                            <p class="dbirthlabel"><b>Date of Birth</b></p>
                            <select name="mothermonth" class="form-control datedropdown health">

                                <option value="">Select Month</option>
                                <option value=""> ----- </option>
                                <?php
                                if(isset($_POST['mothermonth'])){
                                    for($m = 1; $m <= 12; $m++){
                                        $mon = null;
                                        switch($m){
                                            case 1:
                                                $mon = "January";
                                                break;
                                            case 2:
                                                $mon = "February";
                                                break;
                                            case 3:
                                                $mon = "March";
                                                break;
                                            case 4:
                                                $mon = "April";
                                                break;
                                            case 5:
                                                $mon = "May";
                                                break;
                                            case 6:
                                                $mon = "June";
                                                break;
                                            case 7:
                                                $mon = "July";
                                                break;
                                            case 8:
                                                $mon = "August";
                                                break;
                                            case 9:
                                                $mon = "September";
                                                break;
                                            case 10:
                                                $mon = "October";
                                                break;
                                            case 11:
                                                $mon = "November";
                                                break;
                                            case 12:
                                                $mon = "December";
                                                break;
                                        }
                                        if($m == $_POST['mothermonth']) echo "<option value='". $m ."' selected>". $mon ."</option>";
                                        else echo "<option value='". $m ."'>". $mon ."</option>";
                                    }

                                }
                                else {
                                    echo"
										<option value='1'>January</option>
										<option value='2'>February</option>
										<option value='3'>March</option>
										<option value='4'>April</option>
										<option value='5'>May</option>
										<option value='6'>June</option>
										<option value='7'>July</option>
										<option value='8'>August</option>
										<option value='9'>September</option>
										<option value='10'>October</option>
										<option value='11'>November</option>
										<option value='12'>December</option>
									";
                                }
                                ?>

                            </select>
                        </label>

                        <label>
                            <select name="motherday" class="form-control datedropdown health">

                                <option value="">Select Day</option>
                                <option value=""> ----- </option>

                                <?php for($x = 1; $x <= 31; $x++) {
                                    if(isset($_POST['motherday']) && $x == $_POST['motherday']){
                                        echo "<option value='". $x ."' selected>". $x ."</option>";
                                    }
                                    else echo "<option value='". $x ."'>". $x ."</option>";
                                }
                                ?>

                            </select>
                        </label>

                        <label class="yeardrop">
                            <select name="motheryear" class="form-control datedropdown health">

                                <option value="">Select Year</option>
                                <option value=""> ----- </option>

                                <?php for($y = date("Y"); $y >= 1900; $y--) {

                                    if(isset($_POST['motheryear']) && $x == $_POST['motheryear']){
                                        echo "<option value='". $y ."' selected>". $y ."</option>";
                                    }
                                    else echo "<option value='". $y ."'>". $y ."</option>";

                                }
                                ?>

                            </select>
                        </label>

                        <label>
                            <p class="dbirthlabel">Mother's Status</p>
                            <select name="motherstatus" class="form-control datedropdown health">

                                <?php
                                if(isset($_POST['motherstatus'])){
                                    Switch ($_POST['motherstatus']){
                                        case 1:
                                            echo "<option value='1' selected>Normal</option>";
                                            echo "<option value='0'>Deceased</option>";
                                            break;
                                        case 0:
                                            echo "<option value='1'>Normal</option>";
                                            echo "<option value='0' selected>Deceased</option>";
                                            break;
                                    }
                                }
                                else{
                                    echo "<option value='1'>Normal</option>";
                                    echo "<option value='0'>Deceased</option>";
                                }
                                ?>

                            </select>
                        </label>

                    </div>

                </div>

                <hr>

                <!-- If the member is married -->
                <?php if($rowCiv['CIV_STATUS'] == 2){
                    echo"

                    <div class='row'>

                        <div class='col-lg-12'>
   
                            <h3 class='healthfieldlabel'>Spouse's Information</h3>

                            <label>
                            <span class='labelspan'>Spouse's First Name<big class='req'> *</big></span>
                            <input type='text' class='form-control memname' placeholder='Name' name='spousefirst' value='"; if (isset($_POST['spousefirst'])) {echo $_POST['spousefirst'];} echo" '>
                            </label>

                            <label>
                            <span class='labelspan'>Spouse's Middle Name<big class='req'> *</big></span>
                            <input type='text' class='form-control memname' placeholder='Name' name='spousemiddle' value='"; if (isset($_POST['spousemiddle'])) {echo $_POST['spousemiddle'];} echo" '>
                            </label>

                            <label>
                            <span class='labelspan'>Spouse's Last Name<big class='req'> *</big></span>
                            <input type='text' class='form-control memname' placeholder='Name' name='spouselast' value='"; if (isset($_POST['spouselast'])) {echo $_POST['spouselast'];} echo" '>
                            </label>

                        </div>

                    </div>

                    <div class='row'>

                        <div class='col-lg-12'>

                            <label>
                            <p class='dbirthlabel'><b>Date of Birth</b></p>
                            <select name='spousemonth' class='form-control datedropdown health'>
							<option value=''>Select Month</option>
                                <option value=''> ----- </option>
					";

                    if(isset($_POST['spousemonth'])){
                        for($m = 1; $m <= 12; $m++){
                            $mon = null;
                            switch($m){
                                case 1:
                                    $mon = "January";
                                    break;
                                case 2:
                                    $mon = "February";
                                    break;
                                case 3:
                                    $mon = "March";
                                    break;
                                case 4:
                                    $mon = "April";
                                    break;
                                case 5:
                                    $mon = "May";
                                    break;
                                case 6:
                                    $mon = "June";
                                    break;
                                case 7:
                                    $mon = "July";
                                    break;
                                case 8:
                                    $mon = "August";
                                    break;
                                case 9:
                                    $mon = "September";
                                    break;
                                case 10:
                                    $mon = "October";
                                    break;
                                case 11:
                                    $mon = "November";
                                    break;
                                case 12:
                                    $mon = "December";
                                    break;
                            }
                            if($m == $_POST['spousemonth']) echo "<option value='". $m ."' selected>". $mon ."</option>";
                            else echo "<option value='". $m ."'>". $mon ."</option>";
                        }

                    }
                    else {
                        echo"
										<option value='1'>January</option>
										<option value='2'>February</option>
										<option value='3'>March</option>
										<option value='4'>April</option>
										<option value='5'>May</option>
										<option value='6'>June</option>
										<option value='7'>July</option>
										<option value='8'>August</option>
										<option value='9'>September</option>
										<option value='10'>October</option>
										<option value='11'>November</option>
										<option value='12'>December</option>
									";
                    }
                    echo "
                            </select>
                            </label>

                            <label>
                            <select name='spouseday' class='form-control datedropdown health'>

                                <option value=''>Select Day</option>
                                <option value=''> ----- </option>
                ";?>
                    <?php for($x = 1; $x <= 31; $x++) {
                        if(isset($_POST['spouseday']) && $x == $_POST['spouseday']){
                            echo "<option value='". $x ."' selected>". $x ."</option>";
                        }
                        else echo "<option value='". $x ."'>". $x ."</option>";
                    }
                    ?>
                    <?php echo"
                            </select>
                            </label>

                            <label class='yeardrop'>
                            <select name='spouseyear' class='form-control datedropdown health'>

                                <option value=''>Select Year</option>
                                <option value=''> ----- </option>
                ";?>
                    <?php for($y = date("Y"); $y >= 1900; $y--) {

                        if(isset($_POST['spouseyear']) && $x == $_POST['spouseyear']){
                            echo "<option value='". $y ."' selected>". $y ."</option>";
                        }
                        else echo "<option value='". $y ."'>". $y ."</option>";

                    }
                    ?>
                    <?php echo"
                            </select>
                            </label>

                            <label>
                            <p class='dbirthlabel'>Spouse's Status</p>
                            <select name='spousestatus' class='form-control datedropdown health'>
				";

                    if(isset($_POST['spousestatus'])){
                        Switch ($_POST['spousestatus']){
                            case 1:
                                echo "<option value='1' selected>Normal</option>";
                                echo "<option value='0'>Deceased</option>";
                                break;
                            case 0:
                                echo "<option value='1'>Normal</option>";
                                echo "<option value='0' selected>Deceased</option>";
                                break;
                        }
                    }
                    else{
                        echo "<option value='1'>Normal</option>";
                        echo "<option value='0'>Deceased</option>";
                    }

                    echo"
                            </select>
                            </label>

                        </div>

                    </div>
                    ";
                }?>
                <hr>

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Children's Information</h3>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <font color="red">Note: Please check the checkbox below if you do not have children. Excluding deceased children.</font><p>

                        <p>

                            <input type="checkbox" name="hasChild" value="1"> I don't have children<p>

                    </div>

                </div>

                <div id="childform">
                    <div class="childfields">

                        <div class="row">

                            <div class="col-lg-12">

                                <label>
                                    <span class="labelspan">Children's First Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="childfirst[]" value="<?php if (isset($_POST['childfirst[]'])) {echo $_POST['childfirst[]'];}?> ">
                                </label>

                                <label>
                                    <span class="labelspan">Children's Last Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="childlast[]" value="<?php if (isset($_POST['childlast[]'])) {echo $_POST['childlast[]'];}?> ">
                                </label>

                                <label>
                                    <span class="labelspan">Children's Middle Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="childmiddle[]" value="<?php if (isset($_POST['childmiddle[]'])) {echo $_POST['childmiddle[]'];}?> ">
                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <label>
                                    <p class="dbirthlabel"><b>Date of Birth</b></p>
                                    <select name="childmonth[]" class="form-control datedropdown health">

                                        <option value="">Select Month</option>
                                        <option value=""> ----- </option>
                                        <?php
                                        if(isset($_POST['childmonth'])){
                                            for($m = 1; $m <= 12; $m++){
                                                $mon = null;
                                                switch($m){
                                                    case 1:
                                                        $mon = "January";
                                                        break;
                                                    case 2:
                                                        $mon = "February";
                                                        break;
                                                    case 3:
                                                        $mon = "March";
                                                        break;
                                                    case 4:
                                                        $mon = "April";
                                                        break;
                                                    case 5:
                                                        $mon = "May";
                                                        break;
                                                    case 6:
                                                        $mon = "June";
                                                        break;
                                                    case 7:
                                                        $mon = "July";
                                                        break;
                                                    case 8:
                                                        $mon = "August";
                                                        break;
                                                    case 9:
                                                        $mon = "September";
                                                        break;
                                                    case 10:
                                                        $mon = "October";
                                                        break;
                                                    case 11:
                                                        $mon = "November";
                                                        break;
                                                    case 12:
                                                        $mon = "December";
                                                        break;
                                                }
                                                if($m == $_POST['childmonth']) echo "<option value='". $m ."' selected>". $mon ."</option>";
                                                else echo "<option value='". $m ."'>". $mon ."</option>";
                                            }

                                        }
                                        else {
                                            echo"
										<option value='1'>January</option>
										<option value='2'>February</option>
										<option value='3'>March</option>
										<option value='4'>April</option>
										<option value='5'>May</option>
										<option value='6'>June</option>
										<option value='7'>July</option>
										<option value='8'>August</option>
										<option value='9'>September</option>
										<option value='10'>October</option>
										<option value='11'>November</option>
										<option value='12'>December</option>
									";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label>
                                    <select name="childday[]" class="form-control datedropdown health">

                                        <option value="">Select Day</option>
                                        <option value=""> ----- </option>

                                        <?php for($x = 1; $x <= 31; $x++) {
                                            if(isset($_POST['childday']) && $x == $_POST['childday']){
                                                echo "<option value='". $x ."' selected>". $x ."</option>";
                                            }
                                            else echo "<option value='". $x ."'>". $x ."</option>";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <select name="childyear[]" class="form-control datedropdown health">

                                        <option value="">Select Year</option>
                                        <option value=""> ----- </option>

                                        <?php for($y = date("Y"); $y >= 1900; $y--) {

                                            if(isset($_POST['childyear']) && $x == $_POST['childyear']){
                                                echo "<option value='". $y ."' selected>". $y ."</option>";
                                            }
                                            else echo "<option value='". $y ."'>". $y ."</option>";

                                        }
                                        ?>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <p class="dbirthlabel">Children's Sex</p>
                                    <select name="childsex[]" class="form-control datedropdown health">

                                        <option value="1">Male</option>
                                        <option value="0">Female</option>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <p class="dbirthlabel">Children's Status</p>
                                    <select name="childstatus[]" class="form-control datedropdown health">

                                        <option value="1" selected>Normal</option>
                                        <option value="0">Deceased</option>

                                    </select>
                                </label>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <a href="#" class="btn btn-default addchild" role="button">Add Field</a>
                        <a class="btn btn-default removechild" role="button">Remove Previously Added Fields</a>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-lg-12">

                        <h3 class="healthfieldlabel">Siblings's Information</h3>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <font color="red">Note: Please check the checkbox below if you do not have children. Excluding deceased children.</font><p>

                        <p>

                            <input type="checkbox" name="hasSibling" value="1"> I don't have siblings<p>

                    </div>

                </div>

                <div id="siblingform">
                    <div class="siblingfields">

                        <div class="row">

                            <div class="col-lg-12">

                                <label>
                                    <span class="labelspan">Sibling's First Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="siblingfirst[]" value="<?php if (isset($_POST['siblingfirst[]'])) {echo $_POST['siblingfirst[]'];}?> ">
                                </label>

                                <label>
                                    <span class="labelspan">Sibling's Last Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="siblinglast[]" value="<?php if (isset($_POST['siblinglast[]'])) {echo $_POST['siblinglast[]'];}?> ">
                                </label>

                                <label>
                                    <span class="labelspan">Sibling's Middle Name<big class="req"> *</big></span>
                                    <input type="text" class="form-control memname" placeholder="Name" name="siblingmiddle[]" value="<?php if (isset($_POST['siblingmiddle[]'])) {echo $_POST['siblingmiddle[]'];}?> ">
                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <label>
                                    <p class="dbirthlabel"><b>Date of Birth</b></p>
                                    <select name="siblingmonth[]" class="form-control datedropdown health">

                                        <option value="">Select Month</option>
                                        <option value=""> ----- </option>
                                        <?php
                                        if(isset($_POST['siblingmonth'])){
                                            for($m = 1; $m <= 12; $m++){
                                                $mon = null;
                                                switch($m){
                                                    case 1:
                                                        $mon = "January";
                                                        break;
                                                    case 2:
                                                        $mon = "February";
                                                        break;
                                                    case 3:
                                                        $mon = "March";
                                                        break;
                                                    case 4:
                                                        $mon = "April";
                                                        break;
                                                    case 5:
                                                        $mon = "May";
                                                        break;
                                                    case 6:
                                                        $mon = "June";
                                                        break;
                                                    case 7:
                                                        $mon = "July";
                                                        break;
                                                    case 8:
                                                        $mon = "August";
                                                        break;
                                                    case 9:
                                                        $mon = "September";
                                                        break;
                                                    case 10:
                                                        $mon = "October";
                                                        break;
                                                    case 11:
                                                        $mon = "November";
                                                        break;
                                                    case 12:
                                                        $mon = "December";
                                                        break;
                                                }
                                                if($m == $_POST['siblingmonth']) echo "<option value='". $m ."' selected>". $mon ."</option>";
                                                else echo "<option value='". $m ."'>". $mon ."</option>";
                                            }

                                        }
                                        else {
                                            echo"
										<option value='1'>January</option>
										<option value='2'>February</option>
										<option value='3'>March</option>
										<option value='4'>April</option>
										<option value='5'>May</option>
										<option value='6'>June</option>
										<option value='7'>July</option>
										<option value='8'>August</option>
										<option value='9'>September</option>
										<option value='10'>October</option>
										<option value='11'>November</option>
										<option value='12'>December</option>
									";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label>
                                    <select name="siblingday[]" class="form-control datedropdown health">

                                        <option value="">Select Day</option>
                                        <option value=""> ----- </option>

                                        <?php for($x = 1; $x <= 31; $x++) {
                                            if(isset($_POST['siblingday']) && $x == $_POST['siblingday']){
                                                echo "<option value='". $x ."' selected>". $x ."</option>";
                                            }
                                            else echo "<option value='". $x ."'>". $x ."</option>";
                                        }
                                        ?>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <select name="siblingyear[]" class="form-control datedropdown health">

                                        <option value="">Select Year</option>
                                        <option value=""> ----- </option>

                                        <?php for($y = date("Y"); $y >= 1900; $y--) {

                                            if(isset($_POST['siblingyear']) && $x == $_POST['siblingyear']){
                                                echo "<option value='". $y ."' selected>". $y ."</option>";
                                            }
                                            else echo "<option value='". $y ."'>". $y ."</option>";

                                        }
                                        ?>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <p class="dbirthlabel">Sibling's Sex</p>
                                    <select name="siblingsex[]" class="form-control datedropdown health">

                                        <option value="1">Male</option>
                                        <option value="0">Female</option>

                                    </select>
                                </label>

                                <label class="yeardrop">
                                    <p class="dbirthlabel">Sibling's Status</p>
                                    <select name="siblingstatus[]" class="form-control datedropdown health">

                                        <option value="1" selected>Normal</option>
                                        <option value="0">Deceased</option>

                                    </select>
                                </label>

                            </div>

                        </div>

                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <a class="btn btn-default addsibling" role="button">Add Field</a>
                        <a class="btn btn-default removesibling" role="button">Remove Previously Added Fields</a>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <hr>

                        <input type="submit" name="submit" class="btn btn-success" role="button" value="Apply"></a>
                        <p> &nbsp;

                    </div>

                </div>

            </form>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include 'GLOBAL_TEMPLATE_Footer.php' ?>
