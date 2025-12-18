<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied - AZEGSM</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #0f172a;
                color: #f1f5f9;
            }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 text-center">
            <!-- Icon -->
            <div class="flex justify-center">
                <div class="w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-6xl font-bold text-gray-900 dark:text-white">403</h1>
                <h2 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Access Denied</h2>
            </div>

            <!-- Message -->
            <div class="mt-4">
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Sorry, you don't have permission to access this page.
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                    <?php
                    if (isset($_SESSION['permission_page_name'])) {
                        echo 'The page "' . htmlspecialchars($_SESSION['permission_page_name']) . '" is restricted to ';

                        $user_type = getCurrentUserType();
                        if ($user_type === 'admin') {
                            echo 'administrators only.';
                        } elseif ($user_type === 'reseller') {
                            echo 'users with higher privileges.';
                        } else {
                            echo 'authorized users only.';
                        }
                        unset($_SESSION['permission_page_name']);
                    } else {
                        echo 'This content is restricted to authorized users only.';
                    }
                    ?>
                </p>
            </div>

            <!-- User Info -->
            <?php
            $is_logged_in = (isset($_SESSION['dashboard_user_logged_in']) && $_SESSION['dashboard_user_logged_in']) ||
                           (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']);
            ?>
            <?php if ($is_logged_in): ?>
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span class="text-sm text-blue-800 dark:text-blue-300">
                        Logged in as:
                        <strong><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></strong>
                        (<?php echo htmlspecialchars(ucfirst(getCurrentUserType())); ?>)
                    </span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="mt-8 space-y-3">
                <?php
                $url_prefix = defined('URL_PREFIX') ? URL_PREFIX : '';
                $url_prefix = $url_prefix ?: '';
                ?>

                <a href="<?php echo $url_prefix; ?>/dashboard/index.php"
                   class="block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Go to Dashboard
                </a>

                <button onclick="window.history.back()"
                        class="block w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                    Go Back
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-8 text-sm text-gray-500 dark:text-gray-500">
                <p>Need access to this page?</p>
                <p class="mt-1">Contact your administrator to request appropriate permissions.</p>
            </div>
        </div>
    </div>
</body>
</html>
