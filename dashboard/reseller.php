<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

require_once BASE_PATH . '/lib/Users/Users.php';
$users = new Users();

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

$db = getDbInstance();
$select = array('id', 'username', 'status', 'apikey', 'email', 'credit');

// Get all resellers for client-side filtering and sorting
$db->where('status', "reseller");
$db->orderBy('id', 'DESC');
$rows = $db->get('user', null, implode(',', $select));

// Collect unique status values (should just be 'reseller')
$statusOptions = array_unique(array_column($rows, 'status'));
sort($statusOptions);

include BASE_PATH . '/includes/admin_header.php';
?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css">

<style>
    @media (prefers-color-scheme: dark) {
        /* DataTables dark mode styling */
        .dataTables_wrapper {
            color: #f1f5f9 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #f1f5f9 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #f1f5f9 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #6b7280 !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #374151 !important;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #374151 !important;
        }
    }

    /* Modern table styling */
    table.dataTable tbody td {
        text-align: center;
        vertical-align: middle;
    }

    table.dataTable thead th {
        text-align: center;
    }

    /* Filter row styling */
    .filter-row input,
    .filter-row select {
        width: 100%;
        padding: 6px 10px;
        font-size: 13px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    @media (prefers-color-scheme: dark) {
        .filter-row input,
        .filter-row select {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #f1f5f9 !important;
        }
    }

    .filter-row input:focus,
    .filter-row select:focus {
        outline: none;
        border-color: #3b82f6;
    }

    /* Column names row - draggable */
    .column-names th {
        position: relative;
        cursor: move;
    }

    .column-names th.no-sort {
        cursor: default;
    }

    /* Fixed width for Action column */
    #resellersTable th:last-child,
    #resellersTable td:last-child {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
    }

    /* Column resize handle */
    .column-names th .resize-handle {
        position: absolute;
        top: 0;
        right: -3px;
        width: 6px;
        height: 100%;
        cursor: col-resize;
        z-index: 10;
        background: transparent;
    }

    .column-names th .resize-handle:hover {
        background-color: #3b82f6;
    }

    /* Prevent text selection during resize */
    .resizing {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    /* Column resizer */
    .dataTables_wrapper table {
        border-collapse: collapse;
    }

    th.dt-orderable-asc,
    th.dt-orderable-desc {
        cursor: pointer;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
    }

    /* Copy API button */
    .copy-api-btn {
        padding: 2px 6px !important;
        font-size: 12px;
        line-height: 1.2;
        border-radius: 3px;
        transition: all 0.2s;
    }

    .copy-api-btn:hover {
        background-color: #5cb85c !important;
        color: white !important;
        border-color: #5cb85c !important;
    }

    .copy-api-btn.copied {
        background-color: #5cb85c !important;
        color: white !important;
        border-color: #5cb85c !important;
    }

    @media (prefers-color-scheme: dark) {
        .copy-api-btn {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
            border-color: #374151 !important;
        }

        .copy-api-btn:hover {
            background-color: #5cb85c !important;
            color: white !important;
            border-color: #5cb85c !important;
        }
    }

    /* Responsive improvements */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 10px 0;
    }

    .filter-row th:after,
    .filter-row th:before {
        display: none !important;
    }
</style>

