<?php
session_name('RESELLER_SESSION');
session_start();
require_once 'config/config.php';
require_once BASE_PATH.'/includes/auth_validate.php';

// Users class
require_once BASE_PATH.'/lib/Users/Users.php';
$users = new Users();

// Only super admin is allowed to access this page
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'super')
{
    // Show permission denied message
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit('401 Unauthorized');
}

// Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');
$del_id = filter_input(INPUT_GET, 'del_id');

// Per page limit for pagination.
$pagelimit = 20;

// Get current page.
$page = filter_input(INPUT_GET, 'page');
if (!$page)
{
    $page = 1;
}

// If filter types are not selected we show latest added data first
if (!$filter_col)
{
    $filter_col = 'saleid';
}
if (!$order_by)
{
    $order_by = 'Desc';
}

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('saleid', 'username', 'credit' ,'input','cost','api','orderid','status','datetime');

//Start building query according to input parameters.
// If search string
if ($search_string)
{
    $db->where('usernamename', '%' . $search_string . '%', 'like');
}

//If order by option selected
if ($order_by)
{
    $db->orderBy($filter_col, $order_by);
}

// Set pagination limit
$db->pageLimit = $pagelimit;

// Get result of the query.
$rows = $db->arraybuilder()->paginate('Sales', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH.'/includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-6">
            <h1 class="page-header">Xiaomi AzeGsm Reseller Sold Records From DHRU</h1>
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
    
  
    <!-- //Filters -->

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="10%">Sale ID</th>
                <th width="15%">Reseller Name</th>
                <th width="10%">Reseller Credit</th>
				<th width="20%">Sold To</th>
                <th width="8%">Order QNT</th>
                <th width="7%">Order ID</th>
                <th width="20%">Order status</th>
                <th width="15%">Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?php echo $row['saleid']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['credit']); ?></td>
                <td><?php echo htmlspecialchars($row['input']); ?></td>
				<td><?php echo htmlspecialchars($row['cost']); ?></td>
				<td><?php echo htmlspecialchars($row['orderid']); ?></td>
				<td><?php echo htmlspecialchars($row['status']); ?></td>
				<td><?php echo htmlspecialchars($row['datetime']); ?></td>
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
                echo '<li' . $li_class . '><a href="soldrs_DHRU.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH.'/includes/footer.php'; ?>
