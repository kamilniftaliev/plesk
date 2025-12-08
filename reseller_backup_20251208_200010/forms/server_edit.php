<fieldset>
    <!-- Form Name -->
    <legend>Change Xiaomi AzeGsm Auth User's Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="user_name" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo ($edit) ? $admin_account['id'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Current Balance</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="camount" autocomplete="off" placeholder="Credit Amount " class="form-control" value="<?php echo ($edit) ? $admin_account['credit'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
     <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Credit Sold By</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="regBy" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo ($edit) ? $admin_account['regBy'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Old Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" min="0" name="oldpass" autocomplete="off" placeholder="Old Password" class="form-control" value="<?php echo ($edit) ? sha1($admin_account['password']) : ''; ?>"autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >New Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" min="0" name="newpass" autocomplete="off" placeholder="New Password " class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>
   
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success" >Change User's Info Now <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>