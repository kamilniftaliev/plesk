<?php
include "/include/header.php";
session_name('RESELLER_SESSION');
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';


require_once BASE_PATH.'/lib/Users/Users.php';
$users = new Users();

$admin_user_id =  $_SESSION['admin_id'];
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


$pagelimit = 100;


$page = filter_input(INPUT_GET, 'page');
if (!$page) {
	$page = 1;
}
if (!$search_string) {
	$search_string = '';
}

if (!$filter_col) {
	$filter_col = 'id';
}


$db = getDbInstance();
$select = array('id', 'jumlah', 'created_at' ,'email','ispay');
function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}

if ($search_string)
{
    $db->where('name', $search_string)->where('ispay', 0);
} else {
    
   $db->where('resellerid', $admin_user_id)->where('ispay', 0);  
    
}

 $db->orderBy("id", "desc");
$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);
$total_pages = $db->totalPages;


if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'user') {
    require_once 'includes/user_header.php';
}
if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'admin') {
    require_once 'includes/admin_header.php';
}
if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] == 'reseller') {
    require_once 'includes/reseller_header.php';
}	



?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Refill Credit History</h1>
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
    

    
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="10%">User/email</th>
                <th width="15%">Amount</th>
                 <th width="15%">Paid Off</th>
				<th width="15%">Time</th>
	
               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): 
            // if ($row['sid'] === $serverid) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>

                
                
                <td><?php echo $row['jumlah']; ?></td>
                <td><?php if ($row['ispay']==0 ){echo "UNPAID";} if($row['ispay']==1 ){echo "PAID";}  ?></td>
                

                <td>
                    <?php echo $row['created_at']; ?>
                    
                </td>

				
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
                echo '<li' . $li_class . '><a href="job.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>
