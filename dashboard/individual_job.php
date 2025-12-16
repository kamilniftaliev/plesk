<?php
include "../include/header.php";
session_name('DASHBOARD_SESSION');
session_start();
require_once '../config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';


require_once BASE_PATH . '/lib/Users/Users.php';
$users = new Users();

$admin_user_id = $_SESSION['admin_id'];
$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');


$search_string = filter_input(INPUT_GET, 'search_string');
$filter_col = filter_input(INPUT_GET, 'filter_col');
$serviceid = filter_input(INPUT_GET, 'serviceid');
$tanggal = filter_input(INPUT_GET, 'tanggal');
$paid = filter_input(INPUT_GET, 'paid');


if (!$search_string) {
    $search_string = $admin_user_id;
}
if (!$serviceid) {
    $serviceid = '';
}

if (!$filter_col) {
    $filter_col = 'id';
}


$db = getDbInstance();
$select = array('id', 'name', 'iduser', 'configblob', 'serviceid', 'tgl', 'status', 'serverid', 'cost');


if ($search_string) {
    if ($admin_user_id) {
        $db->where('iduser', $admin_user_id);
    }

    if ($serviceid) {
        $db->where('serviceid', $serviceid);
    }

    $where3 = "";
    if ($paid !== null) {
        if ($paid == 0) {
            $where3 = "cost=0";
        } else {
            $where3 = "cost!=0";
        }

        $db->where($where3);
    }

    if ($tanggal) {
        $db->where("tgl >='" . $tanggal . "'");
    }
}



$db->orderBy("id", "desc");

// Get all rows for DataTables client-side processing
$rows = $db->get('data', null, implode(',', $select));


if ($_SESSION['admin_type'] == 'user') {
    require_once '../includes/user_header.php';
}
if ($_SESSION['admin_type'] == 'admin') {
    require_once '../includes/header.php';
}
if ($_SESSION['admin_type'] == 'reseller') {
    require_once '../includes/reseller_header.php';
}



?>
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
    #individualJobTable {
        width: 100% !important;
    }

    #individualJobTable thead th {
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
        #individualJobTable thead th {
            background-color: #1e293b;
        }
    }

    #individualJobTable tbody td {
        text-align: center !important;
        vertical-align: middle !important;
    }

    #individualJobTable thead th.sorting:after,
    #individualJobTable thead th.sorting_asc:after,
    #individualJobTable thead th.sorting_desc:after {
        opacity: 0.3;
    }

    #individualJobTable thead th.sorting_asc:after,
    #individualJobTable thead th.sorting_desc:after {
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

    /* Responsive improvements */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 10px 0;
    }

    .filter-row th:after,
    .filter-row th:before {
        display: none !important;
    }

    .dataTables_empty {
        padding: 20px !important;
    }
</style>

