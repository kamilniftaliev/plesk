<?php
session_name('AUTHID_SESSION');
session_start();
require_once 'config/config.php';
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

if (!$filter_col) {
    $filter_col = 'id';
}

$db = getDbInstance();
$select = array('id', 'patchflash');
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
$rows = $db->arraybuilder()->paginate('multipatch', $page, $select);
$total_pages = $db->totalPages;

$statusfrp = "";
$statusfdl = "";
$statusflash = "";
$statusubl = "";

$pagelimit = 50;
$page = 1;
$db = getDbInstance();
$select = array('id', 'patchflash', 'multipliers');
$db->where('id', '1');
$db->pageLimit = $pagelimit;
$statuspatch = "";
$multipliers = "";
$patchname = "";
$rows = $db->arraybuilder()->paginate('multipatch', $page, $select);
foreach ($rows as $row):
    $id = $row['id'];
    $statuspatch = $row['patchflash'];
    ;
    $multipliers = $row['multipliers'];
    ;
    break;


endforeach;


if ($multipliers == 2) {
    $patchname = "50";
}
if ($multipliers == 3) {
    $patchname = "75";
}
if ($statuspatch == 1) {
    $statuspatch = "Gift By My Friend";

} elseif ($statuspatch == 2) {
    $statuspatch = "PATCH ONLY";

} else {

    $statuspatch = "ORIGINAL BLOB";

}




include BASE_PATH . '/includes/admin_header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AzeGsm- Servers</h1>
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


                <th width="20%">PATCH type</th>
                <th width="10%">STATUS</th>
                <th width="40%">Action</th>

            </tr>
        </thead>

        <tbody>

            <tr>
                <td>PATCH v6</td>
                <td> <?php echo $statuspatch . " " . $patchname; ?></td>
                <td> <a href="setpatch.php?authtype=frp&status=1&multi=2">25 To 50 PATCH</a> || <a
                        href="setpatch.php?authtype=frp&status=1&multi=3">25 To 75 PATCH</a> || <a
                        href="setpatch.php?authtype=frp&status=2">PATCH ONLY </a>|| <a
                        href="setpatch.php?authtype=frp&status=3">25 Limit</a></td>
            </tr>
            <tr>




        </tbody>
    </table>
    <!-- //Table -->

    <!-- Pagination -->
    <div class="text-center">
        <?php echo paginationLinks($page, $total_pages, 'serverspatch.php'); ?>
    </div>
    <!-- //Pagination -->
</div>
<!-- //Main container -->
<?php include BASE_PATH . '/includes/footer.php'; ?>