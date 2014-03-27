<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>Call Control System</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
  	<script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/admin.min.js"></script>
</head>
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="brand">Project Name</a>
	      <ul class="nav">
	        <li <?php if($this->uri->segment(2) == 'accounts'){echo 'class="active"';}?>>
	          <a href="<?php echo site_url("admin/accounts");?>">Accounts</a>
	        </li>
	        <li <?php if($this->uri->segment(2) == 'leads'){echo 'class="active"';}?>>
	          <a href="<?php echo site_url("admin/leads");?>">Leads Management</a>
	        </li>
	        <li <?php if($this->uri->segment(2) == 'report'){echo 'class="active"';}?>>
	         <a href="<?php echo site_url("admin/report");?>">Report</a>
	        </li>			
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">System <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	            <li>
	              <a href="<?php echo site_url("admin/logout"); ?>">Logout</a>
	            </li>
	          </ul>
	        </li>
	      </ul>
	    </div>
	  </div>
	</div>	
