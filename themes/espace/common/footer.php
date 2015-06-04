</div><!-- end content -->

<footer>

    <div id="footer-content" class="center-div">
        <div id="footer_images">
            <img src="<?php echo img("logo/EuropeanaSpace.png");?>">
            <img src="<?php echo img("logo/eu_logo-150x120.png");?>">
            <img src="<?php echo img("logo/spa_web_europeana.png");?>">
            <img src="<?php echo img("logo/LIBIS_CMYK_Into Info.jpg");?>">
            <img src="<?php echo img("logo/digitalmeetsculture_omp.jpg");?>">
            <img src="<?php echo img("logo/KULEUVEN_CMYK_LOGO.PNG");?>">             
            <img src="<?php echo img("logo/cs_logo.png");?>">    
        </div>
        <?php if($footerText = get_theme_option('Footer Text')): ?>
        <div id="custom-footer-text">
            <p><?php echo get_theme_option('Footer Text'); ?></p>
        </div>
        <?php endif; ?>
     
       
    </div><!-- end footer-content -->

     <?php fire_plugin_hook('public_footer', array('view'=>$this)); ?>

</footer>

<script type="text/javascript">
    jQuery(document).ready(function(){
        Omeka.showAdvancedForm();
               Omeka.dropDown();
               
               
    });
</script>

</body>

</html>
