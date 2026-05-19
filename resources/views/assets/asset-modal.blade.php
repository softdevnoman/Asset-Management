<div class="modal fade" id="assetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assetModalLabel">Add New Asset</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="/assets">
        @csrf
        <div class="modal-body">
          <div class="row">
            <!-- Asset Code -->
            <div class="col-md-6 mb-4">
              <label for="asset_code" class="form-label">Asset Code <span class="text-danger">*</span></label>
              <input type="text" id="asset_code" name="asset_code" class="form-control" placeholder="e.g., AST-001" required>
            </div>

            <!-- Asset Name -->
            <div class="col-md-6 mb-4">
              <label for="name" class="form-label">Asset Name <span class="text-danger">*</span></label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Enter asset name" required>
            </div>
          </div>

          <div class="row">
            <!-- Serial Number -->
            <div class="col-md-6 mb-4">
              <label for="serial_number" class="form-label">Serial Number <span class="text-danger">*</span></label>
              <input type="text" id="serial_number" name="serial_number" class="form-control" placeholder="Enter serial number" required>
            </div>

            <!-- Category -->
            <div class="col-md-6 mb-4">
              <label for="category" class="form-label">Category</label>
              <select class="form-select" id="category" name="category">
                <option value="">Select category</option>
                <option value="Electronics">Electronics</option>
                <option value="Furniture">Furniture</option>
                <option value="Vehicle">Vehicle</option>
                <option value="Machinery">Machinery</option>
                <option value="Software">Software</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Purchase Price -->
            <div class="col-md-6 mb-4">
              <label for="purchase_price" class="form-label">Purchase Price ($)</label>
              <input type="number" step="0.01" id="purchase_price" name="purchase_price" class="form-control" placeholder="0.00">
            </div>

            <!-- Current Value -->
            <div class="col-md-6 mb-4">
              <label for="current_value" class="form-label">Current Value ($)</label>
              <input type="number" step="0.01" id="current_value" name="current_value" class="form-control" placeholder="0.00">
            </div>
          </div>

          <div class="row">
            <!-- Condition -->
            <div class="col-md-6 mb-4">
              <label for="condition" class="form-label">Condition</label>
              <select class="form-select" id="condition" name="condition">
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
                <option value="Under Repair">Under Repair</option>
              </select>
            </div>

            <!-- Assign To -->
            <div class="col-md-6 mb-4">
              <label for="assigned_to" class="form-label">Assign To</label>
              <select class="form-select" id="assigned_to" name="assigned_to">
                <option value="">Select user</option>
                <option value="John Doe">John Doe</option>
                <option value="Jane Smith">Jane Smith</option>
                <option value="Mike Johnson">Mike Johnson</option>
                <option value="Sarah Wilson">Sarah Wilson</option>
                <option value="Conference Room">Conference Room</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Purchase Date -->
            <div class="col-md-6 mb-4">
              <label for="purchase_date" class="form-label">Purchase Date</label>
              <input type="date" id="purchase_date" name="purchase_date" class="form-control">
            </div>

            <!-- Warranty Expiry -->
            <div class="col-md-6 mb-4">
              <label for="warranty_expiry" class="form-label">Warranty Expiry Date</label>
              <input type="date" id="warranty_expiry" name="warranty_expiry" class="form-control">
            </div>
          </div>

          <div class="row">
            <!-- Maintenance Date -->
            <div class="col-md-6 mb-4">
              <label for="maintenance_date" class="form-label">Last Maintenance Date</label>
              <input type="date" id="maintenance_date" name="maintenance_date" class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-4">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Retired">Retired</option>
              </select>
            </div>
          </div>

          <!-- Notes -->
          <div class="row">
            <div class="col-12 mb-4">
              <label for="notes" class="form-label">Notes</label>
              <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Additional notes about the asset..."></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Asset</button>
        </div>
      </form>
    </div>
  </div>
</div>
