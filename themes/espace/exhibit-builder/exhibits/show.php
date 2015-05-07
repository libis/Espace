<?php
echo head(array(
    'title' => metadata('exhibit_page', 'title') . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));
?>
 
<p id="simple-pages-breadcrumbs">
<a href="<?php echo public_url('');?>">Home</a> 
> <a href="<?php echo url('exhibits');?>">Stories</a> 
> <?php echo exhibit_builder_link_to_exhibit($exhibit); ?>
<?php
    $exhibitPage = get_current_record('exhibit_page');
    $currentPage = $exhibitPage;
    $parents = array();
    while ($currentPage->parent_id) {
        $currentPage = $currentPage->getParent();
        array_unshift($parents, $currentPage);
    }

    $html = '';
    foreach ($parents as $parent) {
        $html .= ' > ';
        $text = metadata($parent, 'title');
        $html .= exhibit_builder_link_to_exhibit($exhibit, $text, array(), $parent);
      
        release_object($parent);
    }
    
    echo $html." ";
?>
> <?php echo metadata('exhibit_page', 'title'); ?>
</p>
<h1><span class="exhibit-page"><?php echo metadata('exhibit_page', 'title'); ?></h1>
<div id='primary'>


<?php exhibit_builder_render_exhibit_page(); ?>

<div id="exhibit-page-navigation">
    <?php if ($prevLink = exhibit_builder_link_to_previous_page()): ?>
    <div id="exhibit-nav-prev">
    <?php echo $prevLink; ?>
    </div>
    <?php endif; ?>
    <?php if ($nextLink = exhibit_builder_link_to_next_page()): ?>
    <div id="exhibit-nav-next">
    <?php echo $nextLink; ?>
    </div>
    <?php endif; ?>
    <div id="exhibit-nav-up">
    </div>
</div>
</div>

<nav id="exhibit-pages">
    <h4><span class='story-title'>Story title : <?php echo exhibit_builder_link_to_exhibit($exhibit); ?></span></h4>
    <?php echo exhibit_builder_page_tree($exhibit, $exhibit_page); ?>
</nav>
<?php echo foot(); ?>