<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AZEGSM Resellers</h1>
    </div>
    <?php include BASE_PATH . '/includes/flash_messages.php'; ?>

    <!-- Table -->
    <div class="table-responsive">
        <table id="resellersTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="column-names">
                    <th draggable="true">ID<span class="resize-handle"></span></th>
                    <th draggable="true">Name<span class="resize-handle"></span></th>
                    <th draggable="true">Admin Type<span class="resize-handle"></span></th>
                    <th draggable="true">API Key<span class="resize-handle"></span></th>
                    <th draggable="true">Credit Balance<span class="resize-handle"></span></th>
                    <th class="no-sort">Action<span class="resize-handle"></span></th>
                </tr>
                <tr class="filter-row">
                    <th></th>
                    <th><input type="text" class="column-filter" data-column="1" placeholder="Filter name..."></th>
                    <th><input type="text" class="column-filter" data-column="2" placeholder="Filter type..."></th>
                    <th><input type="text" class="column-filter" data-column="3" placeholder="Filter API key..."></th>
                    <th><input type="number" class="column-filter" data-column="4" placeholder="Filter credit..."></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row):
                    if (!isset($_SESSION['name']) || $_SESSION['name'] !== $row['username']):
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span class="api-key-text" style="font-family: monospace; font-size: 13px;"><?php echo htmlspecialchars($row['apikey']); ?></span>
                                <button class="btn btn-default btn-xs copy-api-btn"
                                        data-apikey="<?php echo htmlspecialchars($row['apikey'], ENT_QUOTES, 'UTF-8'); ?>"
                                        title="Copy API Key">
                                    <i class="glyphicon glyphicon-copy"></i>
                                </button>
                                <a href="reseller.php?regenerate_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-xs"
                                    onclick="return confirm('Are you sure you want to regenerate the API key for <?php echo htmlspecialchars($row['username']); ?>?');"
                                    title="Regenerate API Key">
                                    <i class="glyphicon glyphicon-refresh"></i>
                                </a>
                            </div>
                        </td>
                        <td><?php echo isset($row['credit']) ? htmlspecialchars($row['credit']) : '0'; ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit_reseller.php?admin_user_id=<?php echo $row['id']; ?>&operation=edit"
                                    class="btn btn-primary btn-sm" title="Edit">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm delete_btn" data-toggle="modal"
                                    data-target="#confirm-delete-<?php echo $row['id']; ?>" title="Delete">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                        <div class="modal-dialog">
                            <form action="delete_customer.php" method="POST">
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
                <?php endif; endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- //Table -->
