{{-- Global confirm modal --}}
<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
            <h5 class="modal-title fw-semibold" id="confirmActionModalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <i class="bi bi-warning"></i><p class="mb-0 text-muted" id="confirmActionModalMessage">Are you sure?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-danger" id="confirmActionModalNo" data-bs-dismiss="modal">
                    No
                </button>
                <button type="button" class="btn btn-success" id="confirmActionModalYes">
                    Yes
                </button>
            </div>
        </div>
    </div>
</div>