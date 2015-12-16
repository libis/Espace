<?php echo head(array('bodyid'=>'home', 'bodyclass' =>'two-col')); ?>
<div id="header-image-container">
    
    <div id="primary">
        <h2>Europeana Space Photography Pilot</h2>
        <div id="header-image-holder">		
            <div id="homepageText">
                <?php if ($homepageText = get_theme_option('Homepage Text')): ?>
                <p><span class='image-overlay-text'><?php echo $homepageText; ?></span></p>
                <?php endif; ?>
            </div>
            <h2 id="get_started"><a href="<?php echo url('create-your-own-story');?>">&#10146;   Get Started Now</a></h2>
        </div>

        <?php fire_plugin_hook('public_home', array('view' => $this)); ?>
    </div><!-- end primary -->

    <div id="secondary">
         
    </div><!-- end secondary -->
</div>    
 <?php echo theme_header_image(); ?>
</div><!-- end content -->


<div id="browse-stories">  
  <div id="filters" class="button-group">
    <div class="filter-stories">filter stories &#9755;</div>  
    <button data-filter="*">all</button>
    <button data-filter=".new">new</button>
    <button data-filter=".popular">popular</button>
    <button data-filter=".featured">featured</button>
  </div>
  <form id="search-form" method="get" action="" name="search-form">
    <input id="query" type="text" placeholder="Search..." title="Search" value="" name="query">
    <input type="hidden" name="record_types[]" value="Exhibit">
    <input type="hidden" name="record_types[]" value="ExhibitPage">
    <input type="hidden" name="query_type" value="keyword">
    <button id="submit_search" value="Search" type="submit" name="submit_search">Search</button>
  </form>
    <div class="filter-stories browse-all-stories"><a href="<?php echo url('exhibits');?>">browse all stories</a></div>  
</div>

<?php $stories = libis_search_exhibits($_GET);?>
<div id="container">
        <div class="grid-sizer"></div>
        <div class="gutter-sizer"></div>
        
        <div class="stamp story featured popular new">
            <p><a href='<?php echo url('create-your-own-story');?>'><img class="make-icon" src="<?php echo img('book-icon_white.png');?>"><br>Create your own story</a></p>
        </div>
        
        <?php if(is_array($stories)):?>
            <?php foreach($stories as $story):?>
                <?php 
                    $class='';
                    if($story->featured == 1):
                        $class.=" featured";
                    endif;
                    if(strtotime($story->added)<strtotime('-30 days')):
                        $class.=" new";
                    endif;
                ?>
                <div class="story<?php echo $class;?>">   
                    <?php if ($exhibitImage = record_image($story, 'fullsize')): ?>
                        <div class="images">                   
                            <?php echo exhibit_builder_link_to_exhibit($story, $exhibitImage); ?>
                        </div> 
                    <?php endif; ?>
                    <div class="words">
                        <h3><?php echo exhibit_builder_link_to_exhibit($story); ?></h3>
                        <p><?php echo snippet_by_word_count(metadata($story, 'description', array('no_escape' => true)),35); ?></p>
                        <div class="more">
                            <?php echo exhibit_builder_link_to_exhibit($story,'read more'); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        <?php else:?>
            <?php echo $stories;?>
        <?php endif;?>
            
        
        
</div>

<script>
    jQuery( document ).ready(function() {
        var $container = jQuery('#container').imagesLoaded( function() {
        // initialize
            $container.isotope({
              itemSelector: '.story',
              masonry:{
                   columnWidth: $container.find('.grid-sizer')[0],
                   "gutter": $container.find('.gutter-sizer')[0]
              }

            });
            jQuery('#filters').on( 'click', 'button', function() {
                var filterValue = jQuery(this).attr('data-filter');
                $container.isotope({ filter: filterValue });
              });
        });
    });

</script>


<?php echo foot(); ?>
