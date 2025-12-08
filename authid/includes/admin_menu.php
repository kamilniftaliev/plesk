<?php

date_default_timezone_set('asia/jakarta');
$now = date("Y-m-d ");
$db = getDbInstance();
$numuser = $db->getValue ("user", "count(*)");
$db->where("status", "authid");

$numTotalauthid = $db->getValue ("user", "count(*)");
$name = $_SESSION['name'];


$db = getDbInstance();
$db->where("username", $name);
$row = $db->get('user');

//$data = mysqli_query($koneksi, "SELECT * FROM data WHERE DATE(tgl) = CURDATE() ");
$where = "status='done' AND tgl >= '$now' AND  serviceid='6'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashtoday = $db->getValue ("data", "count(*)");

$where = "status='done' AND tgl >= '$now' AND  serviceid='1'  ";
$db = getDbInstance();
$db->where($where);
$jumlahflashmtk = $db->getValue ("data", "count(*)")  ;
$jumlahflashtoday += $jumlahflashmtk;

$where = "serviceid='2' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahfrptoday = $db->getValue ("data", "count(*)");

$where = "serviceid='4' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahFDLtoday = $db->getValue ("data", "count(*)");

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
                            <div class="huge"><?php echo $numTotalauthid . ' Acc'; ?></div>
                            <div>Total Authid Accounts</div>
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
		
		<div class="col-lg-4 col-md-7">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-key fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $numuser . ' Acc'; ?></div>
                            <div>Total User Account</div>
                        </div>
                    </div>
                </div>
                <a href="add_admin.php">
                    <div class="panel-footer">
                        <span class="pull-left">Add a new authid</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
		
        <div class="col-lg-4 col-md-7">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo 'Unlimited';?></div>
                            <div>Your credit balances</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
		
		   <div class="col-lg-4 col-md-7">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $jumlahflashtoday. ' Done Flash';?></div>
                            <div><?php echo date("Y-m-d"); ?> </div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
			   <div class="col-lg-4 col-md-7">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $jumlahfrptoday . ' Done FRP';?></div>
                            <div><?php echo date("Y-m-d"); ?> </div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
			   <div class="col-lg-4 col-md-7">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo $jumlahFDLtoday . ' Done FDL';?></div>
                             <div><?php echo date("Y-m-d"); ?> </div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
        
        </div>
        <div class="col-lg-3 col-md-6">
            
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">


            <!-- /.panel -->
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">

            <!-- /.panel .chat-panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->