    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>">
            <?php echo ucfirst($this->uri->segment(2));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <a href="#">New</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Adding <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
      </div>

      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new account created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Error!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      $options_user_type = array(0 => "Member", 1 => "Admin");

      //form validation
      echo validation_errors();
      
      echo form_open('admin/accounts/add', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Firstname</label>
            <div class="controls">
              <input type="text" id="" name="first_name" value="" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Lastname</label>
            <div class="controls">
              <input type="text" id="" name="last_name" value="" >
            </div>
          </div>		  
          <div class="control-group">
            <label for="inputError" class="control-label">Username</label>
            <div class="controls">
              <input type="text" id="" name="username" value="" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="email_address" value="">
            </div>
          </div>          
          <div class="control-group">
            <label for="inputError" class="control-label">Password</label>
            <div class="controls">
              <input type="password" id="" name="password" value="">
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Password Confirm</label>
            <div class="controls">
              <input type="password" name="passconf" value="">
            </div>
          </div>
          <?php
          echo '<div class="control-group">';
            echo '<label for="user_type" class="control-label">User Type</label>';
            echo '<div class="controls">';
            echo form_dropdown('user_type', $options_user_type, set_value('user_type'), 'class="span2"');
            echo '</div>';
          echo '</div">';
          ?>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Submit</button>
            <button class="btn" type="reset">Reset</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     