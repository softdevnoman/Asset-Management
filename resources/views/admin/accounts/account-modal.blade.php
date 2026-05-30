<div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accountModalLabel">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="accountForm" novalidate>
                @csrf
                <input type="hidden" id="account_id_pk" name="account_id_pk">
                <div class="modal-body">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Enter full name" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="name@company.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Role -->
                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label">Account Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">Password <span class="text-danger" id="password-asterisk">*</span></label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="••••••••" required>
                            <small class="text-muted id-note d-none">Leave blank if you do not want to change the password.</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger" id="confirm-password-asterisk">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
