<?php
$collectionTitle = strip_formatting(metadata('collection', array('Dublin Core', 'Title')));
?>

<?php echo head(array('title'=> $collectionTitle, 'bodyclass' => 'collections show')); ?>

<h1><?php echo $collectionTitle; ?></h1>

<h2 class="link-to-meta"><a href="#collection-meta">Go to Collection description</a></h2>

<?php if (metadata('collection', 'total_items') > 0): ?>
    <div class="collection-images">
        <?php foreach (loop('items') as $item): ?>
        <?php $itemTitle = strip_formatting(metadata('item', array('Dublin Core', 'Title'))); ?>
        
            <?php if (metadata('item', 'has thumbnail')): ?>
            <div class="item-img collection-show-img">
                <?php echo link_to_item(item_image('square_thumbnail', array('alt' => $itemTitle))); ?>
            </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>    
    <?php else: ?>
        <p><?php echo __("There are currently no items within this collection."); ?></p>
    <?php endif; ?>

    <div id="collection-meta"><?php echo all_element_texts('collection'); ?></div>

<div id="collection-items">
    <h2><?php echo link_to_items_browse(__('Items in the %s Collection', $collectionTitle), array('collection' => metadata('collection', 'id'))); ?></h2>
    
</div><!-- end collection-items -->

<?php fire_plugin_hook('public_collections_show', array('view' => $this, 'collection' => $collection)); ?>

<?php echo foot(); ?>
