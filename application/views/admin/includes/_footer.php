<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<footer id="footer" class="main-footer">
    <div class="pull-right hidden-xs">
        <strong style="font-weight: 600;"><?php echo $this->settings->copyright; ?>&nbsp;</strong>
    </div>
    <b>Version</b>&nbsp;1.9
</footer>
</div><!-- ./wrapper -->
<!-- jQuery UI 1.13.2 -->
<script src="<?php echo base_url(); ?>assets/admin/js/jquery-ui-1.13.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/admin/js/adminlte.min.js"></script>
<!-- DataTables js -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables_new.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- Lazy Load js -->
<script src="<?php echo base_url(); ?>assets/admin/js/lazysizes.min.js"></script>
<!-- iCheck js -->
<script src="<?php echo base_url(); ?>assets/vendor/icheck/icheck.min.js"></script>
<!-- Pace -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/pace/pace.min.js"></script>
<!-- File Manager -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/file-manager/file-manager-1.0.js?v3"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/file-uploader/js/jquery.dm-uploader.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/file-uploader/js/ui.js"></script>
<!-- Plugins js -->
<script src="<?php echo base_url(); ?>assets/admin/js/plugins.js"></script>
<!-- Color Picker js -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- Datepicker js -->
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
<!-- Custom js -->
<script src="<?php echo base_url(); ?>assets/admin/js/custom-1.9.js?v6"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/post-types.js?v9"></script>

<!--tinyMCE-->
<script src="<?php echo base_url(); ?>assets/admin/plugins/tinymce/jquery.tinymce.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/tinymce/tinymce.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/datatables/rowReorder.dataTables.min.css">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.rowReorder.min.js"></script>

<script>
    function init_tinymce(selector, min_height) {
        var menu_bar = 'file edit view insert format tools table help';
        if (selector == '.tinyMCEQuiz') {
            menu_bar = false;
        }
		if (selector == '.tinyMCESummary') {
            menu_bar = false;
        }
		
		
        tinymce.init({
            width: "100%",
            selector: selector,
            min_height: min_height,
            valid_elements: '*[*]',
            relative_urls: false,
            remove_script_host: false,
            directionality: directionality,
            language: '<?php echo $this->selected_lang->text_editor_lang; ?>',
            menubar: menu_bar,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code codesample fullscreen",
                "insertdatetime media table paste imagetools"
            ],
            toolbar: 'fullscreen code preview | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor removeformat | image media link | outdent indent',
            content_css: ['<?php echo base_url(); ?>assets/admin/plugins/tinymce/editor_content.css'],
        });
        tinymce.DOM.loadCSS('<?php echo base_url(); ?>assets/admin/plugins/tinymce/editor_ui.css');
    }

    if ($('.tinyMCE').length > 0) {
        init_tinymce('.tinyMCE', 500);
    }
    if ($('.tinyMCEsmall').length > 0) {
        init_tinymce('.tinyMCEsmall', 300);
    }
    if ($('.tinyMCEQuiz').length > 0) {
        init_tinymce('.tinyMCEQuiz', 200);
    }
	if ($('.tinyMCESummary').length > 0) {
        init_tinymce('.tinyMCESummary', 200);
    }
    $(document).ready(function () {
        setTimeout(() => {
            if($("#summary_word").length){
                tinymce.get('summary').on('keyup',function(e){
                    $("#summary_word").text(count_word(this.getContent()));
                });
                $("#summary_word").text(count_word(tinymce.get('summary').getContent()));
            }
            if($("#content_tinymce_word").length){
                tinymce.get('content_tinymce').on('keyup',function(e){
                    $("#content_tinymce_word").text(count_word(this.getContent()));
                });
                $("#content_tinymce_word").text(count_word(tinymce.get('content_tinymce').getContent()));
            }
        }, 1000);
    });

	
	
</script>

<?php if (isset($lang_search_column)): ?>
    <script>
        var table = $('#cs_datatable_lang').DataTable({
            dom: 'l<"#table_dropdown">frtip',
            "order": [[0, "desc"]],
            "aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
        });
        //insert a label
        $('<label class="table-label"><label/>').text('Language').appendTo('#table_dropdown');

        //insert the select and some options
        $select = $('<select class="form-control input-sm"><select/>').appendTo('#table_dropdown');

        $('<option/>').val('').text('<?php echo trans("all"); ?>').appendTo($select);
        <?php foreach ($this->languages as $lang): ?>
        $('<option/>').val('<?php echo $lang->name; ?>').text('<?php echo $lang->name; ?>').appendTo($select);
        <?php endforeach; ?>
        $("#table_dropdown select").val($("#table_dropdown option:eq(<?php echo $this->selected_lang->id; ?>)").val());
        table.column(<?php echo $lang_search_column; ?>).search($("#table_dropdown option:eq(<?php echo $this->selected_lang->id; ?>)").val()).draw();
        
        $("#table_dropdown select").change(function () {
            table.column(<?php echo $lang_search_column; ?>).search($(this).val()).draw();
        });
        <?php if(isset($select_lang) && !empty($select_lang)){ ?>
            $("#table_dropdown select").val("<?php echo $select_lang; ?>");
        <?php } ?>
    </script>
<?php endif; ?>
</body>
</html>

