<?php
session_name('AUTHID_SESSION');
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

if (!$filter_col) {
    $filter_col = 'id';
}

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

$statusfrp = "";
$statusfdl = "";
$statusflash = "";
$statusubl = "";

$pagelimit = 50;
$page = 1;
$db = getDbInstance();
$select = array('frpon', 'fdlon', 'edlon', 'ublon', 'id');
$db->where('status', 'ON');
$db->pageLimit = $pagelimit;


$rows = $db->arraybuilder()->paginate('server', $page, $select);
foreach ($rows as $row):
    $id = $row['id'];
    $statusfrp = $row['frpon'];
    $statusfdl = $row['fdlon'];
    $statusflash = $row['edlon'];
    $statusubl = $row['ublon'];

    break;


endforeach;

if ($statusfrp == 1) {
    $statusfrp = "ON";
} else {
    $statusfrp = "OFF";
}

if ($statusfdl == 1) {
    $statusfdl = "ON";
} else {
    $statusfdl = "OFF";
}


if ($statusflash == 1) {
    $statusflash = "ON";
} else {
    $statusflash = "OFF";
}

if ($statusubl == 1) {
    $statusubl = "ON";
} else {
    $statusubl = "OFF";
}


include '../includes/header.php';
?>
<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AzeGsm- Servers</h1>

    </div>
    <?php include './includes/flash_messages.php'; ?>



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


                <th width="20%">AUTH type</th>
                <th width="20%">STATUS</th>
                <th width="10%">Action</th>

            </tr>
        </thead>

        <tbody>

            <tr>
                <td><?php echo "FRP" ?></td>
                <td> <?php echo $statusfrp; ?></td>
                <td> <a href="setserver.php?authtype=frp&status=on">ON</a> || <a
                        href="setserver.php?authtype=frp&status=off">OFF</a></td>
            </tr>
            <tr>

                <td><?php echo "FDL" ?></td>
                <td> <?php echo $statusfdl; ?></td>
                <td> <a href="setserver.php?authtype=fdl&status=on">ON</a> || <a
                        href="setserver.php?authtype=fdl&status=off">OFF</a></td>
            </tr>

            <tr>
                <td><?php echo "FLASH" ?></td>
                <td> <?php echo $statusflash; ?></td>
                <td> <a href="setserver.php?authtype=flash&status=on">ON</a> || <a
                        href="setserver.php?authtype=flash&status=off">OFF</a></td>
            </tr>

            <tr>
                <td><?php echo "UBL" ?></td>
                <td> <?php echo $statusubl; ?></td>
                <td> <a href="setserver.php?authtype=ubl&status=on">ON</a> || <a
                        href="setserver.php?authtype=ubl&status=off">OFF</a></td>
            </tr>



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