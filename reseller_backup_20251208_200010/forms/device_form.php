<fieldset>
    <div class="form-group">

		
							
    <div class="form-group">
          <input type="text" name="username" value="<?php echo htmlspecialchars($edit ? $customer['username'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Username" class="form-control" required="required" id = "password" >
    </div> 

    <div class="form-group">
        <input type="text" name="password" value="<?php echo htmlspecialchars($edit ? $customer['password'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Password" class="form-control" required="required" id="password">
    </div> 

    

    <div class="form-group">
          <input name="email" placeholder="Email" class="form-control" id="email"><?php echo htmlspecialchars(($edit) ? $customer['email'] : '', ENT_QUOTES, 'UTF-8'); ?></input>
    </div> 
	
	

    <div class="form-group text-center">
        <button type="submit" class="btn btn-info" >Add Activation <span class="glyphicon glyphicon-plus"></span></button>
    </div>            
</fieldset>
