<?php
require_once LIBCO_DIR."/models/LibcoService.php";
$formAttributes = array(
    'action' => url('libco/libco/search'),
    'method' => 'GET',
    'class'  => 'europeana-search',
    'class'  => 'llibco-search',
);
?>
<?php $view = get_view();?>
<?php echo $this->form('libco-search-form', $formAttributes); ?>

<div><?php echo flash(); ?></div>

<div id="libco-search-box">
    <div class="field">
        <?php echo $this->formText('q', $query, array('title' => __('Search keywords'), 'size' => 40, 'placeholder' => 'Search...')); ?>
        <?php echo $this->formButton('', __('Search'), array('type' => 'submit')); ?>
    </div> 

    <?php
    $libcoService = new LibcoService();
    $searchSources = $libcoService->getSearchSources();
    ?>
    <div class="field">
        <label><?php echo __("Select Search Source"); ?></label>
        <div class="inputs">
            <ul>
            <?php
            if(!empty($searchSources) && is_array($searchSources)){
                foreach($searchSources as $source){
                    $sourceName = $source->name;

                    /* Api returns error 500 if Youtube is a part of the search sources, therefore skip it.*/
                    if(strtolower($sourceName) === "youtube")
                        continue;

                    echo "<li>";
                    echo $view->formCheckbox('searchsource_'.$sourceName, null, array('class' => 'source', 'checked'=>'checked'));
                    echo $sourceName;
                    echo "</li>";
                }
                echo "<br>";
                echo "<li>";
                echo "<input type='checkbox' class='cbsourceselecctall' id='sourceselecctall' checked='checked'>";
                echo __('All Sources');
                echo "</li>";
            }
            else{
                echo 'Error in retrieving search sources from Europeana Space.';
            }
            ?>
             </ul>
        </div>
    </div>    

        <p><a target="_blank" href="<?php echo url('espaceapisearch'); ?>"><?php echo __("Need help?"); ?></a></p>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#sourceselecctall').click(function(event) {  //on click
            if(this.checked) { // check select status
                $('.source').each(function() { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "cbrecord"
                });
            }else{
                $('.source').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "cbrecord"
                });
            }
        });

        $('.source').click(function() {
            console.log('clicked for uncheck');
            $(".cbsourceselecctall").prop("checked", false);
        });
    });
</script>