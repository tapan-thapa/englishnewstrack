<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer id="footer">
    <div class="container">
    <div class="ContMid">
    <div class="row">
    <div class="col-sm-2 col-xs-12">
    <a class="Logo" href="<?= lang_base_url(); ?>" class="Footerlogo"> 
                    <img src="<?= $this->dark_mode == 1 ? get_logo_footer($this->visual_settings) : get_logo($this->visual_settings); ?>" alt="logo" class="logo custom" width="100%"  >
                </a>
</div>
    <div class="col-sm-10 col-xs-12">
    <ul class="FSubMenu">
             <?php if (!empty($this->menu_links)):
                                foreach ($this->menu_links as $item):
                                    if ($item->item_visibility == 1 && $item->item_location == "footer"):?>                       
             <li>
            <a title="<?php echo html_escape($item->item_name); ?>" href="<?php echo generate_menu_item_url($item); ?>"><?php echo html_escape($item->item_name); ?> </a>
                </li>
                <?php endif;
                                endforeach;
                            endif; ?> 
            </ul> 
         </div>

          <div class="col-sm-10 col-xs-12">  <div class="foot-bdr">  </div></div>
              
        <!-- Copyright -->
        <div class="footer-bottom">
                 <div class="col-md-3">
                <div class="SocialBtn footer-widget f-widget-follow">
                        <ul>
                            <li><?php echo trans("footer_follow"); ?></li> 
                            <!--Include social media links-->
                            <?php $this->load->view('partials/_social_media_links', ['rss_hide' => true]); ?>
                        </ul>
                    </div>
                        </div>

                    <div class="col-md-9">

                    <div class="footer-bottom-left">
                        <p><?php echo html_escape($this->settings->copyright); ?></p>
                    </div>
           
        </div>
    </div>
    </div>
</footer>

<a title="Top" href="#" class="scrollup"><div class="scrollupCont"><!--<div class="backtext">Back to Top</div>--> <i class="icon-arrow-up"></i></div></a>

<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins-1.8.js"></script>
<script>
      (function () {
	$('.hamburger-menu').on('click', function() {
		$('.bar').toggleClass('animate');
    var mobileNav = $('.main-menu');
    mobileNav.toggleClass('menuhide menushow');
	})
})();
        </script>


<script>
    $(document).ready(function(){
      $('.slick-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 4,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: true,
        autoplaySpeed: 2000,
        autoplay: false,
        adaptiveHeight: true, 
        responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
      });
    });
    $(document).ready(function(){
      $('.short-video-slider').slick({
        slidesToShow:2,
        slidesToScroll: 2,
        arrows: true,
        dots: false,
        speed: 300,
        infinite: true,
        autoplaySpeed: 2000,
        autoplay: false,
        adaptiveHeight: true, 
        responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 1,
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
        }
      }
    ]
      });
    });
</script>
<script>
    $(document).ready(function() {
        $('.container').bind('cut copy', function(e) {
            e.preventDefault();
            const elem = document.createElement('textarea');
            elem.value = getSelectedText();
            document.body.appendChild(elem);
            elem.select();
            document.execCommand('copy');
            document.body.removeChild(elem);
        });
        $(document).keydown(function(objEvent) {
            if (objEvent.ctrlKey) {
                if (objEvent.keyCode == 65) {

                    return false;
                }
            }
        });
    });

    function getSelectedText() {
        let selectedText = window.getSelection ? window.getSelection() : document.getSelection ? document.getSelection() : document.selection ?  document.selection.createRange().text : '';

        let text = selectedText.toString();

        if(text.length > 150){
            finalText = text.substring(0, 150)+"...";
        }else{
            finalText = text;
        }

        return finalText+" "+window.location;
    }
    </script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5909878024864044" crossorigin="anonymous"></script>

<?php if (check_cron_time(3)): ?>
    <script>$.ajax({type: "POST", url: "<?php echo base_url(); ?>vr-run-internal-cron"});</script>
<?php endif; ?>
<?php if ($this->settings->cookies_warning && empty(helper_getcookie('cookies_warning'))): ?>
    <div class="cookies-warning">
        <div class="text"><?php echo $this->settings->cookies_warning_text; ?></div>
        <a title="Close" href="javascript:void(0)" onclick="hide_cookies_warning();" class="icon-cl"> <i class="icon-close"></i></a>
    </div>
<?php endif; ?>
<script>
    var sys_lang_id = '<?php echo $this->selected_lang->id; ?>';$('<input>').attr({type: 'hidden', name: 'sys_lang_id', value: sys_lang_id}).appendTo('form');var base_url = "<?php echo base_url(); ?>";var fb_app_id = "<?php echo $this->general_settings->facebook_app_id; ?>";var csfr_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";var csfr_cookie_name = "<?php echo $this->config->item('csrf_cookie_name'); ?>";var is_recaptcha_enabled = false;var sweetalert_ok = "<?php echo trans("ok"); ?>";var sweetalert_cancel = "<?php echo trans("cancel"); ?>";<?php if ($this->recaptcha_status == true): ?>is_recaptcha_enabled = true;<?php endif; ?>
</script>
<script src="<?php echo base_url(); ?>assets/js/script-1.9.min.js?v=1.4"></script>

<?php if ($this->general_settings->pwa_status == 1): ?>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= base_url();?>pwa-sw.js').then(function (registration) {
                }, function (err) {
                    console.log('ServiceWorker registration failed: ', err);
                }).catch(function (err) {
                    console.log(err);
                });
            });
        } else {
            console.log('service worker is not supported');
        }
    </script>
<?php endif; ?>
<!-- <?php //if(!$this->auth_check && $this->general_settings->newsletter_status == 1 && $this->general_settings->newsletter_popup == 1 && empty(helper_getcookie('vr_news_p'))): ?>
    <script>$(window).on('load', function () {$('#modal_newsletter').modal('show');});</script>
<?php //endif; ?> -->
<?php echo $this->general_settings->google_analytics; ?>
<?php echo $this->general_settings->custom_javascript_codes; ?>
<?php $this->load->view('partials/_js_footer'); ?>

</body>
</html>