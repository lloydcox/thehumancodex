<script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('admin/js/sb-admin-2.min.js')}}"></script>
<script src="{{asset('admin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    $('#postCategoryDeleteConfirmationModal').on("show.bs.modal", function (e) {
        $("#delete_post_category_id_input").val($(e.relatedTarget).data('id'));
    });
    $('#connectionCategoryDeleteConfirmationModal').on("show.bs.modal", function (e) {
        $("#delete_connection_category_id_input").val($(e.relatedTarget).data('id'));
    });
</script>
