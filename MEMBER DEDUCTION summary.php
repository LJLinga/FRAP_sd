<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    $choice = 1;
    if(isset($_POST['Period'])){
        
            $choice = $_POST['Period'];
        
    }


    $query = "SELECT ha.Record_ID as 'has_HA', f.Amount as 'FFee'
              from member m
              left join (SELECT * from health_aid where app_status = 2) ha
              on m.member_id = ha.member_id
              left join (SELECT member_id,sum(PER_PAYMENT) as 'Amount' 
                         from Loans
                         where member_id = {$_SESSION['idnum']} and loan_status = 2 ) f
              on f.member_id = m.member_id
              where m.member_id = {$_SESSION['idnum']}";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $page_title = 'Loans - Audit Trail';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>
        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Deduction Summary</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-4 col-1">

                        <div class="panel panel-default" align="center">

                            <div class="panel-heading">

                                <b>FA Membership Fee</b>

                            </div>

                            <div class="panel-body">
                                <?php if($choice == 1)
                                        echo "₱ 100.00";
                                      else
                                        echo "₱ 0.00";?>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-2">

                        <div class="panel panel-default" align="center">

                            <div class="panel-heading">

                                <b>Health Aid Program Fee</b>

                            </div>

                            <div class="panel-body">
                                <?php


                                if(!empty($ans['has_HA']) && $choice == 1)
                                    echo "₱ 100.00";
                                else
                                    echo "₱ 0.00";
                                ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4 col-3">

                        <div class="panel panel-default" align="center">

                            <div class="panel-heading">

                                <b>FALP Loan</b>

                            </div>

                            <div class="panel-body">

                                ₱ <?php 

                                if($choice==2)
                                echo sprintf("%.2f",(float)$ans['FFee']);
                            else
                                echo sprintf("%.2f",((float)$ans['FFee'])*2);?>

                            </div>

                        </div>

                    </div>

<!--                    <div class="col-lg-3 col-4">-->
<!---->
<!--                        <div class="panel panel-green" align="center">-->
<!---->
<!--                            <div class="panel-heading">-->
<!---->
<!--                                <b>Bank Loan</b>-->
<!---->
<!--                            </div>-->
<!---->
<!--                            <div class="panel-body">-->
<!---->
<!--                                ₱ --><?php //
//                                if($choice==2)
//                                echo sprintf("%.2f",(float)$ans['BFee']);
//                                 else
//                                    echo sprintf("%.2f",((float)$ans['BFee'])*2);?>
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!---->
<!--                    </div>-->

                </div>

                <div class="row">

                    <div class="col-lg-3">


                    </div>

                    <div class="col-lg-6">

                        <div class="row">

                            <form action="MEMBER DEDUCTION summary.php" method="POST">

                                <div class="col-lg-3">

                                    <label>View Summary As</label>

                                    <select name = "Period" class="form-control" style="margin-bottom: 20px;"> 

                                        <option value = 1 <?php if($choice == 1) echo "selected"?>>Per Month</option>
                                        <option value = 2 <?php if($choice == 2) echo "selected"?>>Per Pay Period</option>

                                    </select>

                                </div>

                                <input type="submit" class="btn btn-info" value="View As" name="viewas" style="margin-top: 25px;">

                            </form>

                        </div>

                        <table class="table table-bordered" style="background-color: white;">
                            
                        <thread>

                            <tr>

                            <td align="center"><b>Payable</b></td>
                            <td align="center" width="50%"><b>Amount</b></td>

                            </tr>

                        </thread>

                         <tbody>

                            <tr>

                            <td>FA Membership Fee</td>
                            <td><?php $mf = 0;
                                if($choice == 1){
                                        echo "₱ 100.00";
                                        $mf = 100;
                                    }
                                      else
                                        echo "₱ 0.00";?></td>

                            </tr>

                            <tr>

                            <td>Health Aid Program Fee</td>
                            <td><?php

                                $ha = 0;
                                if(!empty($ans['has_HA']) && $choice == 1){
                                    echo "₱ 100.00";
                                    $ha = 100;
                                }
                                else{
                                    echo "₱ 0.00";
                                }
                                ?></td>

                            </tr>

                            <tr>

                            <td>FALP Loan</td>
                            <td id = "FALP">₱ <?php 
                                if ($choice==2){
                                    $ff = (float)$ans['FFee'];
                                echo sprintf("%.2f",(float)$ans['FFee']);
                            }
                                 else{
                                    $ff = (float)$ans['FFee']*2;
                                    echo sprintf("%.2f",((float)$ans['FFee'])*2);}?></td>
                            

                            </tr>

<!--                            <tr>-->
<!---->
<!--                            <td>Bank Loan</td>-->
<!--                            <td id = "Bank">₱ --><?php //
//                                if ($choice==2){
//                                echo sprintf("%.2f",(float)$ans['BFee']);
//                                $bf = (float)$ans['BFee'];
//                            }
//                                 else{
//                                    echo sprintf("%.2f",((float)$ans['BFee'])*2);
//
//                                    $bf = (float)$ans['BFee']*2;
//                                }
//                                    ?><!--</td>-->
<!---->
<!--                                -->
<!---->
<!--                            </tr>-->

                            <tr>

                            <td><b>TOTAL</td>
                            <td><b>₱ <?php echo sprintf("%.2f",$mf+$ha+$ff);?></td>

                            </tr>

                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" align="center">
                         <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
