<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
        <div class="box-header with-border">
            <div class="left">
                <h3 class="box-title"><?php echo "Choose Display keyword"; ?></h3>
            </div>
        </div><!-- /.box-header -->
        <script>
            $(document).ready(function () {
                //Select2
                $(".progLang").select2({
                 tags: false,
                 maximumSelectionLength: 4,
                 tokenSeparators: [
                     "/", ", ", ";", " ", "#"],
                });
               
        });
        </script>
        <div class="box-body">
            <?php if (!empty($post)){ 
                ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            
                            <input type="text" name="key_search" id="key_search" minlength="3" />
                            <span id="key_search_Go" style="border: 1px solid; padding: 5px; cursor:pointer;background: #ccc;">G0</span>
                            &nbsp;
                            <span  id="key_search_Reset" style="border: 1px solid; padding: 5px; cursor:pointer;background: #ccc;">Reset</span>
                            <br/>
                            <div id="keybox_edit">
                                <p>Click to select Keyword</p>
                                <select class="progLang" id="keySelect" name="post_select_key[]" style="padding-top:2px;width: 100%;" multiple="true">
                                    <?php 
                                
                                    foreach ($get_postKeys as $key => $value) {
                                        if (in_array($key, $postSelectIds)){
                                            $sel = "selected";
                                        }else{
                                            $sel ="";
                                        }
                                ?>
                                    <option value="<?php echo $key ?>" <?php echo $sel; ?>><?php echo $value ?></option>
                                <?php
                                }   
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="form-group">
                    <div class="row keysection">
                        <div class="col-sm-12">
                            <input type="text" name="key_search" id="key_search" minlength="3" />
                            <span id="key_search_Go" style="border: 1px solid; padding: 5px; cursor:pointer;background: #ccc;">G0</span>
                            &nbsp;
                            <span  id="key_search_Reset" style="border: 1px solid; padding: 5px; cursor:pointer;background: #ccc;">Reset</span>
                            <br/>
                            <div id="keybox">
                                <p>Click to select Keyword</p>
                                <select class="progLang" id="keySelect" name="post_select_key[]" style="padding-top:2px;width: 100%;display:none" multiple="true">
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#keybox').hide();
                $(document).on('click', '#key_search_Go', function (e) {
                    e.preventDefault();
                    var searchStr = $('#key_search').val();
                    var len = $('#key_search').val().length;
                    if(len >=3){
                        $('#keybox').show();
                    }else{
                        alert('enter minimum 3 letter');
                    }
                    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
                    csrfHash = $.cookie(csfr_cookie_name);
                    var dataJson = { [csrfName]: csrfHash, searchTerm: searchStr };

                    $.ajax({
                        url: base_url + "Post_controller/searchRecByInput",
                        type: 'post',
                        dataType: 'json',
                        data: dataJson,
                        cache: false,             
                        success : function(res)
                        {   
                            $("input[name=61f7fc7cae86a_csrf_token]").val(res.token);
                            //$('#keySelect').html('').select2({data: [{id: '', text: ''}]});   
                            if($('#keySelect').length>0){
                                $('#keySelect option').each(function(){
                                    if(!$(this).is(':selected')){
                                        $(this).remove();
                                    }
                                });
                            }    
                            $.map(res.data, function (item) {
                                $('#keySelect').append("<option value='"+item.id+"'>"+item.text+"</option>");
                            });
                        } 
                    });

                });
                $(document).on('click', '#key_search_Reset', function () {
                    $('#key_search').val('');
                    $('#keybox').hide();
                    $('#keySelect').html('').select2({data: [{id: '', text: ''}]});
                });
             });
        </script>