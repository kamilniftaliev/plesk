<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Change AzeGsm Servers Informations</legend>
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
        <label class="col-md-4 control-label">State</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "OFF") { ?>
                    <select name="status" id="status">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="ON">ON</option>

                    </select>
                    <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->

                <?php } ?>


                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "ON") { ?>
                    <select name="status" id="status">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="OFF">OFF</option>

                    </select>
                    <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->

                <?php } ?>




            </div>
        </div>
    </div>


    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Passtoken</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="passtoken" autocomplete="off" placeholder="passtoken" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['passtoken'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">uid</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="uid" autocomplete="off" placeholder="uid" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['uid'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>



    <div class="form-group">
        <label class="col-md-4 control-label">devices Id</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="deviceid" autocomplete="off" placeholder="devices id" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['deviceid'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Region</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost">
                        <option value="<?php echo $region; ?>">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>
                    </select>


                <?php } ?>


                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "in-unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost">
                        <option value="<?php echo $region; ?>">IN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>

                    </select>

                <?php } ?>

                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "unlock.update.miui.com") { ?>
                    <select name="mihost" id="mihost">
                        <option value="<?php echo $region; ?>">CN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>
                    </select>

                <?php } ?>

                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "eu-unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost">
                        <option value="<?php echo $region; ?>">EU</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>

                    </select>

                <?php } ?>


            </div>
        </div>
    </div>



    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">api mi</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="apiurl" autocomplete="off" placeholder="api mi" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['apiurl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>




    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Limit Flash</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="limitflash" autocomplete="off" placeholder="user name" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['limitedl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Limit Frp</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" name="limitfrp" autocomplete="off" placeholder="Credit Amount " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['limitfrp'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Limit Fdl</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" name="limitfdl" autocomplete="off" placeholder="Credit Amount " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['limitfdl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label">Server Support</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" name="serversupport" autocomplete="off" placeholder="Credit Amount "
                    class="form-control" value="<?php echo ($edit) ? $admin_account['serversupport'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>





    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Update Server Now <span
                    class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>