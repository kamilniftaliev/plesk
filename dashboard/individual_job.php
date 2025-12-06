<?php
include "/include/header.php";
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';


require_once BASE_PATH.'/lib/Users/Users.php';
$users = new Users();

$admin_user_id =  $_SESSION['admin_id'];
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$serviceid = filter_input(INPUT_GET, 'serviceid');
$tanggal = filter_input(INPUT_GET, 'tanggal');
$paid = filter_input(INPUT_GET, 'paid');


$pagelimit = 100;


$page = filter_input(INPUT_GET, 'page');
if (!$page) {
	$page = 1;
}
if (!$search_string) {
	$search_string = $admin_user_id;
}
if (!$serviceid) {
	$serviceid = '';
}

if (!$filter_col) {
	$filter_col = 'id';
}


$db = getDbInstance();
$select = array('id','name', 'iduser', 'configblob' ,'serviceid', 'tgl','status','serverid','cost');


if ($search_string)
{   $where3 = "";
    if($paid == 0) { $where3 = "cost=0"; }else { $where3 = "cost!=0";}
    
    

    $where2 = "tgl >='" .$tanggal ."'";
    $db->where('iduser', $admin_user_id)->where('serviceid', $serviceid)->where($where3)->where($where2);
    
}



$db->orderBy("id", "desc");
$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('data', $page, $select);
$total_pages = $db->totalPages;




if ($_SESSION['admin_type'] == 'user') {
    require_once 'includes/user_header.php';
}
if ($_SESSION['admin_type'] == 'admin') {
    require_once 'includes/admin_header.php';
}
if ($_SESSION['admin_type'] == 'reseller') {
    require_once 'includes/reseller_header.php';
}	



?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Job Auth History</h1>
        </div>
        
    </div>
    <?php include BASE_PATH.'/includes/flash_messages.php'; ?>

    <?php
    if (isset($del_stat) && $del_stat == 1)
    {
        echo '<div class="alert alert-info">Successfully deleted</div>';
    }
    ?>
    
    <!-- Filters -->
    
  <!-- Filters -->
   
    <!-- Table -->
    
        <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Search</label>
    
            
            <select name="serviceid" id="serviceid">
                    <option value="1">flash</option>
                    <option value="6">mtk5</option>
                    <option value="2">frp</option>
                    <option value="4">fdl</option>
                   
            </select>
            

            
            
             <input type="date" id="tanggal"  name="tanggal">
            <select name="paid" id="paid">
                    <option value="1">paid</option>
                    <option value="0">free</option>
                
                   
            </select>
            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>
    
    
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="8%">id user</th>
                <th width="20%">configblob</th>
                <th width="8%">Auth Type</th>
                <th width="7%">Server Id</th>
                <th width="8%">Response</th>
                <th width="8%">cost</th>
				<th width="20%">Time</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): 
            // if ($row['sid'] === $serverid) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['iduser']); ?></td>
                <td><?php 
                  $blobkey = $row['configblob'];
               echo substr($blobkey, 0, 10);
               
                if ($row['serviceid'] == "1") { ?></td>
                <td><?php echo "EDL"; } ?></td>
                <?php 
               if ($row['serviceid'] == "2") { ?></td>
                <td><?php echo "FRP"; } ?></td>
                <?php 
                if ($row['serviceid'] == "4") { ?></td>
                <td><?php echo "MTK"; } ?></td>
                
                
                <td><?php echo "Server ". $row['serverid']; ?></td>
                
                <td>Hidden</td>
                 <td><?php echo $row['cost']; ?></td>
                <?php 
                $dateTime = new DateTime($row['tgl']);
$timestamp = $dateTime->getTimestamp();
$dhakaTimeZone = new DateTimeZone('Asia/jakarta');
$dateTimeDhaka = new DateTime();
$dateTimeDhaka->setTimestamp($timestamp);
$dateTimeDhaka->setTimezone($dhakaTimeZone);
$convertedDate = date('F j, Y h:i A', $timestamp);
                
                ?>
                <td>
                    <?php echo $dateTimeDhaka->format('F j, Y h:i A'); ?>
                    
                </td>
				<?php 
				 if (htmlspecialchars($row['status']) == 'done')
				 {
					 ?>
				<td style="background-color:green;color:white;"> <?php  echo 'done'; ?> </td>
				 <?php 
				 }
			    else
				{
					?>
					<td style="background-color:red;color:white;"><?php  echo htmlspecialchars($row['status']); ?> </td>
	            <?php 
				}
            // }
				?>
				
            </tr>
            <!-- Delete Confirmation Modal -->
            
            <!-- //Delete Confirmation Modal -->
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- //Table -->

    <!-- Pagination -->
    <div class="text-center">
        <?php
        if (!empty($_GET)) {
            // We must unset $_GET[page] if previously built by http_build_query function
            unset($_GET['page']);
            // To keep the query sting parameters intact while navigating to next/prev page,
            $http_query = "?" . http_build_query($_GET);
        } else {
            $http_query = "?";
        }
        // Show pagination links
        if ($total_pages > 1) {
            echo '<ul class="pagination text-center">';
            for ($i = 1; $i <= $total_pages; $i++) {
                ($page == $i) ? $li_class = ' class="active"' : $li_class = '';
                echo '<li' . $li_class . '><a href="individual_job.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>
