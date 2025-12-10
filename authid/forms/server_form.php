<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Change AzeGsm Servers Informations</legend>

    <!-- ID Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">ID</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                <input type="text" name="id" autocomplete="off" placeholder="ID" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['id'] : ''; ?>" autocomplete="off" readonly>
            </div>
        </div>
    </div>

    <!-- Status Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">State</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-info-circle"></i></span>
                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "OFF") { ?>
                    <select name="status" id="status" class="form-control ds-select">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="ON">ON</option>
                    </select>
                <?php } ?>

                <?php
                $status = ($edit) ? $admin_account['status'] : '';
                if ($status == "ON") { ?>
                    <select name="status" id="status" class="form-control ds-select">
                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                        <option value="OFF">OFF</option>
                    </select>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Passtoken Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Passtoken</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="text" name="passtoken" autocomplete="off" placeholder="Passtoken" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['passtoken'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- UID Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">UID</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="uid" autocomplete="off" placeholder="User ID" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['uid'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Device ID Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Device ID</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                <input type="text" name="deviceid" autocomplete="off" placeholder="Device ID" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['deviceid'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Region Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Region</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost" class="form-control ds-select">
                        <option value="<?php echo $region; ?>">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>
                    </select>
                <?php } ?>

                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "in-unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost" class="form-control ds-select">
                        <option value="<?php echo $region; ?>">IN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>
                    </select>
                <?php } ?>

                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "unlock.update.miui.com") { ?>
                    <select name="mihost" id="mihost" class="form-control ds-select">
                        <option value="<?php echo $region; ?>">CN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>
                        <option value="eu-unlock.update.intl.miui.com">EU</option>
                    </select>
                <?php } ?>

                <?php
                $region = ($edit) ? $admin_account['mihost'] : '';
                if ($region == "eu-unlock.update.intl.miui.com") { ?>
                    <select name="mihost" id="mihost" class="form-control ds-select">
                        <option value="<?php echo $region; ?>">EU</option>
                        <option value="unlock.update.miui.com">CN</option>
                        <option value="unlock.update.intl.miui.com">GLOBAL</option>
                        <option value="in-unlock.update.intl.miui.com">IN</option>
                    </select>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- API MI Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">API MI</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-code"></i></span>
                <input type="text" name="apiurl" autocomplete="off" placeholder="API MI URL" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['apiurl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Limit Flash Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Limit Flash</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-flash"></i></span>
                <input type="text" name="limitflash" autocomplete="off" placeholder="Flash limit" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['limitedl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Limit FRP Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Limit FRP</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="text" name="limitfrp" autocomplete="off" placeholder="FRP limit" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['limitfrp'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Limit FDL Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Limit FDL</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-download"></i></span>
                <input type="text" name="limitfdl" autocomplete="off" placeholder="FDL limit" class="form-control ds-input"
                    value="<?php echo ($edit) ? $admin_account['limitfdl'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Server Support Field -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label ds-form-label">Server Support</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group ds-input-group">
                <span class="input-group-addon"><i class="fa fa-server"></i></span>
                <input type="text" name="serversupport" autocomplete="off" placeholder="Server support info"
                    class="form-control ds-input" value="<?php echo ($edit) ? $admin_account['serversupport'] : ''; ?>"
                    autocomplete="off">
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group ds-form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success ds-btn ds-btn-success">
                <i class="fa fa-save"></i>
                Update Server Now
            </button>
        </div>
    </div>
</fieldset>
