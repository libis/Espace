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
            <h2 id="get_started">&#10146;   Get Started Now</h2>
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
      <div class="filter-stories">browse stories &#9755;</div>
  <button data-filter="*">show all</button>
  <button data-filter=".new">new</button>
  <button data-filter=".popular">popular</button>
  <button data-filter=".featured">featured</button>
</div>
    <form id="search-form" method="get" action="" name="search-form">
        <input id="query" type="text" placeholder="Type here and press Enter" title="Search" value="" name="query">
        <input type="hidden" name="record_types[]" value="Exhibit">
        <input type="hidden" name="query_type" value="exact_match">
        <button id="submit_search" value="Search" type="submit" name="submit_search">Search</button>
    </form>
</div>

<?php $records = libis_search_exhibits($_GET);?>
<div id="container">
        <div class="grid-sizer"></div>
        <div class="gutter-sizer"></div>
        
        <div class="stamp story">
            <p><img class="make-icon" src="<?php echo img('book-icon_white.png');?>"><br>Create your own story</p>
        </div>
        
        <div class="story featured">            
            <div class="images"><img src="http://www.photoconsortium.net/wp-content/uploads/2015/01/R8_M_742_103_MHF-474x284.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story">
            
           
            <div class="images"><img src="http://sgdap.girona.cat/sdam/imatges/079240.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story">              
            <div class="images"><img src="http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=849766"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story featured">               
            <div class="images"><img src="http://www.musei.uniroma1.it/dbinfo/RMSMUS06/JPEG/V.O.114.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story new popular">               
            <div class="images"><img src="http://europeana.parisiennedephotographie.fr/SSEU/Files/8580ab52844c0d0a8d2d5935d639615d5ad8607ff2e97be1d5b145ebbf6ff037"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story popular">               
            <div class="images"><img src="http://www.photoconsortium.net/wp-content/uploads/2015/01/Digital-Gallery.-Promoter-srl-843-694x416.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story">               
            <div class="images"><img src="http://sgdap.girona.cat/sdam/imatges/066731.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
            <div class="more">
                    <a href="">read more</a>
                </div>
            </div>
        </div>
        
        <div class="story">               
            <div class="images"><img src="http://sgdap.girona.cat/sdam/imatges/066579.jpg"></div>
            <div class="words">
                <h3>Maximus mi diam</h3>
                <p>Suspendisse maximus mi diam, at posuere lacus dictum quis. Quisque consectetur orci felis, eu pretium lacus ornare eu. Curabitur non augue at ipsum lacinia lobortis sit amet ornare lorem.</p>
                <div class="more">
                    <a href="">read more</a>
                </div>
            </div>            
        </div>
        
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
