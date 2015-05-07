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
    
    <div class="stamp">
        <p>
            <img class="make-icon" src="http://localhost/omeka/espace/themes/espace/images/book-icon_white.png">
            <br>
            Create your own story
            </p>
    </div>
</div>
<?php echo foot(); ?>
