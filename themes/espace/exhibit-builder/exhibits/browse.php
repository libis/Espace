<?php
$title = __('Browse Stories');
echo head(array('title' => $title, 'bodyclass' => 'exhibits exhibits-browse'));
?>

<h1><?php echo $title; ?> <?php echo __('(%s total)', $total_results); ?></h1>
<?php if (count($exhibits) > 0): ?>

<form id="search-form-exhibit" method="get" action="" name="search-form">
    <input id="query" type="text" placeholder="" title="Search" value="" name="query">
    <input type="hidden" name="record_types[]" value="Exhibit">
    <input type="hidden" name="query_type" value="exact_match">
    <button id="submit_search" value="Search" type="submit" name="submit_search">Search</button>
  </form>

<?php
$stories = libis_search_exhibits($_GET);
if(is_array($stories)):
    set_loop_records('exhibit',$stories);
endif;
?>

<nav class="navigation secondary-nav">
    <?php echo nav(array(
        array(
            'label' => __('Browse All'),
            'uri' => url('exhibits')
        ),
        array(
            'label' => __('Browse by Tag'),
            'uri' => url('exhibits/tags')
        )
    )); ?>
</nav>

<?php echo pagination_links(); ?>

<?php $exhibitCount = 0; ?>
<?php foreach (loop('exhibit') as $exhibit): ?>
    <?php $exhibitCount++; ?>
    <div class="exhibit <?php if ($exhibitCount%2==1) echo ' even'; else echo ' odd'; ?>">
        
        <?php if ($exhibitImage = record_image($exhibit, 'square_thumbnail')): ?>
            <?php echo exhibit_builder_link_to_exhibit($exhibit, $exhibitImage, array('class' => 'image')); ?>
        <?php endif; ?>
        <?php if ($exhibitDescription = metadata('exhibit', 'description', array('no_escape' => true))): ?>
        <div class="description"><?php echo $exhibitDescription; ?>
         <?php echo exhibit_builder_link_to_exhibit($exhibit, 'Read more', array('class' => 'read-story')); ?>
        <?php endif; ?>
        <?php if ($exhibitTags = tag_string('exhibit', 'exhibits')): ?>
        <p class="tags"><?php echo $exhibitTags; ?></p>
        <?php endif; ?>
       </div>
    </div>
<?php endforeach; ?>

<?php echo pagination_links(); ?>

<?php else: ?>
<p><?php echo __('There are no exhibits available yet.'); ?></p>
<?php endif; ?>




<?php echo foot(); ?>
