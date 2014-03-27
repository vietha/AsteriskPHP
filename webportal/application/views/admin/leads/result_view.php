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
		  <input type="submit"  class="btn btn-primary mg-cs" value = "Search" />
				</form>
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
                echo '</tr>';
              }


              ?>      
            </tbody>
          </table>		  
		  <?php echo $this->pagination->create_links();?>
		</div>
	</div>
	  
</div>
