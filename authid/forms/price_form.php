<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Change Xiaomi BD Price Informations</legend>
    <!-- Text input-->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                <input type="text" name="id" autocomplete="off" placeholder="user name" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['id'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>



    <!-- Text input-->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">service Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                <input type="text" name="serviceid" autocomplete="off" placeholder="user name"
                    class="form-control ds-input" value="<?php echo ($edit) ? $admin_account['serviceid'] : ''; ?>"
                    autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Service Name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                <input type="text" name="servicename" autocomplete="off" placeholder="Credit Amount "
                    class="form-control ds-input" value="<?php echo ($edit) ? $admin_account['servicename'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Price</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                <input type="text" name="price" autocomplete="off" placeholder="Credit Amount "
                    class="form-control ds-input" value="<?php echo ($edit) ? $admin_account['harga'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success ds-btn ds-btn-success">Update Price Now <span
                    class="fa fa-send"></span></button>
        </div>
    </div>
</fieldset>