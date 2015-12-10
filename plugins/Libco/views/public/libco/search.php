<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<?php
require_once LIBCO_DIR."/helpers/ImportRecord.php"
?>

<?php
if(empty($totalResults)):
    $totalResults = 0;
endif;

if(empty($query)):
    $query = "";
endif;
?>

<?php $pageTitle = __('Search Europeana Space ') . __('(%s total)', $totalResults); ?>
<?php echo head(array('title' => $pageTitle)); ?>

<h1><?php echo $pageTitle; ?></h1>

<?php echo $this->partial('libco/search-form.php', array('query' => $query)); ?>

<div id="libco-results">
<?php
    if(isset($_POST['eurecords'])){
        $currentUser = current_user();
        if(!isset($currentUser)){
            echo 'To import items from Europeana Space into Omeka you need to login.';
            return;
        }
        $userId = $currentUser->id;

        $importer = new ImportRecord();
        $importer->userId = $userId;

        // import to a new collection
        if(isset($_POST['chbcollection'], $_POST['txtncollectionname']))
            $importer->collectionName =  $_POST['txtncollectionname'];

        // import to an existing collection
        if(isset($_POST['chbexistingcollection'], $_POST['existingcollections'])){
            $collArray = explode(',', $_POST['existingcollections']);
            $importer->addToExistingCollectionId = $collArray[0];
            $importer->collectionName = $collArray[1];
            $importer->addToExistingCollection = true;
        }

        $response = $importer->importRecords($_POST['eurecords']);
        if(!empty($importer->messages) && is_array($importer->messages)){
            foreach($importer->messages as $message){
                echo $message."<br>";
            }
            unset($importer->messages);
        }
    }
?>


<?php if (!empty($error)): ?>
    <p><strong><?php echo __("Error: {$error}"); ?></strong></p>

<?php elseif ($totalResults): ?>
    
    <?php
        // fetch a list of current user collections
    $currentUser = current_user();
    $usercollections = array();
    if(isset($currentUser)){
        $lcService = new LibcoService();
        $usercollections = $lcService->getCollectionList(current_user()->id);
    }    
    ?>
    <form method="post" class="ajax" id="main">
    <h3>Add items to a collection?</h3>
    <div class="field">
        <input type="checkbox" name="chbcollection"> <?php echo __("Create A New Collection"); ?>
        <input type="text" name="txtncollectionname" placeholder="New Collection Name" disabled>
    </div>
    <div class="field">
        <input type="checkbox" name="chbexistingcollection"> <?php echo __("Add to Existing Collection"); ?>
        <?php echo $this->formSelect('existingcollections', 'Existing Collections', array('class' => 'existing-element-drop-down', 'disabled' => 1),$usercollections, array()); ?>
    </div>   
    
    <div class="field import-items">
        <input type="submit" name="btnsubmit" value="Import Items">
    </div>
    
    <table id="search-results">
        
            <thead>           
            <tr>
                <th class="select">                    
                    <?php echo "<input type='checkbox' class='cbselecctall' id='selecctall'>"; ?>
                    <?php echo __('Select all');?>
                </th>
                <th><?php echo __('');?></th>
                <th><?php echo __('Title');?></th>
                <th><?php echo pagination_links();?></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($records as $source => $items): ?>
                <tr><td colspan=4><h2><?php echo $source."(".sizeof($items).")";?></h2></td></tr>
                <?php
                foreach($items as $data):
                    $title = $data['title'];
                    $weblink = $data['url'];
                    $url = $weblink['fromSourceAPI'];
					$data['search_source'] = $source;   // added to the data array to use source as an item type				
                    ?>

                    <tr>
                        <td style="vertical-align: middle;"><?php
                            $record_str = base64_encode(serialize($data));
                            echo "<input type='checkbox' class='cbrecord' id='checkboxselect'  value=' . $record_str .' name='eurecords[]'>";
                            ?>
                        </td>
                        <td>
                            <div id="imag-div">
                            <?php
                                $image = current($data['thumb']);
                                if(!empty($image) && $image != "null"):?>
                                    <img src="<?php echo current($data['thumb']); ?>" height="90" width="90" alt="" onerror="this.style.display='none';">
                                <?php endif;?>
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo "<h3><a target = '_blank' href='$url'>$title</a></h3>"; ?>
                        </td>
                        <td></td>
                    </tr>

                <?php endforeach; ?>
            <?php endforeach;?>
           
            </tbody>
       
    </table>
    <p><input type="submit" name="btnsubmit" value="Import Items"></p>
    <p><i><a class="search-again" href="#libco-search-form">Search again?</a><i></p>
     </form>
<?php endif;?>
</div>

<script>
    $(document).ready(function() {
        $('#selecctall').click(function(event) {  //on click
            if(this.checked) { // check select status
                $('.cbrecord').each(function() { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "cbrecord"
                });
            }else{
                $('.cbrecord').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "cbrecord"
                });
            }
        });

        $('.cbrecord').click(function() {
            console.log('clicked for uncheck');
            $(".cbselecctall").prop("checked", false);
        });

        // enable/disable new collection name text box
        $("input[name='chbcollection']").click(function(event) {  //on click
            if(this.checked) { // check select status
                $( "input[name='txtncollectionname']").val('');
                $( "input[name='txtncollectionname']" ).prop( "disabled", false );
                // disable existing collection option
                $( "select[name='existingcollections']" ).prop( "disabled", true );
                $("input[name='chbexistingcollection']").prop("checked", false);
            }else{
                $( "input[name='txtncollectionname']").val('');
                $( "input[name='txtncollectionname']" ).prop( "disabled", true );
            }
        });

        // enable/disable collection list drop down
        $("input[name='chbexistingcollection']").click(function(event) {  //on click
            if(this.checked) { // check select status
                $( "select[name='existingcollections']" ).prop( "disabled", false );
                //disable new collection option
                $( "input[name='txtncollectionname']").val('');
                $( "input[name='txtncollectionname']" ).prop( "disabled", true );
                $("input[name='chbcollection']").prop("checked", false);
            }else{
                $( "select[name='existingcollections']" ).prop( "disabled", true );
            }
        });

    });
</script>

<?php echo foot(); ?>
