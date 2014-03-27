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




	
	</h2>
  </div>
  <div class="well">
    <form action="<?php echo site_url("admin_report")?>/exportdata" method="post">

        <div class="modal-body">
          <label class="lab-cs">Date Start</label>
           <input class="span2 input-cs" type="date" value="yyyy-mm-dd" name="datestart" />
           <label class="lab-cs">Date End</label>
          <input class="span2 input-cs" type="date" value="yyyy-mm-dd" name="datesend"/>
           <button  class="btn btn-success mg-cs" type="submit" >Download File</button>
        </div>
      
    </form>
</div>

    <div class="row">
        <div class="span12 columns">
          <div class="well" style="display:none">
           
            <?php
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
            $options_user_types = array(0 => "All", 1 => "Member", 2 => "Admin");

            //save the columns names in a array that we will use as filter         
            $options_products = array();    


            echo form_open('admin/accounts', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', "", 'style="width: 260px; height: 26px;"');

              echo form_label('Filter user types:', 'user_type');
              echo form_dropdown('user_type', $options_user_types, "", 'class="span2"');

              //echo form_label('Order by:', 'order');
              //echo form_dropdown('order', $options_products, "", 'class="span2"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              $options_order_type = array('Active' => 'Active', 'In-Active' => 'In-Active');
			  echo form_label('Status:', 'user_status');
              echo form_dropdown('user_status', $options_order_type, "", 'class="span2"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>
		  
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="yellow header headerSortDown">Uniqueid</th>
                <th class="green header">Calldate</th>
                <th class="red header">Source</th>
                <th class="red header">DST</th>
                <th class="red header">Disposition</th>
                 
              </tr>
            </thead>
            <tbody>
              <?php

              $datestring = 'd/m/Y';
              foreach($data as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['clid'].'</td>';
                echo '<td>'.date($datestring,strtotime($row['calldate'])).'</td>';
                echo '<td>'.$row['src'].'</td>';
                echo '<td>'.$row['dst'].'</td>';
                echo '<td>'.$row['disposition'].'</td>';                
              
			
				//echo '</td>';

          //      echo '<td class="crud-actions">
            //      <a href="'.site_url("admin").'/accounts/update/'.$row['clid'].'" class="btn btn-info">edit</a>  
				//  <a data-toggle="modal" data-id="'.site_url("admin").'/accounts/delete/'.$row['clid'].'" href="#deleteModal" class="open-deleteModal btn btn-danger">delete</a>
         //         <!--a href="#" class="btn btn-danger" onclick="deleteMember('."'".site_url("admin").'/accounts/delete/'.$row['clid']."'".');return false;">delete</a-->
          //      </td>';
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
		  <button type="button" class="btn btn-primary" onclick="removeDestination();">Delete</button>
		</div>
	</div>	
<div id="report" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
    <form action="<?php echo site_url("admin_report")?>/exportdata" method="post">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Report </h4>
    </div>
        <div class="modal-body">
          <label class="lab-cs">Date Start</label>
         <input class="span2 input-cs"type="text" name="datestart" value="dsad"/>
           <label class="lab-cs">Date End</label>
          <input class="span2 input-cs" type="text" name="datesend" value="dasdas"/>
        </div>
    <div class="modal-footer">
      <a href="<?php echo site_url("admin_report")?>/exportdata" class="btn btn-success">Download File</a>
    </div>
    </form>
  </div>      
</div>

<script>
$(document).on("click", ".open-deleteModal", function () {
     var userId = $(this).data('id');
     $("#deleteID").val( userId );
});

function removeMember() {
	url_redirect = document.getElementById('deleteID').value;
	document.location = url_redirect;
}

function deleteMember(url_redirect) {
	var r=confirm("This will delete user's data. Are you sure?");
	if (r==true)
	{
	  document.location = url_redirect;
	}
	else
	{
	  return false;
	}
}
</script>