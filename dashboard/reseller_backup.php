<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';


require_once BASE_PATH . '/lib/Users/Users.php';
$users = new Users();

//echo $_SESSION['admin_type'];
if ($_SESSION['admin_type'] !== 'admin') {

    header('Location:/dashboard/login.php');
    exit();
}

// Function to generate random string for API key
function generateRandomString($length = 8)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

// Handle API key regeneration
$regenerate_id = filter_input(INPUT_GET, 'regenerate_id', FILTER_VALIDATE_INT);
if ($regenerate_id) {
    $new_apikey = "AH-TOOL-" . generateRandomString(8);

    $db = getDbInstance();
    $db->where('id', $regenerate_id);
    $stat = $db->update('user', ['apikey' => $new_apikey]);

    if ($stat) {
        $_SESSION['success'] = "API Key regenerated successfully!";
    } else {
        $_SESSION['failure'] = "Failed to regenerate API Key: " . $db->getLastError();
    }

    header('Location: reseller.php');
    exit;
}

$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$order_by = filter_input(INPUT_GET, 'order_by');
$del_id = filter_input(INPUT_GET, 'del_id');


$pagelimit = 20;


$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

if (!$filter_col) {
    $filter_col = 'id';
}
if (!$order_by) {
    $order_by = 'Desc';
}


$db = getDbInstance();
$select = array('id', 'username', 'status', 'apikey', 'email');


if ($search_string) {
    $db->where('username', '%' . $search_string . '%', 'like');
}

//If order by option selected
if ($order_by) {
    $db->orderBy($filter_col, $order_by);
}


$db->pageLimit = $pagelimit;

$db->where('status', "reseller");
$rows = $db->arraybuilder()->paginate('user', $page, $select);
$total_pages = $db->totalPages;

include './includes/admin_header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <div>
            <h1 class="page-header">AZEGSM Resellers</h1>
        </div>
        <div class="col-lg-6">
            <div class="page-action-links text-right">
                <!-- <a href="add_reseller.php" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add New Reseller</a> -->
            </div>
        </div>
    </div>
    <?php include './includes/flash_messages.php'; ?>

    <?php
    if (isset($del_stat) && $del_stat == 1) {
        echo '<div class="alert alert-info">Successfully deleted</div>';
    }
    ?>

    <!-- Filters -->


    <!-- //Filters -->

    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">Name</th>
                <th width="10%">Admin Type</th>
                <th width="25%">API Key</th>
                <th width="15%">Credit Balances</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row):

                if (!isset($_SESSION['name']) || $_SESSION['name'] !== $row['username']) {
                    ?>

                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span
                                            style="font-family: monospace; font-size: 13px;"><?php echo htmlspecialchars($row['apikey']); ?></span>
                                        <a href="reseller.php?regenerate_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-xs"
                                            onclick="return confirm('Are you sure you want to regenerate the API key for <?php echo htmlspecialchars($row['username']); ?>?');"
                                            title="Regenerate API Key">
                                            <i class="glyphicon glyphicon-refresh"></i>
                                        </a>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>

                                <td>
                                    <a href="edit_reseller.php?admin_user_id=<?php echo $row['id']; ?>&operation=edit"
                                        class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                                        data-target="#confirm-delete-<?php echo $row['id']; ?>"><i
                                            class="glyphicon glyphicon-trash"></i></a>
                                </td>
                        <?php } ?>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                        <div class="modal-dialog">
                            <form action="delete_customer.php" method="POST">
                                <!-- Modal content -->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Confirm</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="del_id" id="del_id" value="<?php echo $row['id']; ?>">
                                        <p>Are you sure you want to delete <?php echo $row['username']; ?> ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-default pull-left">Yes</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
                echo '<li' . $li_class . '><a href="admin_users.php' . $http_query . '&page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>