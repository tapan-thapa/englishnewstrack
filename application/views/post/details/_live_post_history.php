<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(!empty($live_blog_history)){
    foreach($live_blog_history as $history){ 
?>
<div class="live_history">
        <div class="timedate">
        <?php
        if(isset($history->created_at)){
            echo helper_date_format($history->created_at); 
            echo "&nbsp;&nbsp;";
            echo date("h:i A",strtotime($history->created_at)) ;
        }
        ?>    
        </div>
        <div class="TitleCont">
        <div class="live_title"><b><?php echo $history->title; ?></b></div>
        <?php if (!empty($history->image_path)): ?>
            <div class="image_inner">
            <img src="<?php echo get_post_image($history, "photoslider"); ?>" class="img-responsive" />

            </div>
        <?php endif; ?>
        <div class="live_des"><?php echo $history->description; ?></div>
        </div>
</div>


<?php } } ?>
<div style="height:30px; width:100%;float:left;"></div>