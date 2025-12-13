<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';


require_once BASE_PATH . '/lib/Users/Users.php';
$users = new Users();


if ($_SESSION['admin_type'] !== 'admin') {

    header('Location:' . URL_PREFIX . '/dashboard/login.php');
    exit();
}




$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


if (!$search_string) {
    $search_string = '';
}

if (!$filter_col) {
    $filter_col = 'id';
}


$db = getDbInstance();
$select = array('id', 'jumlah', 'email', 'resellername', 'created_at', 'ispay');
function obfuscate_email($email)
{
    $em = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

if ($search_string) {


    $db->where('resellername', '%' . $search_string . '%', 'like')->where('ispay', '0');
} else {

    $db->where('ispay', '0');
}

$db->orderBy("id", "desc");

// Get all rows for DataTables client-side processing
$rows = $db->get('penjualancredit', null, implode(',', $select));

// Collect unique statuses for filter
$statusOptions = array_unique(array_column($rows, 'ispay'));
sort($statusOptions);

include './includes/admin_header.php';
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
    #bayarTable {
        width: 100% !important;
    }

    #bayarTable thead th {
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
        #bayarTable thead th {
            background-color: #1e293b;
        }
    }

    #bayarTable tbody td {
        text-align: center !important;
        vertical-align: middle !important;
    }

    #bayarTable thead th.sorting:after,
    #bayarTable thead th.sorting_asc:after,
    #bayarTable thead th.sorting_desc:after {
        opacity: 0.3;
    }

    #bayarTable thead th.sorting_asc:after,
    #bayarTable thead th.sorting_desc:after {
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
    #bayarTable .action-column {
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

<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">AZEGSM Reseller Sold Records</h1>
    </div>
    <?php include './includes/flash_messages.php';
    ?>

    <?php
    if (isset($del_stat) && $del_stat == 1) {
        echo '<div class="alert alert-info">Successfully deleted</div>';



    }



    ?>

    <!-- Filters -->


    <!-- //Filters -->

    <!-- Table -->


    <div class="well text-center filter-form">
        <form class="form form-inline flex items-center gap-4 w-1/3 mx-auto" action="">
            <label for="input_search" class="mb-0">Search</label>
            <input type="text" class="form-control" id="input_search" name="search_string"
                value="<?php echo htmlspecialchars($search_string, ENT_QUOTES, 'UTF-8'); ?>">

            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>

    <div>
        <table id="bayarTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="column-names">
                    <th draggable="true">ID<span class="resize-handle"></span></th>
                    <th draggable="true">Name<span class="resize-handle"></span></th>
                    <th draggable="true">Credit Amount<span class="resize-handle"></span></th>
                    <th draggable="true">Reseller Name<span class="resize-handle"></span></th>
                    <th draggable="true">Date<span class="resize-handle"></span></th>
                    <th draggable="true">Paid Off<span class="resize-handle"></span></th>
                    <th class="no-sort action-column">Action<span class="resize-handle"></span></th>
                </tr>
                <tr class="filter-row">
                    <th><input type="number" class="column-filter" data-column="0" placeholder="Filter ID..."></th>
                    <th><input type="text" class="column-filter" data-column="1" placeholder="Filter name..."></th>
                    <th><input type="number" class="column-filter" data-column="2" placeholder="Filter credit..."></th>
                    <th><input type="text" class="column-filter" data-column="3" placeholder="Filter reseller..."></th>
                    <th><input type="text" class="column-filter" data-column="4" placeholder="Filter date..."></th>
                    <th>
                        <select class="column-filter" data-column="5">
                            <option value="">All</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                        </select>
                    </th>
                    <th class="action-column"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalcredit = 0;
                foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php

                        $totalcredit += $row['jumlah'];

                        echo htmlspecialchars($row['jumlah']); ?></td>
                        <td>
                            <?php echo $row['resellername']; ?>

                        </td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['ispay']); ?></td>
                        <td class="action-column">
                            <div class="action-buttons">
                                <a href="edit_penjualan.php?serverid=<?php echo $row['id']; ?>&operation=edit"
                                    class="btn btn-primary btn-sm" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>

                                <a href="#" class="btn btn-danger btn-sm delete_btn" data-toggle="modal"
                                    data-target="#confirm-delete-<?php echo $row['id']; ?>" title="Delete"><i
                                        class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </td>

                    </tr>
                    <div class="modal fade" id="confirm-delete-<?php echo $row['id']; ?>" role="dialog">
                        <div class="modal-dialog">
                            <form action="delete_history_credit.php" method="POST">
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
                    <?php
                    $_SESSION["CREDIT"] = $totalcredit;

                endforeach;
                if ($search_string) {
                    if (!$search_string == "")
                        include BASE_PATH . '/includes/flashadd.php';
                }

                ?>
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
        var table = $('#bayarTable').DataTable({
            colReorder: false,
            responsive: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {
                    targets: 0,
                    width: '10%',
                    orderable: true,
                    type: 'num'
                },
                {
                    targets: 1,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 2,
                    width: '10%',
                    orderable: true,
                    type: 'num'
                },
                {
                    targets: 3,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 4,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 5,
                    width: '10%',
                    orderable: true
                },
                {
                    targets: 6,
                    width: '100px',
                    orderable: false
                }
            ],
            order: [[0, 'desc']],
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                infoEmpty: "Showing 0 to 0 of 0 records",
                infoFiltered: "(filtered from _MAX_ total records)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });

        // Column header sorting
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

        // Prevent sorting on filter row
        $('.filter-row th').on('click', function (e) {
            e.stopPropagation();
        });

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

        // ========== Column Reordering ==========
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
                if (dropIndex < draggedIndex) {
                    $(this).before(draggedColumn);
                } else {
                    $(this).after(draggedColumn);
                }

                const draggedFilter = $('.filter-row th').eq(draggedIndex);
                const dropFilter = $('.filter-row th').eq(dropIndex);

                if (dropIndex < draggedIndex) {
                    dropFilter.before(draggedFilter);
                } else {
                    dropFilter.after(draggedFilter);
                }

                $('#bayarTable tbody tr').each(function () {
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
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>