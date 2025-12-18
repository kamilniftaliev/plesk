<!-- Add New -->
<div class="modal fade" id="chinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h4 class="modal-title" id="myModalLabel">Change reseller Password</h4></center>
            </div>
            <div class="modal-body">
			<div class="container-fluid">
			<form method="POST" action="changepass.php">
				<div class="row form-group">
					<div class="col-sm-10">
						<input type="password" class="form-control" name="cpass" placeholder="Current Password" required>
					</div>
				</div>
				
				<div class="row form-group">
					<div class="col-sm-10">
						<input type="password" class="form-control" name="npass" placeholder="New Password" required>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-10">
						<input type="password" class="form-control" name="vpass" placeholder="Confirm New Password" required>
					</div>
				</div>
            </div> 
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                <button type="submit" name="chpass" class="btn btn-info"><span class="glyphicon glyphicon-ok-circle"></span> Change Now</a>
			</form>
            </div>

        </div>
    </div>
</div>