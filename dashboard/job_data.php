<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

if (getCurrentUserType() !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

$db = getDbInstance();

// Get DataTables parameters
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 50;
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$orderColumnIndex = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 1;
$orderDir = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'desc';

// Column names mapping (matching table structure)
$columns = [
    0 => 'id',      // No (calculated)
    1 => 'id',      // ID
    2 => 'iduser',  // ID User
    3 => 'name',    // Username
    4 => 'configblob', // Config Blob
    5 => 'serviceid',  // Auth Type
    6 => 'serverid',   // Server ID
    7 => 'status',     // Response (always "Hidden")
    8 => 'cost',       // Cost
    9 => 'tgl',        // Time
    10 => 'status'     // Status
];

// Get total records count
$totalRecords = $db->getValue('data', 'COUNT(*)');

// Apply global search filter (escape for security)
if (!empty($searchValue)) {
    $escapedSearch = $db->escape($searchValue);
    $db->where("(name LIKE '%{$escapedSearch}%' OR configblob LIKE '%{$escapedSearch}%' OR status LIKE '%{$escapedSearch}%' OR id LIKE '%{$escapedSearch}%' OR iduser LIKE '%{$escapedSearch}%')");
}

// Apply column-specific filters
if (isset($_GET['columns'])) {
    foreach ($_GET['columns'] as $i => $column) {
        if (!empty($column['search']['value']) && isset($columns[$i])) {
            $colName = $columns[$i];
            $filterValue = $column['search']['value'];
            $escapedValue = $db->escape($filterValue);

            // Special handling for different column types
            if ($i == 5) { // Auth Type - filter by service name
                $serviceMap = [
                    'EDL' => '1',
                    'FRP' => '2',
                    'FDL' => '4',
                    'MTK 5' => '6',
                    'MTK 6 new' => '9'
                ];
                if (isset($serviceMap[$filterValue])) {
                    $db->where('serviceid', $serviceMap[$filterValue]);
                }
            } else if ($i == 6) { // Server ID - extract number from "Server X"
                if (preg_match('/\d+/', $filterValue, $matches)) {
                    $db->where('serverid', intval($matches[0]));
                } else {
                    $db->where("serverid LIKE '%{$escapedValue}%'");
                }
            } else if ($i == 9) { // Time column - date filter
                // Filter by date (YYYY-MM-DD format from date picker)
                $db->where("DATE(tgl) = '{$escapedValue}'");
            } else if ($i == 10) { // Status column - case insensitive match
                $db->where("LOWER(status) = LOWER('{$escapedValue}')");
            } else if (in_array($i, [1, 2, 8])) { // Numeric columns (ID, ID User, Cost)
                $db->where($colName, intval($filterValue));
            } else {
                $db->where("{$colName} LIKE '%{$escapedValue}%'");
            }
        }
    }
}

// Clone db instance for filtered count
$dbClone = clone $db;
$filteredRecords = $dbClone->getValue('data', 'COUNT(*)');

// Apply ordering
$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'id';
$db->orderBy($orderColumn, $orderDir);

// Get data
$select = ['id', 'name', 'iduser', 'configblob', 'serviceid', 'tgl', 'status', 'serverid', 'cost'];
$rows = $db->get('data', [$start, $length], implode(',', $select));

// Format data for DataTables
$data = [];
$rowNum = $start + 1;

foreach ($rows as $row) {
    // Determine auth type
    $authType = '';
    switch ($row['serviceid']) {
        case '1':
            $authType = 'EDL';
            break;
        case '2':
            $authType = 'FRP';
            break;
        case '4':
            $authType = 'FDL';
            break;
        case '6':
            $authType = 'MTK 5';
            break;
        case '9':
            $authType = 'MTK 6 new';
            break;
    }

    // Truncate configblob
    $displayBlob = strlen($row['configblob']) > 10
        ? substr($row['configblob'], 0, 10) . '...'
        : $row['configblob'];

    // Username with copy button
    $usernameHtml = '<div style="display: flex; position: relative; align-items: center; justify-content: center; gap: 6px;">' .
        '<span>' . htmlspecialchars($row['name']) . '</span>' .
        '<button class="copy-btn" data-text="' . htmlspecialchars($row['name']) . '" type="button">' .
        '<i class="glyphicon glyphicon-copy"></i>' .
        '</button>' .
        '</div>';

    // Config Blob with copy button
    $configBlobHtml = '<div style="display: flex; position: relative; align-items: center; justify-content: center; gap: 6px;">' .
        '<span>' . htmlspecialchars($displayBlob) . '</span>' .
        '<button class="copy-btn" data-text="' . htmlspecialchars($row['configblob']) . '" type="button">' .
        '<i class="glyphicon glyphicon-copy"></i>' .
        '</button>' .
        '</div>';

    // Status styling with proper labels
    $statusLabel = ucfirst(strtolower($row['status'])); // "done" -> "Done", "FAILED" -> "Failed"
    $statusClass = 'status-' . strtolower($row['status']); // status-done, status-failed, status-error, status-pending

    $data[] = [
        $rowNum++,                                    // No
        htmlspecialchars($row['id']),                // ID
        htmlspecialchars($row['iduser']),            // ID User
        $usernameHtml,                                // Username with copy button
        $configBlobHtml,                              // Config Blob with copy button
        $authType,                                    // Auth Type
        'Server ' . htmlspecialchars($row['serverid']), // Server ID
        'Hidden',                                     // Response
        htmlspecialchars($row['cost']),              // Cost
        htmlspecialchars($row['tgl']),               // Time
        '<div class="' . $statusClass . ' w-full flex items-center justify-center"><span>' . htmlspecialchars($statusLabel) . '</span></div>' // Status
        // '<div class="status-error w-full">' . htmlspecialchars($statusLabel) . '</div>' // Status
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'draw' => $draw,
    'recordsTotal' => intval($totalRecords),
    'recordsFiltered' => intval($filteredRecords),
    'data' => $data
]);
