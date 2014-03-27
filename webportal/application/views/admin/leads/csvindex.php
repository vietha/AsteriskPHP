    <head>
        <meta charset="utf-8">     
        <link href="<?php echo base_url(); ?>assets/css/admin/bootstrap.css" type="text/css" rel="stylesheet" /> 
        <script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    </head>
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
        <h2>Leads Import</h2>
      </div>  
        <div class="container">    
             <br>
 
             <?php if (isset($error)): ?>
               <!-- <div class="alert alert-error"><?php echo $error; ?></div>-->
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>-->
            <?php endif; ?>          
                <form method="post" action="<?php echo base_url() ?>csv/importcsv" enctype="multipart/form-data">
                    <input  type="file" name="userfile" ><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>
 
            <br><br>
        
 
         
 
        </div>
 
</div>

        </div>