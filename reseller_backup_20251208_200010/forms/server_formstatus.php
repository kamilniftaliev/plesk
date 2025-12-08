<fieldset>
    <!-- Form Name -->
    <legend>Change AzeGsm Servers Informations</legend>
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
        <label class="col-md-4 control-label" >State</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php 
                $status = ($edit) ? $admin_account['status'] : '';
                    if($status == "OFF" ){     ?>
                    <select name="status" id="status">
                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                    <option value="ON">ON</option>
                   
                </select>
 <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->
                
           <?php    } ?>
                
                
                                <?php 
                $status = ($edit) ? $admin_account['status'] : '';
                    if($status == "ON" ){     ?>
                    <select name="status" id="status">
                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                    <option value="OFF">OFF</option>
                   
                </select>
 <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->
                
           <?php    } ?>
                
                
                
                
            </div>
        </div>
    </div>
    
    

 
    

    
    
   
   
     <div class="form-group">
        <label class="col-md-4 control-label" >Server Support</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                  <input type="text" name="serversupport" autocomplete="off" placeholder="Credit Amount " class="form-control" value="<?php echo ($edit) ? $admin_account['serversupport'] : ''; ?>" autocomplete="off" >
            </div>
        </div>
    </div>
   
   
   
   
   
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success" >Update Server Now <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>