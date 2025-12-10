<?php

date_default_timezone_set('asia/jakarta');
$now = date("Y-m-d ");
$db = getDbInstance();
$numuser = $db->getValue("user", "count(*)");
$db->where("status", "reseller");

$numTotalreseller = $db->getValue("user", "count(*)");
$name = $_SESSION['name'];


$db = getDbInstance();
$db->where("username", $name);
$row = $db->get('user');

//$data = mysqli_query($koneksi, "SELECT * FROM data WHERE DATE(tgl) = CURDATE() ");
$where = "status='done' AND tgl >= '$now' AND  serviceid='6'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashtoday = $db->getValue("data", "count(*)");

$where = "status='done' AND tgl >= '$now' AND  serviceid='1'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashmtk = $db->getValue("data", "count(*)");
$jumlahflashtoday += $jumlahflashmtk;

$where = "serviceid='2' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahfrptoday = $db->getValue("data", "count(*)");

$where = "serviceid='4' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahFDLtoday = $db->getValue("data", "count(*)");

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">AH - Admin Panel , Welcome <?php echo $name; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-4 col-md-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $numTotalreseller . ' Acc'; ?></div>
                            <div>Total Reseller Accounts</div>
                        </div>
                    </div>
                </div>
                <a href="customers.php">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>