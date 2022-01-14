<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Router's Details today</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/buttons.bootstrap.min.css')?>" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
  </head>
  <body>
  <div class="container">
    
</center>
    <h3>Router's Details</h3>
    <br />
    <button class="btn btn-success" onclick="add_router()"><i class="glyphicon glyphicon-plus"></i> Add Router</button>
    <br />
    <br />
    <table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
       <tr>
          <th>#</th>
          <th>SAP ID</th>
          <th>Hostname</th>
          <th>Loopback</th>
          <th>Mac Address12345</th>
          <th style="width:125px;">Action
          </p></th>
        </tr>
      </thead>
      <tbody>
				<?php 
        $i = 1;
        foreach($routers as $router){?>
				     <tr>
				         <td><?php echo $i++;?></td>
				         <td><?php echo $router->sap_id;?></td>
								 <td><?php echo $router->hostname;?></td>
								<td><?php echo $router->loopback;?></td>
								<td><?php echo $router->mac_address;?></td>
								<td>
									<button class="btn btn-warning" onclick="edit_router(<?php echo $router->id;?>)"><i class="glyphicon glyphicon-pencil"></i></button>
									<button class="btn btn-danger" onclick="delete_router(<?php echo $router->id;?>)"><i class="glyphicon glyphicon-remove"></i></button>
								</td>
				      </tr>
				     <?php }?>
      </tbody>
 
      <!-- <tfoot>
        <tr>
          <th>#</th>
          <th>SAP ID</th>
          <th>Hostname</th>
          <th>Loopback</th>
          <th>Mac Address</th>
          <th>Action</th>
        </tr>
      </tfoot>
    </table> -->
  </div>
  <script src="<?php echo base_url('assets/js/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
  <script src="<?php echo base_url('assets/js/jquery.dataTables.min.js')?>"></script>
  <script src="<?php echo base_url('assets/js/dataTables.bootstrap.js')?>"></script>
  <script type="text/javascript">
  $(document).ready( function () {
      $('#table_id').DataTable();
  } );
    var save_method; //for save method string
    var table;
 
 
    function add_router()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    }
 
    function edit_router(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals
 
      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('router/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
 
            $('[name="id"]').val(data.id);
            $('[name="sap_id"]').val(data.sap_id);
            $('[name="hostname"]').val(data.hostname);
            $('[name="loopback"]').val(data.loopback);
            $('[name="mac_address"]').val(data.mac_address); 
 
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Router'); // Set title to Bootstrap modal title
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    }
 
    function save()
    {
    
      var sap_id = $.trim($("#sap_id").val());
      if(!sap_id) {
          alert('Please enter the sap id');
          return false;
      }

      var hostname = $.trim($("#hostname").val());
      if(!hostname) {
          alert('Please enter the hostname');
          return false;
      }

      var loopback = $.trim($("#loopback").val());
      if(!loopback) {
          alert('Please enter the loopback');
          return false;
      }

      var mac_address = $.trim($("#mac_address").val());
      if(!mac_address) {
          alert('Please enter the mac address');
          return false;
      }

      var url;
      if(save_method == 'add')
      {
          url = "<?php echo site_url('router/router_add')?>";
      }
      else
      {
        url = "<?php echo site_url('router/router_update')?>";
      }
 
       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
               $('#modal_form').modal('hide');
              location.reload();// for reload a page
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }
 
    function delete_router(id)
    {
      if(confirm('Are you sure delete this data?'))
      {
        // ajax delete data from database
          $.ajax({
            url : "<?php echo site_url('router/router_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
               
               location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
 
      }
    }
 
  </script>
 
  <!-- Bootstrap modal -->
  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title alert alert-info">Router Form</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="mobile_id"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">SAP ID</label>
              <div class="col-md-9">
                <input name="sap_id" id="sap_id" placeholder="Enter SAP ID" class="form-control" type="text">
                <input name="id" class="form-control" type="hidden" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3">Hostname</label>
              <div class="col-md-9">
                <input name="hostname" id="hostname" placeholder="Enter Hostname" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Loopback</label>
              <div class="col-md-9">
								<input name="loopback" id="loopback" placeholder="Enter loopback" class="form-control" type="text">
 
              </div>
            </div>
						<div class="form-group">
							<label class="control-label col-md-3">Mac Address</label>
							<div class="col-md-9">
								<input name="mac_address" id="mac_address" placeholder="Enter Mac Address" class="form-control" type="text">
 
							</div>
						</div>
 
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Save</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
 
  </body>
</html>