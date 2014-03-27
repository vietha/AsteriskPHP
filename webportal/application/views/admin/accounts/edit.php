    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ('Admin');?>
          </a> 
          <span class="active"> / </span>
        </li>
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ('Accounts');?>
          </a> 
          <span class="active"> / </span>
        </li>
        <li class="active">
          <a href="#">Update</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
      </div>

 
      <?php
      //flash messages
      if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> account updated with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
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

      echo form_open('admin_accounts/update/'.$this->uri->segment(3).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Username</label>
            <div class="controls">
              <input type="text" id="" name="username" value="<?php echo $user[0]['user_name']; ?>" disabled>
            </div>
          </div>
         <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="email" value="<?php echo $user[0]['email_address']; ?>" >
            </div>
          </div>          
          <div class="control-group">
            <label for="inputError" class="control-label">New Password</label>
            <div class="controls">
              <input type="password" id="" name="password" value="" >
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">New Password Confirm</label>
            <div class="controls">
              <input type="password" name="passconf" value="" >
            </div>
          </div>
          <?php
          echo '<div class="control-group">';
            echo '<label for="user_type" class="control-label">User Type</label>';
            echo '<div class="controls">';
            echo form_dropdown('user_type', $options_user_type, $user[0]['is_admin'], 'class="span2"');
            echo '</div>';
          echo '</div">';
          ?>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     