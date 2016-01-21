<!DOCTYPE html>
<html class="<?php echo get_theme_option('Style Sheet'); ?>" lang="<?php echo get_html_lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
    <?php if ($description = option('description')): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>

    <?php
    if (isset($title)) {
        $titleParts[] = strip_formatting($title);
    }
    $titleParts[] = option('site_title');
    ?>
    <title><?php echo implode(' &middot; ', $titleParts); ?></title>

    <?php echo auto_discovery_link_tags(); ?>

    <?php fire_plugin_hook('public_head',array('view'=>$this)); ?>
    <!-- Stylesheets -->
    <?php
    queue_css_file(array('iconfonts', 'style'));

    echo head_css();
    ?>
    <!-- JavaScripts -->
    <?php queue_js_file('vendor/modernizr'); ?>
    <?php queue_js_file('vendor/selectivizr', 'javascripts', array('conditional' => '(gte IE 6)&(lte IE 8)')); ?>
    <?php queue_js_file('vendor/respond'); ?>
    <?php queue_js_url('http://cdnjs.cloudflare.com/ajax/libs/masonry/3.2.2/masonry.pkgd.min.js'); ?>
    <?php queue_js_file('isotope.pkgd.min'); ?>
    <?php queue_js_file('imagesloaded.pkgd.min'); ?>
    <?php queue_js_file('globals'); ?>
    <?php echo head_js(); ?>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link href='http://fonts.googleapis.com/css?family=Lato:300,300italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Vollkorn:400,400italic,700italic,700' rel='stylesheet' type='text/css'>
</head>
 <?php echo body_tag(array('id' => @$bodyid, 'class' => @$bodyclass)); ?>
    <?php //fire_plugin_hook('public_body', array('view'=>$this)); ?>
        <header>            
            <div id="site-title">
                <a href="<?php echo url("/");?>">
                    <?php echo theme_logo();?>
                </a>
            </div>
            <div id="primary-nav">
                <?php echo public_nav_main();?>                
            </div>
             <div id="mobile-nav">
                <?php echo public_nav_main();?>               
            </div>
            <div id="admin-bar-public">
                 <?php if($user = current_user()) {
                $links = array(
                    array(
                        'label' => __('Welcome, %s', $user->name),
                        'uri' => admin_url('/users/edit/'.$user->id)
                    ),
                    array(
                        'label' => __('Log Out'),
                        'uri' => url('/users/logout')
                    )
                );
            } else {
                $links = array(array(
                        'label' => __('Log In'),
                        'uri' => url('/users/login')
                    ));
            }

            echo nav($links, 'public_navigation_admin_bar');
            ?>
            </div>
        </header>         
  
         
        
		<?php if ((get_theme_option('Header Image') !== null)): ?>
		
		<?php endif; ?>	
                      
    <div id="content">

<?php fire_plugin_hook('public_content_top', array('view'=>$this)); ?>