</div>
<!-- //Main container -->

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        var table = $('#resellersTable').DataTable({
            colReorder: false,
            responsive: true,
            pageLength: 50,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                { targets: 0, width: '5%', orderable: true },
                { targets: 1, width: '25%', orderable: true },
                { targets: 2, width: '15%', orderable: true },
                { targets: 3, width: '25%', orderable: true },
                { targets: 4, width: '15%', orderable: true, type: 'num' },
                { targets: 5, width: '120px', orderable: false }
            ],
            order: [[0, 'desc']],
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ resellers",
                infoEmpty: "Showing 0 to 0 of 0 resellers",
                infoFiltered: "(filtered from _MAX_ total resellers)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });

        // Column sorting on header click
        $('.column-names th:not(.no-sort)').on('click', function (e) {
            var columnIndex = $(this).index();
            var currentOrder = table.order();

            if (currentOrder.length > 0 && currentOrder[0][0] === columnIndex) {
                var newDirection = currentOrder[0][1] === 'asc' ? 'desc' : 'asc';
                table.order([columnIndex, newDirection]).draw();
            } else {
                table.order([columnIndex, 'asc']).draw();
            }
        });

        // Prevent sorting when clicking on filter row
        $('.filter-row th').on('click', function (e) {
            e.stopPropagation();
        });

        // Prevent sorting when clicking inside filter inputs/selects
        $('.filter-row input, .filter-row select, .filter-row label').on('mousedown click', function (e) {
            e.stopPropagation();
        });

        // Custom column filters
        $('.column-filter').on('keyup change', function () {
            var columnIndex = $(this).data('column');
            var searchValue = this.value;

            if (columnIndex === 4) { // Credit Balance - exact match
                table.column(columnIndex).search(
                    searchValue ? '^' + searchValue + '$' : '',
                    true,
                    false
                ).draw();
            } else {
                table.column(columnIndex).search(searchValue).draw();
            }
        });

        // Column Resizing
        let isResizing = false;
        let currentColumn = null;
        let startX = 0;
        let startWidth = 0;

        $('.column-names th .resize-handle').on('mousedown', function (e) {
            e.preventDefault();
            e.stopPropagation();

            isResizing = true;
            currentColumn = $(this).parent();
            startX = e.pageX;
            startWidth = currentColumn.outerWidth();

            $('body').addClass('resizing');
        });

        $(document).on('mousemove', function (e) {
            if (!isResizing) return;

            const diff = e.pageX - startX;
            const newWidth = startWidth + diff;

            if (newWidth > 50) {
                currentColumn.css('width', newWidth + 'px');
                const columnIndex = currentColumn.index();
                $('.filter-row th').eq(columnIndex).css('width', newWidth + 'px');
            }
        });

        $(document).on('mouseup', function () {
            if (isResizing) {
                isResizing = false;
                currentColumn = null;
                $('body').removeClass('resizing');
            }
        });

        // Column Reordering (Drag and Drop)
        let draggedColumn = null;
        let draggedIndex = -1;

        $('.column-names th[draggable="true"]').on('dragstart', function (e) {
            if ($(e.target).hasClass('resize-handle')) {
                e.preventDefault();
                return false;
            }

            draggedColumn = $(this);
            draggedIndex = draggedColumn.index();
            e.originalEvent.dataTransfer.effectAllowed = 'move';
            $(this).css('opacity', '0.5');
        });

        $('.column-names th').on('dragover', function (e) {
            if (!draggedColumn) return;

            e.preventDefault();
            e.originalEvent.dataTransfer.dropEffect = 'move';

            const dropIndex = $(this).index();
            if (dropIndex !== draggedIndex) {
                $(this).css('border-left', '2px solid #3b82f6');
            }
        });

        $('.column-names th').on('dragleave', function () {
            $(this).css('border-left', '');
        });

        $('.column-names th').on('drop', function (e) {
            e.preventDefault();

            if (!draggedColumn) return;

            const dropIndex = $(this).index();

            if (dropIndex !== draggedIndex) {
                // Reorder column headers
                if (dropIndex < draggedIndex) {
                    $(this).before(draggedColumn);
                } else {
                    $(this).after(draggedColumn);
                }

                // Reorder filter row cells
                const draggedFilter = $('.filter-row th').eq(draggedIndex);
                const dropFilter = $('.filter-row th').eq(dropIndex);

                if (dropIndex < draggedIndex) {
                    dropFilter.before(draggedFilter);
                } else {
                    dropFilter.after(draggedFilter);
                }

                // Reorder all table body cells
                $('#resellersTable tbody tr').each(function () {
                    const draggedCell = $(this).find('td').eq(draggedIndex);
                    const dropCell = $(this).find('td').eq(dropIndex);

                    if (dropIndex < draggedIndex) {
                        dropCell.before(draggedCell);
                    } else {
                        dropCell.after(draggedCell);
                    }
                });
            }

            $(this).css('border-left', '');
        });

        $('.column-names th').on('dragend', function () {
            $(this).css('opacity', '1');
            $('.column-names th').css('border-left', '');
            draggedColumn = null;
            draggedIndex = -1;
        });

        // Copy API Key to Clipboard
        $(document).on('click', '.copy-api-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const apiKey = $(this).data('apikey');
            const button = $(this);
            const icon = button.find('i');

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(apiKey).then(function () {
                    button.addClass('copied');
                    icon.removeClass('glyphicon-copy').addClass('glyphicon-ok');

                    setTimeout(function () {
                        button.removeClass('copied');
                        icon.removeClass('glyphicon-ok').addClass('glyphicon-copy');
                    }, 2000);
                }).catch(function (err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy API key');
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = apiKey;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();

                try {
                    document.execCommand('copy');
                    button.addClass('copied');
                    icon.removeClass('glyphicon-copy').addClass('glyphicon-ok');

                    setTimeout(function () {
                        button.removeClass('copied');
                        icon.removeClass('glyphicon-ok').addClass('glyphicon-copy');
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy API key');
                }

                document.body.removeChild(textArea);
            }
        });
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>
