<div class="container top">

  <ul class="breadcrumb">
	<li>
	  <a href="<?php echo site_url("admin"); ?>">
		<?php echo ucfirst($this->uri->segment(1));?>
	  </a> 
	  <span class="divider">/</span>
	</li>
	<li class="active">
	  <?php echo ucfirst($this->uri->segment(2));?>
	</li>
  </ul>

  <div class="page-header users-header">
	<h2>
	  <?php echo ucfirst($this->uri->segment(2));?> 
	  <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">New Account</a>
	</h2>
  </div>
  

    <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
            $options_user_types = array(0 => "All", 1 => "Member", 2 => "Admin");

            //save the columns names in a array that we will use as filter         
            $options_products = array();    


            echo form_open('admin/accounts/search', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', "", 'style="width: 260px; height: 26px;"');

              echo form_label('Filter user types:', 'user_type');
              echo form_dropdown('user_type', $options_user_types, "", 'class="span2"');

              //echo form_label('Order by:', 'order');
              //echo form_dropdown('order', $options_products, "", 'class="span2"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              $options_order_type = array('1' => 'Active', '0' => 'In-Active');
			  echo form_label('Status:', 'user_status');
              echo form_dropdown('user_status', $options_order_type, "", 'class="span2"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>
		  
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header headerSortDown">Fistname</th>
                <th class="green header">Lastname</th>
                <th class="red header">Email</th>
                <th class="red header">Username</th>
                <th class="red header">Admin</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($accounts as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['first_name'].'</td>';
                echo '<td>'.$row['last_name'].'</td>';
                echo '<td>'.$row['email_address'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
				
                echo '<td>';
				if($row['is_admin'])
					echo 'Yes';
				else
					echo 'No';
				echo '</td>';

              echo '<td class="crud-actions">
                  <a href="'.site_url("").'admin_accounts/update/'.$row['id'].'" class="btn btn-info">edit</a>  
                 <a data-toggle="modal" data-id="'.site_url("").'/admin_accounts/delete/'.$row['id'].'" href="#deleteModal" class="open-deleteModal btn btn-danger">delete</a>
                 <!-- <a href="#deleteModal" data-toggle="modal" class="btn btn-danger" onclick="deleteMember('."'".site_url("admin").'/leads/delete/'.$row['id']."'".');return false;">delete</a>-->
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>		  
        <div class="pagination pagination-right">
               <?php echo $list_pagination ;?>
        </div>
		</div>
	</div>
	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<input type = "hidden" id="deleteID" value=""/>
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title">Deleting Destination</h4>
		</div>
        <div class="modal-body">
          This will remove all user's data from system and can not undo.
		  Are you sure want to continue?
        </div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		 <a  id="delcs" class="btn btn-primary" href="#">Delete</a>
		</div>
	</div>	  
</div>

<script type="text/javascript">
$(document).on("click", ".open-deleteModal", function () {
   var userId = $(this).data('id');
  $('#delcs').attr('href',userId);
});

</script>