<!-- Main container -->
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">Job Auth History</h1>
    </div>
    <?php include './includes/flash_messages.php'; ?>

    <?php
    if (isset($del_stat) && $del_stat == 1) {
        echo '<div class="alert alert-info">Successfully deleted</div>';
    }
    ?>

    <!-- Filters -->
    <div class="well text-center filter-form">
        <form class="form form-inline flex items-center gap-4 mx-auto w-1/2" action="">
            <label for="input_search" class="mb-0">Search</label>


            <select name="serviceid" id="serviceid">
                <option value="1">flash</option>
                <option value="6">mtk5</option>
                <option value="2">frp</option>
                <option value="4">fdl</option>

            </select>




            <input type="date" id="tanggal" name="tanggal">
            <select name="paid" id="paid">
                <option value="1">paid</option>
                <option value="0">free</option>


            </select>
            <input type="submit" value="Go" class="btn btn-primary">
        </form>
    </div>


    <div>
        <table id="individualJobTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="column-names">
                    <th draggable="true">ID<span class="resize-handle"></span></th>
                    <th draggable="true">ID User<span class="resize-handle"></span></th>
                    <th draggable="true">Configblob<span class="resize-handle"></span></th>
                    <th draggable="true">Auth Type<span class="resize-handle"></span></th>
                    <th draggable="true">Server ID<span class="resize-handle"></span></th>
                    <th draggable="true">Response<span class="resize-handle"></span></th>
                    <th draggable="true">Cost<span class="resize-handle"></span></th>
                    <th draggable="true">Time<span class="resize-handle"></span></th>
                    <th draggable="true">Status<span class="resize-handle"></span></th>
                </tr>
                <tr class="filter-row">
                    <th><input type="number" class="column-filter" data-column="0" placeholder="Filter ID..."></th>
                    <th><input type="text" class="column-filter" data-column="1" placeholder="Filter user..."></th>
                    <th><input type="text" class="column-filter" data-column="2" placeholder="Filter configblob...">
                    </th>
                    <th>
                        <select class="column-filter" data-column="3">
                            <option value="">All Types</option>
                            <option value="EDL">EDL</option>
                            <option value="FRP">FRP</option>
                            <option value="MTK">MTK</option>
                        </select>
                    </th>
                    <th><input type="text" class="column-filter" data-column="4" placeholder="Filter server..."></th>
                    <th></th>
                    <th><input type="number" class="column-filter" data-column="6" placeholder="Filter cost..."></th>
                    <th><input type="text" class="column-filter" data-column="7" placeholder="Filter time..."></th>
                    <th>
                        <select class="column-filter" data-column="8">
                            <option value="">All Status</option>
                            <option value="done">done</option>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['iduser']); ?></td>
                        <td><?php
                        $blobkey = $row['configblob'];
                        echo substr($blobkey, 0, 10);

                        if ($row['serviceid'] == "1") { ?></td>
                            <td><?php echo "EDL";
                        } ?></td>
                        <?php
                        if ($row['serviceid'] == "2") { ?></td>
                            <td><?php echo "FRP";
                        } ?></td>
                        <?php
                        if ($row['serviceid'] == "4") { ?></td>
                            <td><?php echo "MTK";
                        } ?></td>

                        <?php
                        if ($row['serviceid'] == "8") { ?></td>
                            <td><?php echo "FLASH MTK NEW";
                        } ?></td>

                        <?php
                        if ($row['serviceid'] == "10") { ?></td>
                            <td><?php echo "FLASH MTK MALACHITE";
                        } ?></td>

                        <?php
                        if ($row['serviceid'] == "9") { ?></td>
                            <td><?php echo "FLASH MTK 6 OLD";
                        } ?></td>


                        <td><?php echo "Server " . $row['serverid']; ?></td>

                        <td>Hidden</td>
                        <td><?php echo $row['cost']; ?></td>
                        <?php
                        $dateTime = new DateTime($row['tgl']);
                        $timestamp = $dateTime->getTimestamp();
                        $dhakaTimeZone = new DateTimeZone('Asia/jakarta');
                        $dateTimeDhaka = new DateTime();
                        $dateTimeDhaka->setTimestamp($timestamp);
                        $dateTimeDhaka->setTimezone($dhakaTimeZone);
                        $convertedDate = date('F j, Y h:i A', $timestamp);

                        ?>
                        <td>
                            <?php echo $dateTimeDhaka->format('F j, Y h:i A'); ?>

                        </td>
                        <?php
                        if (htmlspecialchars($row['status']) == 'done') {
                            ?>
                            <td style="background-color:green;color:white;"> <?php echo 'done'; ?> </td>
                            <?php
                        } else {
                            ?>
                            <td style="background-color:red;color:white;"><?php echo htmlspecialchars($row['status']); ?> </td>
                            <?php
                        }
                        ?>

                    </tr>
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
        var table = $('#individualJobTable').DataTable({
            colReorder: false,
            responsive: false,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {
                    targets: 0,
                    width: '5%',
                    orderable: true,
                    type: 'num'
                },
                {
                    targets: 1,
                    width: '8%',
                    orderable: true
                },
                {
                    targets: 2,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 3,
                    width: '8%',
                    orderable: true
                },
                {
                    targets: 4,
                    width: '7%',
                    orderable: true
                },
                {
                    targets: 5,
                    width: '8%',
                    orderable: true
                },
                {
                    targets: 6,
                    width: '8%',
                    orderable: true,
                    type: 'num'
                },
                {
                    targets: 7,
                    width: '20%',
                    orderable: true
                },
                {
                    targets: 8,
                    width: '8%',
                    orderable: true
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

                $('#individualJobTable tbody tr').each(function () {
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