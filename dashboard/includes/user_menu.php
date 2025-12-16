<?php
include('../includes/konak.php');
date_default_timezone_set('asia/jakarta');
$now = date("Y-m-d ");
$db = getDbInstance();
$numuser = $db->getValue("user", "count(*)");
$db->where("status", "reseller");

$numTotalreseller = $db->getValue("user", "count(*)");
$name = $_SESSION['name'];


$db = getDbInstance();
$select = array('id', 'credit', 'frp', 'v6', 'activev6', 'jumlahv6');
$db->where('username', $name);
$rows = $db->arraybuilder()->paginate('user', 1, $select);
$row = $rows[0];
$credit = $row['credit'];
$paketfrp = $row['frp'];
$paketv6 = $row['v6'];
$tglv6 = $row['activev6'];
$jumlahTotal = $row['jumlahv6'];
$id = $row['id'];


$query = mysqli_query($koneksi, "SELECT * FROM data WHERE iduser='$id' AND tgl >= '$tglv6' ");
$jumlahv66 = mysqli_num_rows($query);



//$query = $this->db->get();

///if ($query->num_rows() > 0)
//{
//  $row = $query->row(); 
//  return $row->campaign_id;
//}
$where = "status='done' AND id ='$id' AND tgl >= '$now' AND serviceid='1' ";
$db = getDbInstance();
$db->where($where);
$jumlahflashQcomtoday = $db->getValue("data", "count(*)");


$where = "status='done' AND id ='$id' AND tgl >= '$now' AND serviceid='6' ";
$db = getDbInstance();
$db->where($where);
$jumlahflashmtktoday = $db->getValue("data", "count(*)");

$jumlahflashtoday = $jumlahflashQcomtoday + $jumlahflashmtktoday;



$where = "serviceid='2' AND id ='$id' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahfrptoday = $db->getValue("data", "count(*)");

$where = "serviceid='4' AND id ='$id' AND status='done' AND tgl >= '$now' ";
$db = getDbInstance();
$db->where($where);
$jumlahFDLtoday = $db->getValue("data", "count(*)");


$where = "serviceid='9' AND id ='$id' AND tgl >= '$tglv6' ";
$db = getDbInstance();
$db->where($where);
$jumlahv6 = $db->getValue("data", "count(*)");



?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">AH- Auth User Panel , Welcome <?php echo $name; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->






    <div class="col-lg-4 col-md-7">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $jumlahflashtoday . ' Done Flash'; ?></div>
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
                        <div class="huge"><?php echo $jumlahfrptoday . ' Done FRP'; ?></div>
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
                        <div class="huge"><?php echo $jumlahFDLtoday . ' Done FDL'; ?></div>
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
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> Paket FRP <?php if ($paketfrp == 1) {
                            echo 'YES';
                        } else {
                            echo 'NO';
                        } ?></div>

                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">


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
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"> Paket MTK v6 <?php if ($paketv6 == 1) {
                            echo 'YES';
                        } else {
                            echo 'NO';
                        } ?></div>

                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    Active <?php echo $tglv6; ?><br>
                    Num <?php echo $jumlahTotal; ?><br>
                    TOTAL DO MTK v6 <?php echo $jumlahv66; ?><br>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>



    <div class="col-lg-4 col-md-7">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">CREDIT <?php echo $credit; ?></div>

                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">


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