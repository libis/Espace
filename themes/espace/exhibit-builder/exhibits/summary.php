<?php echo head(array('title' => metadata('exhibit', 'title'), 'bodyclass'=>'exhibits summary')); ?>

<p id="simple-pages-breadcrumbs">
<a href="<?php echo public_url('');?>">Home</a> 
> <a href="<?php echo url('exhibits');?>">Stories</a> 
> <?php echo $exhibit->title; ?>
</p>
<h1><?php echo metadata('exhibit', 'title'); ?></h1>
<?php echo exhibit_builder_page_nav(); ?>

<div id="primary">
<?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
<div class="exhibit-description">
    <?php echo $exhibitDescription; ?>
</div>
<?php endif; ?>

<?php if (($exhibitCredits = metadata('exhibit', 'credits'))): ?>
<div class="exhibit-credits">
    <h3><?php echo __('Credits'); ?></h3>
    <p><?php echo $exhibitCredits; ?></p>
</div>
<?php endif; ?>
</div>

<?php if ($exhibit->getPagesCount() > 0): ?>
<nav id="exhibit-pages">
    <ul>
        <?php set_exhibit_pages_for_loop_by_exhibit(); ?>
        <?php foreach (loop('exhibit_page') as $exhibitPage): ?>
        <?php echo exhibit_builder_page_summary($exhibitPage); ?>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>

<?php echo foot(); ?>
