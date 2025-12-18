<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Check permission
requirePermission('penjualan');

// Get current user type
$current_user_type = getCurrentUserType();

// Get current user ID
$current_user_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

$db = getDbInstance();

// Get DataTables parameters
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 50;
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$orderColumnIndex = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 0;
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'desc';

// Column names mapping
$columns = [
    0 => 'id',
    1 => 'email',
    2 => 'jumlah',
    3 => 'resellername',
    4 => 'created_at',
    5 => 'ispay'
];

// Build the base query with status filter
$db->where('1', '1'); // Always true condition to start

// Filter by reseller for reseller users
if ($current_user_type === 'reseller' && $current_user_id) {
    $db->where('resellerid', $current_user_id);
}

// Get total records count (with reseller filter)
$totalRecords = $db->getValue('penjualancredit', 'COUNT(*)');

// Apply global search filter
if (!empty($searchValue)) {
    $escapedSearch = $db->escape($searchValue);
    $db->where("(id LIKE '%{$escapedSearch}%' OR email LIKE '%{$escapedSearch}%' OR jumlah LIKE '%{$escapedSearch}%' OR resellername LIKE '%{$escapedSearch}%' OR created_at LIKE '%{$escapedSearch}%')");
}

// Apply column-specific filters
if (isset($_GET['columns'])) {
    foreach ($_GET['columns'] as $i => $column) {
        if (!empty($column['search']['value']) && isset($columns[$i])) {
            $colName = $columns[$i];
            $searchVal = $db->escape($column['search']['value']);

            // Special handling for status column
            if ($colName === 'ispay') {
                if ($searchVal === 'Paid') {
                    $db->where('ispay', '1');
                } elseif ($searchVal === 'Unpaid') {
                    $db->where('ispay', '0');
                }
            } else {
                $db->where("{$colName} LIKE '%{$searchVal}%'");
            }
        }
    }
}

// Re-apply reseller filter for filtered count
if ($current_user_type === 'reseller' && $current_user_id) {
    $db->where('resellerid', $current_user_id);
}

// Get filtered records count
$filteredRecords = $db->getValue('penjualancredit', 'COUNT(*)');

// Reset database instance for actual data query
$db = getDbInstance();

// Apply reseller filter again for actual data query
if ($current_user_type === 'reseller' && $current_user_id) {
    $db->where('resellerid', $current_user_id);
}

// Apply global search filter again
if (!empty($searchValue)) {
    $escapedSearch = $db->escape($searchValue);
    $db->where("(id LIKE '%{$escapedSearch}%' OR email LIKE '%{$escapedSearch}%' OR jumlah LIKE '%{$escapedSearch}%' OR resellername LIKE '%{$escapedSearch}%' OR created_at LIKE '%{$escapedSearch}%')");
}

// Apply column-specific filters again
if (isset($_GET['columns'])) {
    foreach ($_GET['columns'] as $i => $column) {
        if (!empty($column['search']['value']) && isset($columns[$i])) {
            $colName = $columns[$i];
            $searchVal = $db->escape($column['search']['value']);

            // Special handling for status column
            if ($colName === 'ispay') {
                if ($searchVal === 'Paid') {
                    $db->where('ispay', '1');
                } elseif ($searchVal === 'Unpaid') {
                    $db->where('ispay', '0');
                }
            } else {
                $db->where("{$colName} LIKE '%{$searchVal}%'");
            }
        }
    }
}

// Apply ordering
if (isset($columns[$orderColumnIndex])) {
    $orderColumn = $columns[$orderColumnIndex];
    $db->orderBy($orderColumn, $orderDir);
}

// Apply pagination
$db->pageLimit = $length;
$page = ($start / $length) + 1;

// Get the data
$select = ['id', 'email', 'jumlah', 'resellername', 'created_at', 'ispay'];
$rows = $db->arraybuilder()->paginate('penjualancredit', $page, $select);

// Format data for DataTables
$data = [];
foreach ($rows as $row) {
    $rowData = [
        $row['id'],
        htmlspecialchars($row['email']),
        htmlspecialchars($row['jumlah']),
        htmlspecialchars($row['resellername']),
        $row['created_at'],
        $row['ispay']
    ];

    // Add Action column only for admin users
    if ($current_user_type === 'admin') {
        $actions = '<div style="display: flex; gap: 5px; justify-content: center;">';
        $actions .= '<a href="edit_penjualan.php?serverid=' . $row['id'] . '&operation=edit" class="btn btn-primary btn-sm" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>';
        $actions .= '<a href="#" class="btn btn-danger btn-sm delete_btn" data-toggle="modal" data-target="#confirm-delete-' . $row['id'] . '" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';

        // Add delete modal HTML
        $actions .= '<div class="modal fade" id="confirm-delete-' . $row['id'] . '" role="dialog">';
        $actions .= '<div class="modal-dialog">';
        $actions .= '<form action="delete_history_credit.php" method="POST">';
        $actions .= '<div class="modal-content">';
        $actions .= '<div class="modal-header">';
        $actions .= '<button type="button" class="close" data-dismiss="modal">&times;</button>';
        $actions .= '<h4 class="modal-title">Confirm</h4>';
        $actions .= '</div>';
        $actions .= '<div class="modal-body">';
        $actions .= '<input type="hidden" name="del_id" value="' . $row['id'] . '">';
        $actions .= '<p>Are you sure you want to delete transaction #' . $row['id'] . '?</p>';
        $actions .= '</div>';
        $actions .= '<div class="modal-footer">';
        $actions .= '<button type="submit" class="btn btn-default pull-left">Yes</button>';
        $actions .= '<button type="button" class="btn btn-default" data-dismiss="modal">No</button>';
        $actions .= '</div>';
        $actions .= '</div>';
        $actions .= '</form>';
        $actions .= '</div>';
        $actions .= '</div>';
        $actions .= '</div>';

        $rowData[] = $actions;
    }

    $data[] = $rowData;
}

// Prepare response
$response = [
    'draw' => $draw,
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $filteredRecords,
    'data' => $data
];

header('Content-Type: application/json');
echo json_encode($response);
