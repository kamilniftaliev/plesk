<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Costumers class
require_once BASE_PATH . '/lib/servers/servers.php';
$servers = new servers();

// Get Input data from query string
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


// Per page limit for pagination.
$pagelimit = 30;

// Get current page.
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}
if (!$search_string) {
    $search_string = '';
}
// If filter types are not selected we show latest added data first
if (!$filter_col) {
    $filter_col = 'id';
}

//Get DB instance. i.e instance of MYSQLiDB Library
$db = getDbInstance();
$select = array('id', 'limitedl', 'limitfrp', 'limitfdl', 'status', 'serversupport', 'limitleftedl', 'limitleftfrp', 'limitleftfdl');
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


// Set pagination limit
$db->pageLimit = $pagelimit;

// Get result of the query.
$rows = $db->arraybuilder()->paginate('server', $page, $select);
$total_pages = $db->totalPages;

include '../includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AzeGsm- Servers</h1>

    </div>
    <?php include 'includes/flash_messages.php'; ?>



    <!-- Filters -->
    <div class="well text-center filter-form">
        <form class="form form-inline flex items-center gap-4 mx-auto justify-center" action="">
            <label for="input_search" class="mb-0">Search</label>
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
                <th width="10%">status</th>
                <th width="10%">limit Flash</th>
                <th width="10%">Left Flash</th>
                <th width="10%">Limit Frp</th>
                <th width="10%">Left Frp</th>
                <th width="10%">Limit Fdl</th>
                <th width="10%">Left Fdl</th>
                <th width="20%">server support</th>
                <th width="10%">Action</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo xss_clean($row['status']); ?></td>
                    <td><?php echo xss_clean($row['limitedl']); ?></td>
                    <td><?php echo xss_clean($row['limitleftedl']); ?></td>
                    <td><?php echo xss_clean($row['limitfrp']); ?></td>
                    <td><?php echo xss_clean($row['limitleftfrp']); ?></td>
                    <td><?php echo xss_clean($row['limitfdl']); ?></td>
                    <td><?php echo xss_clean($row['limitleftfdl']); ?></td>
                    <td><?php echo xss_clean($row['serversupport']); ?></td>
                    <td>
                        <a href="edit_servers.php?serverid=<?php echo $row['id']; ?>&operation=edit"
                            class="btn btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                        <a href="#" class="btn btn-danger delete_btn" data-toggle="modal"
                            data-target="#confirm-delete-<?php echo $row['id']; ?>"><i
                                class="glyphicon glyphicon-trash"></i></a>

                    </td>
                </tr>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                    <div class="modal-dialog">
                        <form action="delete_server.php" method="POST">
                            <!-- Modal content -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Confirm</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="del_id" id="del_id" value="<?php echo $row['id']; ?>">
                                    <p>Are you sure you want to delete <?php echo $row['id']; ?> ?</p>
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
        <?php echo paginationLinks($page, $total_pages, 'servers.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>