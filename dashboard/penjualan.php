<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Check permission for this page
requirePermission('penjualan');

require_once BASE_PATH . '/lib/Users/Users.php';
$users = new Users();

// Get current user type for conditional display
$current_user_type = getCurrentUserType();

// Import header
require_once '../includes/header.php';
?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap.min.css">
<!-- Flatpickr CSS for date picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

        select:not(.form-control),
        input[type="text"]:not(.form-control),
        input[type="number"]:not(.form-control) {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #f1f5f9 !important;
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

    /* Prevent sorting on filter row */
    .filter-row th {
        cursor: default !important;
    }

    /* Copy icon styling */
    .copy-icon {
        cursor: pointer;
        margin-left: 8px;
        color: #6b7280;
        font-size: 14px;
        transition: color 0.2s;
    }

    .copy-icon:hover {
        color: #3b82f6;
    }

    @media (prefers-color-scheme: dark) {
        .copy-icon {
            color: #9ca3af;
        }

        .copy-icon:hover {
            color: #60a5fa;
        }
    }

    /* Paid status badges */
    .badge-paid {
        background-color: #10b981;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .badge-unpaid {
        background-color: #f59e0b;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
</style>

<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">Transactions</h1>
    </div>
    <?php include 'includes/flash_messages.php'; ?>

    <!-- Table -->
    <div>
        <table id="transactionsTable" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Credit Amount</th>
                    <th>Reseller Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <?php if ($current_user_type === 'admin'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
                <tr class="filter-row">
                    <th><input type="number" class="column-filter" data-column="0" placeholder="Filter ID..."></th>
                    <th><input type="text" class="column-filter" data-column="1" placeholder="Filter email..."></th>
                    <th><input type="number" class="column-filter" data-column="2" placeholder="Filter amount..."></th>
                    <th><input type="text" class="column-filter" data-column="3" placeholder="Filter reseller..."></th>
                    <th><input type="text" class="column-filter date-filter" id="dateFilter" data-column="4" placeholder="Select date..."></th>
                    <th>
                        <select class="column-filter" data-column="5">
                            <option value="">All</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                    </th>
                    <?php if ($current_user_type === 'admin'): ?>
                        <th></th>
                    <?php endif; ?>
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
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<!-- Flatpickr JS for date picker -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Helper function to get relative time
    function getRelativeTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        const intervals = {
            year: 31536000,
            month: 2592000,
            week: 604800,
            day: 86400,
            hour: 3600,
            minute: 60
        };

        for (let key in intervals) {
            const interval = Math.floor(seconds / intervals[key]);
            if (interval >= 1) {
                return interval + ' ' + key + (interval === 1 ? '' : 's') + ' ago';
            }
        }
        return 'just now';
    }

    // Function to copy text to clipboard
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                // Optional: Show a brief success message
                console.log('Copied to clipboard: ' + text);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
            });
        } else {
            // Fallback for older browsers
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.top = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                console.log('Copied to clipboard: ' + text);
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
            document.body.removeChild(textArea);
        }
    }

    $(document).ready(function () {
        var currentUserType = '<?php echo $current_user_type; ?>';

        // Initialize DataTable with server-side processing
        var columnDefs = [
            { targets: 0, width: '10%', orderable: true, type: 'num' },
            { targets: 1, width: '20%', orderable: true },
            { targets: 2, width: '15%', orderable: true, type: 'num' },
            { targets: 3, width: '20%', orderable: true },
            { targets: 4, width: '15%', orderable: true },
            { targets: 5, width: '10%', orderable: true }
        ];

        var columns = [
            { data: 0, orderable: true },  // ID
            { data: 1, orderable: true },  // Email
            { data: 2, orderable: true },  // Credit Amount
            { data: 3, orderable: true },  // Reseller Name
            {
                data: 4,
                orderable: true,
                render: function (data, type, row) {
                    if (type === 'display') {
                        var relativeTime = getRelativeTime(data);
                        return '<span title="' + data + '">' + relativeTime + '</span>' +
                               '<i class="glyphicon glyphicon-copy copy-icon" onclick="copyToClipboard(\'' + data + '\')" title="Copy exact time"></i>';
                    }
                    return data;
                }
            },  // Date with relative time and copy icon
            {
                data: 5,
                orderable: true,
                render: function (data, type, row) {
                    if (type === 'display') {
                        return data === '1' ? '<span class="badge-paid">Paid</span>' : '<span class="badge-unpaid">Unpaid</span>';
                    }
                    return data === '1' ? 'Paid' : 'Unpaid';
                }
            }  // Status
        ];

        // Add Action column only for admin users
        if (currentUserType === 'admin') {
            columns.push({ data: 6, orderable: false });  // Action
            columnDefs.push({ targets: 6, width: '10%', orderable: false });
        }

        var table = $('#transactionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'penjualan_data.php',
                type: 'GET'
            },
            responsive: false,
            pageLength: 50,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            columns: columns,
            columnDefs: columnDefs,
            order: [[0, 'desc']], // Sort by ID descending
            dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                infoEmpty: "Showing 0 to 0 of 0 transactions",
                infoFiltered: "(filtered from _MAX_ total transactions)",
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

        // Prevent sorting on filter row
        $('.filter-row th').on('click', function(e) {
            e.stopPropagation();
        });

        // Initialize Flatpickr for date filter
        flatpickr("#dateFilter", {
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                table.column(4).search(dateStr).draw();
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (!dateStr) {
                    table.column(4).search('').draw();
                }
            }
        });

        // Custom column filters (excluding date filter which is handled by Flatpickr)
        $('.column-filter').not('.date-filter').on('keyup change', function () {
            var columnIndex = $(this).data('column');
            var searchValue = this.value;
            table.column(columnIndex).search(searchValue).draw();
        });
    });
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>
