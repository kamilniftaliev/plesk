<fieldset>
    <div class="form-group">
        <label for="f_name">passtoken *</label>
          <input type="text" name="passtoken" value="<?php echo htmlspecialchars($edit ? $customer['passtoken'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="passtoken" class="form-control" required="required" id = "passtoken" >
    </div> 

    <div class="form-group">
        <label for="l_name">user id *</label>
        <input type="text" name="uid" value="<?php echo htmlspecialchars($edit ? $customer['uid'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="userid" class="form-control" required="required" id="uid">
    </div> 

    <div class="form-group">
        <label for="deviceid">devices id </label>
        <input type="text" name="deviceid" value="<?php echo htmlspecialchars($edit ? $customer['deviceid'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="deviceid" class="form-control"  id="deviceid">
    </div> 


       <div class="form-group">
        <label for="apiurl">apiurl *</label>
        <input type="text" name="apiurl" value="<?php echo htmlspecialchars($edit ? $customer['apiurl'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="without http default azegsm.com/apimi" class="form-control" required="required" id="apiurl">
        </div> 


    <div class="form-group">
        <label>region * </label>
        <label class="radio-inline">
            <input type="radio" name="mihost" value="global" <?php echo ($edit &&$customer['mihost'] =='global') ? "checked": "" ; ?> required="required"/> Global
        </label>
        <label class="radio-inline">
            <input type="radio" name="mihost" value="in" <?php echo ($edit && $customer['mihost'] =='in')? "checked": "" ; ?> required="required" id="mihost"/> in
        </label>
                <label class="radio-inline">
            <input type="radio" name="mihost" value="cn" <?php echo ($edit && $customer['mihost'] =='cn')? "checked": "" ; ?> required="required" id="mihost"/> cn
        </label>
    </div>

  
  

    <div class="form-group text-center">
        <label></label>
        <button type="submit" class="btn btn-warning" >Save <span class="glyphicon glyphicon-send"></span></button>
    </div>            
</fieldset>
