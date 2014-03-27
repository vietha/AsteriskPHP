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
            <?php echo ('Leads');?>
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
     

      //form validation
      echo validation_errors();

      echo form_open('admin_leads/update/'.$this->uri->segment(3).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Name</label>
            <div class="controls">
              <input type="text" id="" name="leads_name" value="<?php echo $name[0]['name']; ?>" >
            </div>
          </div>
         <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="leads_email" value="<?php echo $name[0]['email'];  ?>" >
            </div>
          </div>          
         <div class="control-group">
            <label for="inputError" class="control-label">Phone</label>
            <div class="controls">
              <input type="text" id="" name="leads_phone" value="<?php echo $name[0]['phonenumber'];  ?>" >
            </div>
          </div>
         <div class="control-group">
            <label for="inputError" class="control-label">Status</label>
            <div class="controls">
                <select name="leads_status">
                  <?php                  
                      if($name[0]['is_active']==0){

                        echo "<option value='0'>In Active</option>";
                        echo "<option value='1'>Active</option>";
                      }
                      else{

                        echo "<option value='1'>Active</option>";
                        echo "<option value='0'>In Active</option>";
                      }
                  ?>
                
              </select>


              
            </div>
          </div>
         <div class="control-group">
            <label for="inputError" class="control-label">Date Create</label>
            <div class="controls">
              <input type="date" id="" name="leads_datecreate" value="<?php echo $name[0]['datecreate'];  ?>" >
            </div>
          </div>
         <div class="control-group">
            <label for="inputError" class="control-label">Last Update</label>
            <div class="controls">
              <input type="date" id="" name="leads_lastupdate" value="<?php echo $name[0]['lastupdate'];  ?>" >
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     