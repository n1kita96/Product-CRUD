<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="updateModalLabel">Update</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="update-id" name="id">
        <h2><center>Update record</center></h2>
        <div class="form-group">
          <label for="recipient-name" class="control-label">Product Name:</label>
          <input type="text" class="form-control Product_Name-validate" id="update-Product_Name" name="Product_Name">
          <span class="invalid-productName"></span>
        </div>
        <div class="form-group">
          <label for="message-text" class="control-label">Cost:</label>
          <input type="text" class="form-control Cost-validate" id="update-Cost" name="Cost">
          <span class="invalid-Cost"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="update" data-dismiss="modal">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>