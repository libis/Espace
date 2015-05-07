    </div>
</div>
    <footer class="" role="contentinfo">
        <div class="container">
        <?php fire_plugin_hook('admin_footer', array('view'=>$this)); ?>
        <p class="left"><a href="http://www.omeka.org" target="_blank"><?php echo __('Powered by Omeka') ?></a> | <a href="http://omeka.org/codex" target="_blank"><?php echo __('Documentation'); ?></a> | <a href="http://omeka.org/forums/" target="_blank"><?php echo __('Support Forums'); ?></a></p>
        
        <p class="right"><?php echo __('Version %s', OMEKA_VERSION); ?>
        <?php if (get_option('display_system_info') && is_allowed('SystemInfo', 'index')): ?>
        | <a href="<?php echo html_escape(url('system-info')); ?>"><?php echo __('System Information'); ?></a></p>
        <?php endif; ?>
        </div>
        
        <div id="footer_images" class="container">
            <img src="<?php echo img("logo/EuropeanaSpace.png");?>">
            <img src="<?php echo img("logo/eu_logo-150x120.png");?>">
            <img src="<?php echo img("logo/spa_web_europeana.png");?>">
            <img src="<?php echo img("logo/LIBIS_CMYK_Into Info.jpg");?>">
            <img src="<?php echo img("logo/digitalmeetsculture_omp.jpg");?>">
            <img src="<?php echo img("logo/KULEUVEN_CMYK_LOGO.PNG");?>">            
        </div>
    </footer>

<script type="text/javascript">
jQuery(document).ready(function () {
    Omeka.runReadyCallbacks();
});
</script>
</body>

</html>
