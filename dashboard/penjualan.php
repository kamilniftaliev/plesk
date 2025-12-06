<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';


require_once BASE_PATH.'/lib/Users/Users.php';
$users = new Users();


if ($_SESSION['admin_type'] !== 'admin')
{
   
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit('401 Unauthorized');
}




$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


$pagelimit = 50;


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
$select = array('id', 'jumlah', 'email', 'resellername','created_at','ispay');
function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}

if ($search_string)
{
    
    
    $db->where('resellername', '%' . $search_string . '%', 'like')->where('ispay','0');
} else {
    
   $db->where('ispay','0') ;
}

$db->orderBy("id", "desc");
$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);
$total_pages = $db->totalPages;



include BASE_PATH.'/includes/admin_header.php';
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">AZEGSM Reseller Sold Records</h1>
        </div>
        
    </div>
    <?php include BASE_PATH.'/includes/flash_messages.php'; 
    ?>

    <?php
    if (isset($del_stat) && $del_stat == 1)
    {
        echo '<div class="alert alert-info">Successfully deleted</div>';
        
        
        
    } 
    
    
    
    ?>
    
    <!-- Filters -->
    
  
    <!-- //Filters -->

    <!-- Table -->
    
    
       <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Search</label>
            <input type="text" class="form-control" id="input_search" name="search_string" value="<?php echo htmlspecialchars($search_string, ENT_QUOTES, 'UTF-8'); ?>">
           
            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>
    
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="20%">Name</th>
                <th width="10%">Credit Amount</th>
				<th width="20%">reseller name</th>
                <th width="20%">Date</th>
                <th width="10%">Paid Off </th>
                <th width="25%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalcredit = 0;
            foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php 
                
                $totalcredit += $row['jumlah'];
                
                echo htmlspecialchars($row['jumlah']); ?></td>
                <td>
                    <?php echo $row['resellername']; ?>
                    
                </td>
				<td><?php echo htmlspecialchars($row['created_at']); ?></td>
				<td><?php echo htmlspecialchars($row['ispay']); ?></td>
				<td>
				     <a href="edit_penjualan.php?serverid=<?php echo $row['id']; ?>&operation=edit" class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
		 	
			        <a href="#" class="btn btn-danger delete_btn" data-toggle="modal" data-target="#confirm-delete-<?php echo $row['id']; ?>"><i class="glyphicon glyphicon-trash"></i></a>
           </td>
            </td>
            
            </tr>
            <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                <div class="modal-dialog">
                    <form action="delete_history_credit.php" method="POST">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="del_id" id="del_id" value="<?php echo $row['id']; ?>">
                                <p>Are you sure you want to delete <?php echo $row['id'];?> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default pull-left">Yes</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            $_SESSION["CREDIT"] = $totalcredit;
            
            endforeach; 
            if ($search_string) {
                if (!$search_string=="")
                include BASE_PATH.'/includes/flashadd.php';
            }
          
            ?>
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
                echo '<li' . $li_class . '><a href="penjualan.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>
