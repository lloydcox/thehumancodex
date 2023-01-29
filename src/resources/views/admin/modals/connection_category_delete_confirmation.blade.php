<div class="modal fade" id="connectionCategoryDeleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="connectionCategoryDeleteConfirmationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Delete" below if you are ready to delete the Connection Category</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin_delete_connection_categories') }}" id="logout-form" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" value="{{csrf_token()}}" name="_token">
                    <input type="hidden" id="delete_connection_category_id_input" name="id">
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
