<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Costumers class
require_once BASE_PATH . '/lib/Costumers/Costumers.php';
$costumers = new Costumers();
if ($_SESSION['admin_type'] !== 'admin') {

    header('Location:/dashboard/login.php');
    exit();
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
$select = array('id', 'username', 'status', 'credit', 'apikey', 'frp');
function obfuscate_email($email)
{
    $em = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

if ($search_string) {
    $db->where('username', '%' . $search_string . '%', 'like');
}

$db->orderBy("credit", "desc");
$db->pageLimit = $pagelimit;

$rows = $db->arraybuilder()->paginate('user', $page, $select);
$total_pages = $db->totalPages;

include BASE_PATH . '/includes/admin_header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AZEGSM - Users</h1>

    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>



    <!-- Filters -->
    <div class="well text-center filter-form">
        <form class="form form-inline" action="">
            <label for="input_search">Search</label>
            <input type="text" class="form-control" id="input_search" name="search_string"
                value="<?php echo htmlspecialchars($search_string, ENT_QUOTES, 'UTF-8'); ?>">

            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>
    <hr>
    <!-- //Filters -->




    <!-- Table -->
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Username</th>
                <th width="15%">apikey</th>
                <th width="15%">Status</th>
                <th width="15%">FRP Packet</th>
                <th width="15%">Credit Balance</th>
                <th width="20%">Action</th>


            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo xss_clean($row['username']); ?></td>
                    <td><?php echo xss_clean($row['apikey']); ?></td>
                    <td><?php echo xss_clean($row['status']); ?></td>
                    <td><?php if ($row['frp'] == 0) {
                        echo "NO";
                    } else {
                        echo "YES";

                    }


                    ?></td>

                    <td><?php echo xss_clean($row['credit']); ?></td>

                    <td>
                        <a href="edit_userinfo.php?admin_user_id=<?php echo $row['id']; ?>&operation=edit"
                            class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                            data-target="#confirm-delete-<?php echo $row['id']; ?>"><i
                                class="glyphicon glyphicon-trash"></i></a>
                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="delete_user.php" method="POST">
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
        <?php echo paginationLinks($page, $total_pages, 'customers.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>