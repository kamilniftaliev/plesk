<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">AZEGSM User's Informations</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="username" autocomplete="off" placeholder="user name" class="form-control"
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
                    value="<?php echo ($edit) ? $admin_account['credit'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Apikey</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="regBy" autocomplete="off" placeholder="user name" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['apikey'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Old Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" min="0" name="oldpass" autocomplete="off" placeholder="Old Password"
                    class="form-control" value="<?php echo ($edit) ? sha1($admin_account['password']) : ''; ?>"
                    autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">New Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="text" min="0" name="newpass" autocomplete="off" placeholder="New Password "
                    class="form-control" autocomplete="off">
            </div>
        </div>
    </div>





    <div class="form-group">
        <label class="col-md-4 control-label">State</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "admin") { ?>
                    <select name="status" id="status">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="user">user</option>
                        <option value="authid">authid</option>
                    </select>


                <?php } ?>


                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "authid") { ?>
                    <select name="status" id="status">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                    </select>


                <?php } ?>
                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "user") { ?>
                    <select name="status" id="status">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="admin">admin</option>
                        <option value="authid">authid</option>
                    </select>

                    <!--  <input type="text" name="status" autocomplete="off" placeholder="Credit Amount " class="form-control" value=" autocomplete="off" -->

                <?php } ?>




            </div>
        </div>

    </div>


    <div class="form-group">
        <label class="col-md-4 control-label">FRP Packet</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $status = ($edit) ? $admin_account['frp'] : '';
                if ($status == "1") { ?>
                    <select name="frp" id="frp">
                        <option value="<?php echo $status; ?>">YES</option>
                        <option value="0">NO</option>

                    </select>


                <?php } ?>


                <?php
                $status = ($edit) ? $admin_account['frp'] : '';
                if ($status == "0") { ?>
                    <select name="frp" id="frp">
                        <option value="<?php echo $status; ?>">NO</option>
                        <option value="1">YES</option>

                    </select>

                <?php } ?>


            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label">SPECIAL PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <?php
                $price = ($edit) ? $admin_account['price'] : '';
                if ($price == "1") { ?>
                    <select name="price" id="price">
                        <option value="<?php echo $price; ?>">YES</option>
                        <option value="0">NO</option>

                    </select>


                <?php } ?>


                <?php
                $price = ($edit) ? $admin_account['price'] : '';
                if ($price == "0") { ?>
                    <select name="price" id="price">
                        <option value="<?php echo $price; ?>">NO</option>
                        <option value="1">YES</option>

                    </select>

                <?php } ?>


            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">FRP PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="frp_price" autocomplete="off" placeholder="frp price" class="form-control"
                    value="<?php echo ($edit) ? $admin_account['frp_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>



    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">FDL PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="fdl_price" autocomplete="off" placeholder="fdl price " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['fdl_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>


    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Qcom PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="qcom_price" autocomplete="off" placeholder="Qcom price " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['qcom_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">MTK v6 OLD PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="v6_price" autocomplete="off" placeholder="v6 price " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['v6_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">MTK v5 OLD PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="v5_price" autocomplete="off" placeholder="v5 price " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['v5_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">UBL PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="ubl_price" autocomplete="off" placeholder="v6 price " class="form-control"
                    value="<?php echo ($edit) ? $admin_account['ubl_price'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">MTK v6 NEW PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="v6new_price" autocomplete="off" placeholder="v6 new price "
                    class="form-control" value="<?php echo ($edit) ? $admin_account['v6new_price'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">MTK v5 NEW PRICE</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="v5new_price" autocomplete="off" placeholder="v5 new price "
                    class="form-control" value="<?php echo ($edit) ? $admin_account['v5new_price'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Change User's Info Now <span
                    class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>