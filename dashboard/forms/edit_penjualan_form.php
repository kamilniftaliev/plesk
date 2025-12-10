<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Change Vegito Sold Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="id" autocomplete="off" placeholder="user name" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['id'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Is Pay</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $ispay = ($edit) ? $admin_account['ispay'] : '';
                if ($ispay == 1) { ?>
                    <select name="ispay" id="ispay">
                        <option value="1">PAID</option>
                        <option value="0">UNPAID</option>

                    </select>
                    <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->

                <?php } ?>


                <?php
                $ispay = ($edit) ? $admin_account['ispay'] : '';
                if ($ispay == "0") { ?>
                    <select name="ispay" id="ispay">
                        <option value="0">UNPAID</option>
                        <option value="1">PAID</option>

                    </select>
                    <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->

                <?php } ?>




            </div>
        </div>
    </div>






    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Update PAID Now <span
                    class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>