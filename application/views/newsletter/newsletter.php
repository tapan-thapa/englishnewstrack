
<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Newsdrum,  Political to Business News, National to International Breaking Alert</title>
        	<meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#0c8cc7" />
		
    <meta name="description" content="Read National, Business, International News, Technology, Sports, City Breaking news Alerts, Education Articles, Politics Live updates, Finance and Entertainment News, Exclusive News and coverage"/>
    <meta name="keywords" content="Latest News, Breaking News, Live News, Neutral News, News from India, International news, citi news, personal finance, cryptocurrency, sports, lifestyle, television, film, bollwood."/>
    <meta name="author" content="NewsDrum"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:site_name" content="NewsDrum"/>
    <meta property="og:image" content="https://www.newsdrum.in/uploads/logo/dn_share_logo.png"/>
    <meta property="og:image:width" content="210"/>
    <meta property="og:image:height" content="90"/>
    <meta property="og:type" content="website"/>
    <meta  property="og:title" content="Newsdrum,  Political to Business News, National to International Breaking Alert"/>
    <meta property="og:description" content="Read National, Business, International News, Technology, Sports, City Breaking news Alerts, Education Articles, Politics Live updates, Finance and Entertainment News, Exclusive News and coverage"/>
    <meta property="og:url" content="https://www.newsdrum.in/"/>
    <meta property="fb:app_id" content=""/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@NewsDrum"/>
    <meta name="twitter:title" content="Newsdrum,  Political to Business News, National to International Breaking Alert - Newsdrum - Latest News, Breaking News, Live News, Neutral News, News from India, International news, citi news"/>
    <meta name="twitter:description" content="Read National, Business, International News, Technology, Sports, City Breaking news Alerts, Education Articles, Politics Live updates, Finance and Entertainment News, Exclusive News and coverage"/>
 
<link rel="shortcut icon" type="image/png" href="https://www.newsdrum.in/uploads/logo/logo_61fa6b13322cf.png"/>
</head>
  <title>NewsDrum Newsletter</title>
</head>
<body style="padding:0; margin:0;">
  <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Section: main -->
<section id="main">
    <div class="container">
        <div class="row">
            <!-- breadcrumb -->
           

            <center style="width: 100%; background-color: #fff;">
    <div style="max-width:600px; margin:auto">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
       
        <tr>
          <td align="center" style="background:#0c8cc7;" height="10"></td>
        </tr>
        <tr>
          <td bgcolor="#0C8CC7"><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="logo" class="logo custom" width="130"></a></td>
            </tr>
            <tr>
              <td height="10"></td>
            </tr>
            <tr>
              <td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
                <tr>
                  <td align="center"><a href="<?php echo base_url(); ?>" style="font-size:18px; color:#fff; text-decoration:none; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">News</a></td>
                  <td align="center"><a href="<?php echo base_url(); ?>analysis" style="font-size:18px; color:#fff; text-decoration:none; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Analysis</a></td>
                  <td align="center"><a href="<?php echo base_url(); ?>opinion" style="font-size:18px; color:#fff; text-decoration:none; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Opinion</a></td>
                  <td align="center"><a href="<?php echo base_url(); ?>culture" style="font-size:18px; color:#fff; text-decoration:none; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Culture</a></td>
                  <td align="center"><a href="<?php echo base_url(); ?>lifestyle" style="font-size:18px; color:#fff; text-decoration:none; font-family:Arial, Helvetica, sans-serif; font-weight:bold;">Lifestyle</a></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td bgcolor="#0C8CC7" height="10"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        
        <?php $count = 0;
        foreach ($featured_newsletter_posts as $post):
          
        if ($count < 1){ ?>
        <tr>
          <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                
                <img class="img-cover" src="<?php echo base_url().''.$post->image_slider; ?>" alt="<?php echo $post->headline; ?>" class="img-cover" width="100%" height="auto" />
                
            </tr>
             <tr>
              <td height="10"></td>
            </tr>
            <tr>
              <td><a style="color: #0c8cc7;cursor: pointer;font-size: 14px;font-weight: bold; font-family:Arial, Helvetica, sans-serif; text-decoration:none;" href="<?php echo generate_post_url($post); ?>"><span class="category-label BCatItem"><?php echo html_escape($post->topic); ?></span></a></td>
            </tr>
            <tr>
              <td height="5"></td>
            </tr>
            <tr>
              <td> <a style="font-size:24px; line-height:30px; font-weight: bold; font-family:Arial, Helvetica, sans-serif; text-decoration:none; color:#000; display:block;" href="<?php echo generate_post_url($post); ?>" class="img-link"><?php  if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?></a></td>
            </tr>
            <tr>
              <td height="15"></td>
            </tr>
            <tr>
              <td><div class="timestamp post" style="font-size:13px; color:#999; font-family:Arial, Helvetica, sans-serif;"><?php echo date("H:i A",strtotime($post->created_at));?></div></td>
            </tr>
          </table></td>
        </tr>
        <?php }else{
            ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
        
        
        <tr>
          <td><table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="26%" valign="top"   style="padding-top: 5px;">
                <img class="img-cover" src="<?php echo base_url().''.$post->image_small; ?>" alt="<?php echo $post->headline; ?>" class="img-cover" width="100%" height="auto" />
              </td>
              <td width="74%" valign="top"><table width="95%" border="0" align="right" cellpadding="0" cellspacing="0">
                <tr>
                  <td><a style="color: #0c8cc7;cursor: pointer;font-size: 14px;font-weight: bold; font-family:Arial, Helvetica, sans-serif; text-decoration:none;" href="<?php echo generate_post_url($post); ?>"><?php echo html_escape($post->topic); ?> </a></td>
                </tr>
                
                <tr>
                  <td><a style="font-size:16px; line-height:22px; font-weight: bold; font-family:Arial, Helvetica, sans-serif; text-decoration:none; color:#000; display:block; line-height:22px;" href="<?php echo generate_post_url($post); ?>"><?php  if(empty($post->headline)){ echo html_escape($post->title);}else{ echo html_escape($post->headline);}  ?></a></td>
                </tr>
               
                <tr>
                  <td><div class="timestamp post" style="font-size:13px; color:#999; font-family:Arial, Helvetica, sans-serif;"><?php echo date("H:i A",strtotime($post->created_at));?></div></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      <?php }
      $count++; 
    endforeach; ?>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    
    </center>
        </div>
    </div>
</section>
</body>
</html>


