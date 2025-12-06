<fieldset>
    <!-- Form Name -->
    <legend>Change Xiaomi BD Price Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="id" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo ($edit) ? $admin_account['id'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    

    
     <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">service Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="serviceid" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo ($edit) ? $admin_account['serviceid'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Service Name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                 <input type="text" name="servicename" autocomplete="off" placeholder="Credit Amount " class="form-control" value="<?php echo ($edit) ? $admin_account['servicename'] : ''; ?>" autocomplete="off" >
            </div>
        </div>
    </div>
    
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Price</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                  <input type="text" name="price" autocomplete="off" placeholder="Credit Amount " class="form-control" value="<?php echo ($edit) ? $admin_account['harga'] : ''; ?>" autocomplete="off" >
            </div>
        </div>
    </div>
   
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success" >Update Price Now <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>