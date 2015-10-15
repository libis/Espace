<?php
$bodyclass = 'page simple-page';
if ($is_home_page):
    $bodyclass .= ' simple-page-home';
endif;

echo head(array(
    'title' => metadata('simple_pages_page', 'title'),
    'bodyclass' => $bodyclass,
    'bodyid' => metadata('simple_pages_page', 'slug')
));
?>
<?php if (!$is_home_page): ?>
    <p id="simple-pages-breadcrumbs"><?php echo simple_pages_display_breadcrumbs(); ?></p>
    <h1><?php echo metadata('simple_pages_page', 'title'); ?></h1>
    <?php endif; ?>
<div id="primary">    
    <?php
    $text = metadata('simple_pages_page', 'text', array('no_escape' => true));
    echo $this->shortcodes($text);
    ?>
</div>
<div id='secondary'>
    <?php echo simple_pages_navigation();?>
    <?php if(metadata('simple_pages_page', 'title')!='Create your own story'):?>
    <div class="stamp">
        <p><a href='<?php echo url('create-your-own-story');?>'><img class="make-icon" src="<?php echo img('book-icon_white.png');?>"><br>Create your own story</a></p>
    </div>
    <?php endif;?>
</div>
<?php echo foot(); ?>
