<fieldset>
    <!-- Form Name -->
    <legend>Change AZEGSM user's Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="user_name" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo $name; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
    
    <div class="form-group">
        <label class="col-md-4 control-label" >Enter Old Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="oldpassword" autocomplete="off" placeholder="New Password " class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>
    
    
    
    
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Enter New Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="newpassword" autocomplete="off" placeholder="New Password " class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>
    
    
    
    
    <!-- radio checks -->

    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-warning" >Save <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>