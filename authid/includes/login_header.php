<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Authid Login</title>

        <!-- Bootstrap Core CSS -->
        <link  rel="stylesheet" href="/authid/assets/css/bootstrap.min.css"/>

        <!-- MetisMenu CSS -->
        <link href="/authid/assets/js/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="/authid/assets/css/sb-admin-2.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="/authid/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="/authid/assets/js/jquery.min.js" type="text/javascript"></script>

        <style>
            @media (prefers-color-scheme: dark) {
                body {
                    background-color: #0f172a !important;
                    color: #f1f5f9 !important;
                }

                /* Panel */
                .panel {
                    background-color: #1e293b !important;
                    border-color: #374151 !important;
                }

                .panel-heading {
                    background-color: #1e293b !important;
                    border-color: #374151 !important;
                    color: #f1f5f9 !important;
                }

                .panel-body {
                    background-color: #1e293b !important;
                    color: #f1f5f9 !important;
                }

                /* Form elements */
                .form-control {
                    background-color: #1e293b !important;
                    border-color: #374151 !important;
                    color: #f1f5f9 !important;
                }

                .form-control:focus {
                    background-color: #1e293b !important;
                    border-color: #60a5fa !important;
                    color: #f1f5f9 !important;
                }

                .form-control::placeholder {
                    color: #9ca3af !important;
                }

                /* Labels */
                label,
                .control-label {
                    color: #f1f5f9 !important;
                }

                /* Checkbox */
                .checkbox label {
                    color: #f1f5f9 !important;
                }

                /* Buttons */
                .btn-success {
                    background-color: #16a34a !important;
                    border-color: #16a34a !important;
                    color: #ffffff !important;
                }

                .btn-success:hover,
                .btn-success:focus,
                .btn-success:active {
                    background-color: #15803d !important;
                    border-color: #15803d !important;
                    color: #ffffff !important;
                }

                /* Links */
                a {
                    color: #60a5fa !important;
                }

                a:hover {
                    color: #93c5fd !important;
                    text-decoration: none !important;
                }

                /* Alerts */
                .alert-danger {
                    background-color: #991b1b !important;
                    color: #fecaca !important;
                    border-color: #ef4444 !important;
                }

                .alert .close {
                    color: #fecaca !important;
                    opacity: 0.8;
                }

                .alert .close:hover {
                    opacity: 1;
                }
            }
        </style>

    </head>

    <body>
