<?php
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'admin') {
    header('HTTP/1.1 401 Unauthorized', true, 401);
    exit('401 Unauthorized');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AH Admin Panel</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="/reseller/assets/css/bootstrap.min.css" />

    <!-- MetisMenu CSS -->
    <link href="/reseller/assets/js/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/reseller/assets/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="/reseller/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="/reseller/assets/js/jquery.min.js" type="text/javascript"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        @media (prefers-color-scheme: dark) {
            .modern-sidebar {
                background-color: #1e293b;
                border-right: 1px solid #374151;
            }
        }

        .modern-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        #page-wrapper {
            padding: 1.5rem !important;
            margin: 0 !important;
            border: none !important;
        }

        #page-wrapper .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }


        h1.page-header {
            font-size: 3rem;
            font-weight: 600;
        }

        a:hover {
            text-decoration: none !important;
        }
    </style>
</head>

<body class="bg-gray-50">

    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true): ?>
        <!-- Modern Sidebar -->
        <div class="modern-sidebar">
            <!-- Logo -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-14 h-14 bg-blue-600 dark:bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-10 h-10 text-white dark:text-slate-800" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01">
                            </path>
                        </svg>
                    </div>
                    <span class="text-gray-900 dark:text-white font-bold text-4xl">Server Admin</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-4 flex-1 overflow-y-auto">
                <!-- Add Servers -->
                <a href="/reseller/add_server.php"
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="font-medium">Add Servers</span>
                </a>

                <!-- Server Edit -->
                <a href="/reseller/servers.php"
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="font-medium">Server Edit</span>
                </a>

                <!-- Server Status (Active) -->
                <a href="/reseller/serverstatus.php"
                    class="flex items-center gap-3 px-4 py-3 bg-blue-50 dark:bg-white text-blue-700 dark:text-slate-800 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Server Status</span>
                </a>

                <!-- Server Patch -->
                <a href="/reseller/serverspatch.php"
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    <span class="font-medium">Server Patch</span>
                </a>

                <!-- Price Setting -->
                <a href="/reseller/price.php"
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="font-medium">Price Setting</span>
                </a>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-200 dark:border-gray-700"></div>

            </nav>

            <!-- User Profile (Bottom - Sticky) -->
            <div class="mt-auto border-t border-gray-200 dark:border-gray-700 p-4">
                <button onclick="toggleUserMenu()"
                    class="flex items-center gap-3 w-full px-3 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        <?php echo strtoupper(substr($_SESSION['name'] ?? 'A', 0, 1)); ?>
                        <?php echo isset($_SESSION['name']) && strlen($_SESSION['name']) > 1 ? strtoupper(substr($_SESSION['name'], 1, 1)) : 'T'; ?>
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-gray-900 dark:text-white font-bold text-xl">
                            <?php echo ucfirst($_SESSION['name'] ?? 'Admin User'); ?>
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 text-lg">
                            <?php echo ($_SESSION['name'] ?? 'admin') . '@email.com'; ?>
                        </div>
                    </div>
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </button>

                <!-- User Dropdown Menu -->
                <div id="user-menu"
                    class="hidden absolute bottom-28 left-4 right-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 transition-colors border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">User Profile</span>
                    </a>
                    <a href="edit_main.php?admin_user_id=<?php echo $_SESSION['admin_id']; ?>&operation=edit"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 transition-colors border-b border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                        <span class="font-medium">Change Password</span>
                    </a>
                    <a href="/reseller/logout.php"
                        class="flex items-center gap-3 px-4 py-3 text-red-600 dark:text-red-400 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="modern-content">
            <div id="wrapper">
            <?php endif; ?>

            <script>
                function toggleUserMenu() {
                    const menu = document.getElementById('user-menu');
                    menu.classList.toggle('hidden');
                }

                // Close user menu when clicking outside
                document.addEventListener('click', function (event) {
                    const menu = document.getElementById('user-menu');
                    const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
                    if (!userButton && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            </script>

            <?php include('updaters.php') ?>