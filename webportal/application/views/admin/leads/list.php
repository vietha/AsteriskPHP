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
	  <a  style="margin-left:10px"   href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="col-md-2 btn btn-success">New Account</a>
    <a href="#upl" data-toggle="modal" class="btn btn-success">Import CSV</a>
	</h2>
  </div>
  

    <div class="row">
        <div class="span12 columns">
          <div class="well">
           
        <form action="<?php echo site_url('admin/search');?>" method = "post">
        <label  class="span1 lab-cs">Search</label><input class="span3 input-cs" type="text" name = "keyword" />
        <label class="lab-cs">Date Create</label><input class="span3 input-cs" type="date" value="" name="start_date">             
        <input type="submit"  class="btn btn-primary mg-cs" value = "Go" />
        </form>

            <?php
           
           // $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
          //  $options_user_types = array(0 => "All", 1 => "Member", 2 => "Admin");

            //save the columns names in a array that we will use as filter         
          //  $options_products = array();    


         //   echo form_open('admin/leads', $attributes);
     
            //  echo form_label('Search:', 'search_string');
             // echo form_input('search_string', "", 'style="width: 260px; height: 26px;"');

              //echo form_label('Filter user types:', 'user_type');
             // echo form_dropdown('user_type', $options_user_types, "", 'class="span2"');

              //echo form_label('Order by:', 'order');
              //echo form_dropdown('order', $options_products, "", 'class="span2"');

           //   $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

             // $options_order_type = array('Active' => 'Active', 'In-Active' => 'In-Active');
			//  echo form_label('Status:', 'user_status');
             // echo form_dropdown('user_status', $options_order_type, "", 'class="span2"');

            //  echo form_submit($data_submit);

            //echo form_close();
            ?>

          </div>
		    <div>
             
        </div>
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header headerSortDown">Name</th>
                <th class="green header">Email</th>
                <th class="red header">Phone Number</th>               
                <th class="red header">Data Create</th>
                <th class="red header">Last Update</th>
                <th class="red header">Status</th>
                <th class="red header">Actions</th>
                 
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($data as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['name'].'</td>';
                echo '<td>'.$row['email'].'</td>';
                echo '<td>'.$row['phonenumber'].'</td>';               
                echo '<td>'.$row['datecreate'].'</td>';
				        echo '<td>'.$row['lastupdate'].'</td>';
          echo '<td>'    ; 
				if($row['is_active']=='1')
					echo 'Active';
				else
					echo 'In Active';
				echo '</td>';

                echo '<td class="crud-actions">
                  <a href="'.site_url("").'/admin_leads/update/'.$row['id'].'" class="btn btn-info">edit</a>  
				        <a data-toggle="modal" data-id="'.site_url("").'/Admin_leads/delete/'.$row['id'].'" href="#deleteModal" class="open-deleteModal btn btn-danger">delete</a>
                  <!-- <a href=""  class="btn btn-danger" onclick="removeMember('."'".site_url("").'/leads/delete/'.$row['id']."'".');return false;">dasd</a>-->
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

<div id="upl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">  
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Upload Leads</h4>
    </div>
    <div class="modal-body text-center">
        <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success') == TRUE): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>          
        <form method="post" action="<?php echo base_url() ?>csv/importcsv" enctype="multipart/form-data">
        <input  type="file" name="userfile" ><br><br>
        <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
    </div>

</div>


<script type="text/javascript">
$(document).on("click", ".open-deleteModal", function () {
   var userId = $(this).data('id');
  $('#delcs').attr('href',userId);
});

</script>