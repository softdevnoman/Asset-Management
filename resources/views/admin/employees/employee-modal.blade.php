<div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeModalLabel">Add New Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="employeeForm" novalidate enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="employee_id_pk" name="employee_id_pk">
        <div class="modal-body">

          <div class="row">
            <!-- Employee ID -->
            <div class="col-md-6 mb-4">
              <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
              <input type="text" id="employee_id" name="employee_id" class="form-control" placeholder="e.g., EMP-001" required>
            </div>

            <!-- Phone -->
            <div class="col-md-6 mb-4">
              <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
              <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number" required>
            </div>
          </div>

          <div class="row">
            <!-- Position -->
            <div class="col-md-6 mb-4">
              <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
              <input type="text" id="position" name="position" class="form-control" placeholder="e.g., Software Engineer" required>
            </div>

            <!-- Department -->
            <div class="col-md-6 mb-4">
              <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
              <input type="text" id="department" name="department" class="form-control" placeholder="e.g., IT" required>
            </div>
          </div>

          <div class="row">
            <!-- Join Date -->
            <div class="col-md-6 mb-4">
              <label for="join_date" class="form-label">Join Date <span class="text-danger">*</span></label>
              <input type="date" id="join_date" name="join_date" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-4">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on_leave">On Leave</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Profile Photo -->
            <div class="col-md-12 mb-4">
              <label for="profile_photo" class="form-label">Profile Photo</label>
              <input type="file" id="profile_photo" name="profile_photo" class="form-control" accept="image/*">
              <div class="mt-2 current-avatar-preview d-none">
                <span class="d-block small text-muted mb-1">Current Photo:</span>
                <img src="" id="avatar_preview_img" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>
