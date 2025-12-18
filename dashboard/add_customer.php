<?php
session_name('DASHBOARD_SESSION');
session_start();
require_once '../includes/auth_validate.php';

// Check permission for this page
requirePermission('add_customer');

//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    $data_to_store = array_filter($_POST);

    //Insert timestamp
    $data_to_store['created_at'] = date('Y-m-d H:i:s');
    $db = getDbInstance();

    $last_id = $db->insert('customers', $data_to_store);

    if ($last_id) {
        $_SESSION['success'] = "Customer added successfully!";
        header('location: customers.php');
        exit();
    } else {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once '../includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">Add Customers</h1>
    </div>
    <div class="form-container-responsive w-full md:w-1/2 mx-auto">
        <form class="form" action="" method="post" id="customer_form" enctype="multipart/form-data">
            <?php include_once('../forms/customer_form.php'); ?>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#customer_form").validate({
            rules: {
                f_name: {
                    required: true,
                    minlength: 3
                },
                l_name: {
                    required: true,
                    minlength: 3
                },
            }
        });
    });
</script>

</div>
<?php include_once '../includes/footer.php'; ?>