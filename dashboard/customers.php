<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

// Costumers class
require_once BASE_PATH . '/lib/Costumers/Costumers.php';
$costumers = new Costumers();
if (getCurrentUserType() !== 'admin') {
    $url_prefix = URL_PREFIX ?: '';
    header('Location:' . $url_prefix . '/dashboard/login.php');
    exit();
}


$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


$pagelimit = 1000; // Increased to load more data for client-side filtering


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

// Get all rows for client-side filtering and sorting
$db->orderBy("credit", "desc");
$rows = $db->get('user', null, implode(',', $select));

// Collect all unique status values for the filter
$statusOptions = array_unique(array_column($rows, 'status'));
sort($statusOptions);

include '../includes/header.php';
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
    #customersTable {
        width: 100% !important;
    }

    #customersTable thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center !important;
        user-select: none;
    }

    /* Column names row - sortable */
    .column-names th {
        cursor: pointer !important;
    }

    @media (prefers-color-scheme: dark) {
        #customersTable thead th {
            background-color: #1e293b;
        }
    }

    #customersTable tbody td {
        text-align: center !important;
        vertical-align: middle !important;
    }

    #customersTable thead th.sorting:after,
    #customersTable thead th.sorting_asc:after,
    #customersTable thead th.sorting_desc:after {
        opacity: 0.3;
    }

    #customersTable thead th.sorting_asc:after,
    #customersTable thead th.sorting_desc:after {
        opacity: 1;
    }

    /* Filter row styling */
    .filter-row th {
        background-color: #ffffff !important;
        padding: 8px !important;
        border-top: 2px solid #dee2e6;
        cursor: default !important;
    }

    .filter-row th:after {
        display: none !important;
    }

    @media (prefers-color-scheme: dark) {
        .filter-row th {
            background-color: #0f172a !important;
            border-top: 2px solid #374151;
        }
    }

    .filter-row input,
    .filter-row select {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
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
    #customersTable .action-column {
        width: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
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
        <h1 class="page-header">AZEGSM - Users</h1>
    </div>
    <?php include './includes/flash_messages.php'; ?>

    <!-- Table -->
    <div class="table-responsive">
        <table id="customersTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="column-names">
                    <th draggable="true">ID<span class="resize-handle"></span></th>
                    <th draggable="true">Username<span class="resize-handle"></span></th>
                    <th draggable="true">API Key<span class="resize-handle"></span></th>
                    <th draggable="true">Status<span class="resize-handle"></span></th>
                    <th draggable="true">FRP Packet<span class="resize-handle"></span></th>
                    <th draggable="true">Credit Balance<span class="resize-handle"></span></th>
                    <th class="no-sort action-column">Action<span class="resize-handle"></span></th>
                </tr>
                <tr class="filter-row">
                    <th></th>
                    <th><input type="text" class="column-filter" data-column="1" placeholder="Filter username..."></th>
                    <th><input type="text" class="column-filter" data-column="2" placeholder="Filter API key..."></th>
                    <th>
                        <select class="column-filter" data-column="3">
                            <option value="">All Statuses</option>
                            <?php foreach ($statusOptions as $status): ?>
                                <option value="<?php echo htmlspecialchars($status); ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th>
                        <select class="column-filter" data-column="4">
                            <option value="">ALL</option>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                        </select>
                    </th>
                    <th><input type="number" class="column-filter" data-column="5" placeholder="Filter credit..."></th>
                    <th class="action-column"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo xss_clean($row['username']); ?></td>
                        <td>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <span class="api-key-text"><?php echo xss_clean($row['apikey']); ?></span>
                                <button class="btn btn-default btn-xs copy-api-btn"
                                    data-apikey="<?php echo htmlspecialchars($row['apikey'], ENT_QUOTES, 'UTF-8'); ?>"
                                    title="Copy API Key">
                                    <i class="glyphicon glyphicon-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td><?php echo xss_clean($row['status']); ?></td>
                        <td><?php echo $row['frp'] == 0 ? "NO" : "YES"; ?></td>
                        <td><?php echo xss_clean($row['credit']); ?></td>
                        <td class="action-column">
                            <div class="action-buttons">
                                <a href="edit_userinfo.php?admin_user_id=<?php echo $row['id']; ?>&operation=edit"
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
        // Initialize DataTable with all features
        var table = $('#customersTable').DataTable({
            // Disable DataTables' colReorder - we'll use custom implementation
            colReorder: false,
            // ordering: false,

            // Enable responsive design
            responsive: false,

            // Page length options
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

            // Column definitions
            columnDefs: [
                {
                    targets: 0,
                    width: '5%',
                    orderable: true
                },
                {
                    targets: 1,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 2,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 3,
                    width: '12%',
                    orderable: true
                },
                {
                    targets: 4,
                    width: '12%',
                    orderable: true
                },
                {
                    targets: 5,
                    width: '13%',
                    orderable: true,
                    type: 'num'
                },
                {
                    targets: 6,
                    width: '100px',
                    orderable: false
                }
            ],

            // Default sorting
            order: [[5, 'desc']], // Sort by Credit Balance descending

            // Styling
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',

            // Language customization
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                infoEmpty: "Showing 0 to 0 of 0 users",
                infoFiltered: "(filtered from _MAX_ total users)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });

        // Explicitly enable sorting on column names row
        $('.column-names th:not(.no-sort)').on('click', function (e) {
            var columnIndex = $(this).index();
            var currentOrder = table.order();

            // Toggle sort order for the clicked column
            if (currentOrder.length > 0 && currentOrder[0][0] === columnIndex) {
                // Same column clicked, toggle direction
                var newDirection = currentOrder[0][1] === 'asc' ? 'desc' : 'asc';
                table.order([columnIndex, newDirection]).draw();
            } else {
                // Different column clicked, sort ascending
                table.order([columnIndex, 'asc']).draw();
            }
        });

        // Prevent sorting when clicking on filter row
        $('.filter-row th').on('click', function (e) {
            e.stopPropagation();
        });

        // Prevent sorting when clicking inside filter inputs/selects
        $('.filter-row input, .filter-row select, .filter-row label').on('click', function (e) {
            e.stopPropagation();
        });

        // Custom column filters
        $('.column-filter').on('keyup change', function () {
            var columnIndex = $(this).data('column');
            var searchValue = this.value;

            table.column(columnIndex).search(searchValue).draw();
        });

        // ========== Column Resizing ==========
        let isResizing = false;
        let currentColumn = null;
        let startX = 0;
        let startWidth = 0;

        // Add mousedown event to resize handles
        $('.column-names th .resize-handle').on('mousedown', function (e) {
            e.preventDefault();
            e.stopPropagation();

            isResizing = true;
            currentColumn = $(this).parent();
            startX = e.pageX;
            startWidth = currentColumn.outerWidth();

            $('body').addClass('resizing');
        });

        // Handle mouse move for resizing
        $(document).on('mousemove', function (e) {
            if (!isResizing) return;

            const diff = e.pageX - startX;
            const newWidth = startWidth + diff;

            if (newWidth > 50) { // Minimum width
                currentColumn.css('width', newWidth + 'px');

                // Update corresponding filter row cell width
                const columnIndex = currentColumn.index();
                $('.filter-row th').eq(columnIndex).css('width', newWidth + 'px');
            }
        });

        // Handle mouse up to stop resizing
        $(document).on('mouseup', function () {
            if (isResizing) {
                isResizing = false;
                currentColumn = null;
                $('body').removeClass('resizing');
            }
        });

        // ========== Column Reordering (Drag and Drop) ==========
        let draggedColumn = null;
        let draggedIndex = -1;

        $('.column-names th[draggable="true"]').on('dragstart', function (e) {
            // Prevent drag if it's a resize handle click
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
                $('#customersTable tbody tr').each(function () {
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

        // ========== Copy API Key to Clipboard ==========
        $(document).on('click', '.copy-api-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const apiKey = $(this).data('apikey');
            const button = $(this);
            const icon = button.find('i');

            // Copy to clipboard using modern Clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(apiKey).then(function () {
                    // Success - show feedback
                    button.addClass('copied');
                    icon.removeClass('glyphicon-copy').addClass('glyphicon-ok');

                    // Reset after 2 seconds
                    setTimeout(function () {
                        button.removeClass('copied');
                        icon.removeClass('glyphicon-ok').addClass('glyphicon-copy');
                    }, 2000);
                }).catch(function (err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy API key');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = apiKey;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();

                try {
                    document.execCommand('copy');
                    // Success - show feedback
                    button.addClass('copied');
                    icon.removeClass('glyphicon-copy').addClass('glyphicon-ok');

                    // Reset after 2 seconds
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