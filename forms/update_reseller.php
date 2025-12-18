<fieldset>
    <!-- Form Name -->
    <legend class="dark:text-white">Add new reseller</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" name="user_name" autocomplete="off" placeholder="User name" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Email</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input type="text" name="email" autocomplete="off" placeholder="Email" class="form-control">
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="password" autocomplete="off" placeholder="Password" class="form-control">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-4 control-label">Amount</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input type="text" name="amount" autocomplete="off" placeholder="Amount" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">Count</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                <input type="text" name="count" autocomplete="off" placeholder="Activaion Count" class="form-control">
            </div>
        </div>
    </div>
    <!-- radio checks -->
    <!-- <div class="form-group">
        <label class="col-md-4 control-label">Reseller Type</label>
        <div class="col-md-4">
            <div class="radio">
                <label>

                    <input type="radio" name="admin_type" value="reseller" required="" /> World Wide
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="admin_type" value="admin" required="" /> Local
                </label>
            </div>
        </div>
    </div> -->
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Add New Reseller Now <span
                    class="glyphicon glyphicon-plus-sign"></span></button>
        </div>
    </div>
</fieldset>