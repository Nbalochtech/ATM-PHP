<?php include("head.php");
session_start();

if (isset($_SESSION["ac_name"])) {
    include("connect.php");
    $err = array('deposited'=>'','reciever' => '','success'=>'');
if (isset($_POST['trasfer'])) {

    $old_date = $ar_account = $fund = $curbalance = $Amount = $rc_balance = $rc_accountNo='';
    if (empty($_POST['rc_account'])) {
        $err['reciever'] = "Must be Enter Reciever Account No# ";
    }else {
        $pattern = '/^[0-9]{4}+$/';
        if (preg_match($pattern, $_POST['rc_account'])) {
          $reciever = $_POST['rc_account'];
          $reciever_acc = select_trans($con,$reciever);
            if($reciever_acc) {
                $rc_balance = $reciever_acc['currrent_balance'];
                $rc_accountNo = $reciever_acc['account_no'];
            }else {
                $err['reciever'] = "Incorrect Account No#";
            }
        } 
        else{
          $err['reciever'] = "Must be Enter 4 Numerical digit only";
        }
    }
    if (empty($_POST['amount'])) {
      $err['deposited'] = "Must be Enter amount";
    }
    else {
    if (($_POST['amount']>=500) & ($_POST['amount']<=25000)) {
        if ($_POST['amount']%500 == 0) {
    //Validation is ok
        
        $old_date = select_trans($con,$_SESSION["ac_no"]);
            if($old_date) {
                $account_no = $old_date['account_no'];
                $fund = $_POST['amount']; 
                $curbalance = $old_date['current_balance'];
                
                if ($fund <= $curbalance) {
                    $sanderAm = $curbalance-$fund;
                    $date = date('Y-m-d H:i:s');
                    // //Sender amount and acount query                                          
                    // $insert_acc = "INSERT INTO transaction_tbl value('null',0,{0},{$sanderAm},'{$date}',{$account_no});";
                    // $insert_acc .= "INSERT INTO fund_transfer value('null',{$account_no},{$reciever},{$fund},'{$date}');";
                    // //Reciever amount and account query
                    
                    // $tran = mysqli_multi_query($con,$insert_acc);
                    if($tran) {
                    $err['success'] = "<div class='alert alert-success text-center'>Transection has been Successful.</div>";
                        mysqli_close($con);
                    }
                }else {
                    $err['success'] = "<div class='alert alert-danger text-center'>Sorry! Your Current Balance enough for this Amount.</div>";
                }
                
            }
        }else {
        $err['deposited'] = "Must be Amount like(500, 1000, 1500 or 25,000)";
        }
    }
    else {
        $err['deposited'] = "Deposite Amount in limit 500 to 25000 only.";
    }
    }


}
?>
<div class="container">

    <div class="row " >
        <div class="col-md-3 "></div>
        
        <div class="col-md-6 mt-5">
        <div class="card">
            <div class="card-header text-center font-weight-bold"><h3>Fund Transfer</h3></div>
            <div class="card-body">
                
            <?php echo $err['success'];?>
            <form action="" method="post" class="p-2">
            <div class="form-group ">
                    <label for="rc">Reciever Account No#</label>
                    <input type="text" name="rc_account" class="form-control pwd"   placeholder="Enter Reciever Account No#" id="rc" >
                    <div class="text-danger"><?php echo $err['reciever'];?></div>
                </div>
                <div class="form-group ">
                    <label for="wda">Transfer Amount</label>
                    <input type="text" name="amount" class="form-control pwd"   placeholder="Enter Transfer Amount" id="wda" >
                    <div class="text-danger"><?php echo $err['deposited'];?></div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="trasfer" class="btn btn-primary btn-block "><i class="far fa-share-square"></i> Transfer Amount</button>
                </div>
            </form>


            </div>
            <div class="card-footer">
                <div class="clearfix">
                    <a href="Menu.php" class="float-left btn btn-success" role="button"><i class="fas fa-arrow-circle-left"></i> Back Menu</a>
                    <a href="server.php?logout" class="float-right btn btn-danger" role="button"><i class="fas fa-sign-out-alt"></i>Exit Application</a>

                </div>
            </div>
        </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?php
}else{
    header("Location:index.php");
}
include("footer.php");?>