<fieldset>
    <div class="form-group ds-form-group">
        <label for="passtoken" class="ds-form-label required">Pass Token</label>
        <div class="input-group ds-input-group">
            <span class="input-group-addon">
                <i class="fa fa-key"></i>
            </span>
            <input type="text" name="passtoken" value="<?php echo htmlspecialchars($edit ? $customer['passtoken'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter pass token" class="form-control ds-input" required="required" id="passtoken">
        </div>
    </div>

    <div class="form-group ds-form-group">
        <label for="uid" class="ds-form-label required">User ID</label>
        <div class="input-group ds-input-group">
            <span class="input-group-addon">
                <i class="fa fa-user"></i>
            </span>
            <input type="text" name="uid" value="<?php echo htmlspecialchars($edit ? $customer['uid'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter user ID" class="form-control ds-input" required="required" id="uid">
        </div>
    </div>

    <div class="form-group ds-form-group">
        <label for="deviceid" class="ds-form-label required">Device ID</label>
        <div class="input-group ds-input-group">
            <span class="input-group-addon">
                <i class="fa fa-mobile"></i>
            </span>
            <input type="text" name="deviceid" value="<?php echo htmlspecialchars($edit ? $customer['deviceid'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter device ID" class="form-control ds-input" required="required" id="deviceid">
        </div>
    </div>

    <div class="form-group ds-form-group">
        <label class="ds-form-label required">Region</label>
        <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
            <label class="ds-radio">
                <input type="radio" name="mihost" value="global" <?php echo ($edit &&$customer['gender'] =='male') ? "checked": "" ; ?> required="required"/>
                <span class="ds-radio-mark"></span>
                Global
            </label>
            <label class="ds-radio">
                <input type="radio" name="mihost" value="in" <?php echo ($edit && $customer['gender'] =='female')? "checked": "" ; ?> required="required" id="mihost"/>
                <span class="ds-radio-mark"></span>
                India
            </label>
            <label class="ds-radio">
                <input type="radio" name="mihost" value="cn" <?php echo ($edit && $customer['gender'] =='female')? "checked": "" ; ?> required="required"/>
                <span class="ds-radio-mark"></span>
                China
            </label>
        </div>
    </div>

    <div class="form-group text-center ds-form-group">
        <button type="submit" class="btn btn-warning ds-btn ds-btn-warning">
            <i class="fa fa-save"></i>
            Save
        </button>
    </div>
</fieldset>
