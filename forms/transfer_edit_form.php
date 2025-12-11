<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Change Xiaomi BD Auth Admin's Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="user_name" autocomplete="off" placeholder="user name" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['username'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Current Balance</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="camount" autocomplete="off" placeholder="Credit Amount " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['credit'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Enter Amount to Fill</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="number" min="0" name="famount" autocomplete="off" placeholder="Credit Amount "
                    class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Add Credit Now <span
                    class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>