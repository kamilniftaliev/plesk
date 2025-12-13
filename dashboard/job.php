<?php
include "/include/header.php";
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

// Predefined service and status options for filters (server-side processing)
$serviceOptions = [
    '1' => 'EDL',
    '2' => 'FRP',
    '4' => 'FDL',
    '6' => 'MTK 5',
    '9' => 'MTK 6 new'
];

// Common status values
$statusOptions = ['done', 'failed', 'error', 'pending'];

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

        /* Style selects and date inputs that don't have form-control class */
        select:not(.form-control),
        input[type="date"]:not(.form-control),
        input[type="text"]:not(.form-control),
        input[type="submit"]:not(.btn) {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #f1f5f9 !important;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #374151;
            color-scheme: dark;
        }

        select:not(.form-control):focus,
        input[type="date"]:not(.form-control):focus,
        input[type="text"]:not(.form-control):focus {
            background-color: #1e293b !important;
            border-color: #60a5fa !important;
            color: #f1f5f9 !important;
            outline: none;
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

    /* Improved status colors for light and dark mode */
    .status-failed,
    .status-error,
    .status-done,
    .status-pending,
    .status-request {
        color: white;
        padding: 8px 12px;
        font-weight: 500;
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;

    }

    .status-done {
        background-color: #10b981;
    }

    .status-failed {
        background-color: #a35117;
    }

    .status-error {
        background-color: #d12424;
    }

    .status-pending {
        background-color: #f59e0b;
    }

    .status-request {
        background-color: #7c7c7c;
    }

    /* Copy button styling */
    .copy-btn {
        padding: 4px 8px;
        font-size: 12px;
        margin-left: 6px;
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.2s;
        position: absolute;
        right: 0;
        top: -2px;
    }

    .copy-btn:hover {
        background-color: #2563eb;
    }

    .copy-btn i {
        font-size: 11px;
    }

    @media (prefers-color-scheme: dark) {
        .copy-btn {
            background-color: #2563eb;
        }

        .copy-btn:hover {
            background-color: #1d4ed8;
        }
    }

    /* Filter input wrapper with clear button */
    .filter-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-wrapper input {
        /* padding-right: 10px !important; */
        flex: 1;
    }

    .clear-filter-btn {
        position: absolute;
        right: 8px;
        background: transparent;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0;
        width: 16px;
        height: 16px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        line-height: 1;
    }

    .clear-filter-btn:hover {
        color: #ef4444;
    }

    .filter-input-wrapper.has-value .clear-filter-btn {
        display: flex;
    }

    @media (prefers-color-scheme: dark) {
        .clear-filter-btn {
            color: #9ca3af;
        }

        .clear-filter-btn:hover {
            color: #ef4444;
        }
    }

    /* Responsive improvements */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 10px 0;
    }

    .filter-row th {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .filter-row th:after,
    .filter-row th:before {
        display: none !important;
    }

    td.p-0 {
        padding: 0 !important;
        position: relative;
    }

    .align-middle {
        vertical-align: middle;
    }
</style>

<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">Server Requesting History</h1>
    </div>
    <?php include './includes/flash_messages.php'; ?>

    <!-- Table -->
    <div>
        <table id="jobHistoryTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="column-names">
                    <th draggable="true">No<span class="resize-handle"></span></th>
                    <th draggable="true">ID<span class="resize-handle"></span></th>
                    <th draggable="true">ID User<span class="resize-handle"></span></th>
                    <th draggable="true">Username<span class="resize-handle"></span></th>
                    <th draggable="true">Config Blob<span class="resize-handle"></span></th>
                    <th draggable="true">Auth Type<span class="resize-handle"></span></th>
                    <th draggable="true">Server ID<span class="resize-handle"></span></th>
                    <th draggable="true">Response<span class="resize-handle"></span></th>
                    <th draggable="true">Cost<span class="resize-handle"></span></th>
                    <th draggable="true">Time<span class="resize-handle"></span></th>
                    <th draggable="true">Status<span class="resize-handle"></span></th>
                </tr>
                <tr class="filter-row">
                    <th class="text-center"></th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="number" class="column-filter" data-column="1" placeholder="Filter ID...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="number" class="column-filter" data-column="2" placeholder="Filter user ID...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="text" class="column-filter" data-column="3" placeholder="Filter username...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="text" class="column-filter" data-column="4" placeholder="Filter config...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <select class="column-filter" data-column="5">
                            <option value="">All Types</option>
                            <?php foreach ($serviceOptions as $key => $value): ?>
                                <option value="<?php echo htmlspecialchars($value); ?>">
                                    <?php echo htmlspecialchars($value); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="text" class="column-filter" data-column="6" placeholder="Filter server...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="text" class="column-filter" data-column="7" placeholder="Filter response...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="number" class="column-filter" data-column="8" placeholder="Filter cost...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="filter-input-wrapper">
                            <input type="date" class="column-filter" data-column="9" placeholder="Filter time...">
                            <button class="clear-filter-btn" type="button">&times;</button>
                        </div>
                    </th>
                    <th class="align-middle text-center">
                        <select class="column-filter" data-column="10">
                            <option value="">All Statuses</option>
                            <?php foreach ($statusOptions as $status): ?>
                                <option value="<?php echo htmlspecialchars(ucfirst($status)); ?>">
                                    <?php echo htmlspecialchars(ucfirst($status)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via Ajax -->
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
        // Initialize DataTable with server-side processing
        var table = $('#jobHistoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'job_data.php',
                type: 'GET'
            },
            colReorder: false,
            responsive: false,
            pageLength: 50,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            columns: [
                { data: 0, orderable: true },  // No
                { data: 1, orderable: true },  // ID
                { data: 2, orderable: true },  // ID User
                { data: 3, orderable: true },  // Username
                { data: 4, orderable: true },  // Config Blob
                { data: 5, orderable: true },  // Auth Type
                { data: 6, orderable: true },  // Server ID
                { data: 7, orderable: true },  // Response
                { data: 8, orderable: true },  // Cost
                { data: 9, orderable: true },  // Time
                { data: 10, orderable: true }  // Status
            ],
            columnDefs: [
                { targets: 0, width: '2%', type: 'num' },
                { targets: 1, width: '3%', type: 'num' },
                { targets: 2, width: '3%', type: 'num' },
                { targets: 3, width: '7%' },
                { targets: 4, width: '7%' },
                { targets: 5, width: '4%' },
                { targets: 6, width: '5%' },
                { targets: 7, width: '5%' },
                { targets: 8, width: '4%', type: 'num' },
                { targets: 9, width: '5%' },
                { targets: 10, width: '5%', class: "p-0" }
            ],
            order: [[1, 'desc']], // Sort by ID descending
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ jobs",
                infoEmpty: "Showing 0 to 0 of 0 jobs",
                infoFiltered: "(filtered from _MAX_ total jobs)",
                loadingRecords: "Loading...",
                processing: "Processing...",
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

            // Update clear button visibility
            var wrapper = $(this).closest('.filter-input-wrapper');
            if (wrapper.length) {
                if (searchValue) {
                    wrapper.addClass('has-value');
                } else {
                    wrapper.removeClass('has-value');
                }
            }

            table.column(columnIndex).search(searchValue).draw();
        });

        // Clear filter button functionality
        $('.clear-filter-btn').on('click', function (e) {
            e.stopPropagation();
            var wrapper = $(this).closest('.filter-input-wrapper');
            var input = wrapper.find('.column-filter');
            input.val('');
            wrapper.removeClass('has-value');
            var columnIndex = input.data('column');
            table.column(columnIndex).search('').draw();
        });

        // Copy button functionality
        $(document).on('click', '.copy-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var textToCopy = $(this).data('text');
            var btn = $(this);

            navigator.clipboard.writeText(textToCopy).then(function () {
                var originalHtml = btn.html();
                btn.html('<i class="glyphicon glyphicon-ok"></i>');
                btn.css('background-color', '#10b981');

                setTimeout(function () {
                    btn.html(originalHtml);
                    btn.css('background-color', '');
                }, 1000);
            }).catch(function (err) {
                console.error('Failed to copy text: ', err);
            });
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
                $('#jobHistoryTable tbody tr').each(function () {
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