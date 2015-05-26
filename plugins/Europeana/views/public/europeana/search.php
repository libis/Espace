<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<?php $pageTitle = __('Search Europeana ') . __('(%s total)', $totalResults); ?>
<?php echo head(array('title' => $pageTitle, 'bodyclass' => 'europeana search')); ?>

<h1><?php echo $pageTitle; ?></h1>

<?php echo $this->partial('europeana/search-form.php', array('query' => $query)); ?>

<?php if ($error): ?>
<p><strong><?php echo __("Error: {$error}"); ?></strong></p>

<?php elseif ($totalResults): ?>
<?php
    if(isset($_POST['eurecords'])){
        $db = get_db();
        $itemTable = $db->getTable('Item');
        $elementTable = $db->getTable('Element');
        $elementTitleId = $elementTable->findByElementSetNameAndElementName('Dublin Core', 'Title');
        $elementDescriptionId = $elementTable->findByElementSetNameAndElementName('Dublin Core', 'Description');

        $currentUser = current_user();
        if(!isset($currentUser)){
            echo 'To import items from Europeana into Omeka you need to login.';
            return;
        }
        $userId = $currentUser->id;

        $temp_dir = '/tmp';
        $counter = 0;

        foreach ($_POST['eurecords'] as $record_selected) {
            $record_obj = unserialize(base64_decode($record_selected));

            $title = metadata($record_obj, 'title');
            if(isset($title) && strlen($title) > 0){
                $description = metadata($record_obj, 'dcDescription');

                /* Add item */
                $timestamp = date('Y-m-d G:i:s');
                $item = $db->insert("Item", array('item_type_id' => 1, 'public' => 1, 'added' => $timestamp, 'owner_id' => $userId));
                if(isset($item)){
                    $itemElementTitle = $db->insert("Element_text",
                                        array('record_id' => $item, 'record_type' => 'Item',
                                        'element_id' => $elementTitleId->id, 'text' => $title));

                    if(isset($description)){
                        $itemElementDescription = $db->insert("Element_text",
                            array('record_id' => $item, 'record_type' => 'Item',
                                'element_id' => $elementDescriptionId->id, 'text' => $description));
                    }

                    /* Add image to item */
                    $file_name = round(microtime(true) * 1000).'.jpg';
                    $image_path = $temp_dir.'/'.$file_name;
                    $image = metadata($record_obj, 'edmPreview');
                    if(isset($image)){

                        // Download image
                        //copy($image, $image_path);

                        // Create context stream
                        //$context_array = array('http'=>array('proxy'=>'icts-http-gw.cc.kuleuven.be:8080','request_fulluri'=>true));
                        $context_array = array();
                        $context = stream_context_create($context_array);
                        $data = file_get_contents($image,false,$context);
                        file_put_contents($image_path,$data);

                        // Link image to item
                        $fl = new File;
                        $fl->original_filename = $file_name;
                        $fl->setDefaults($image_path);
                        $item = $db->getTable('Item')->find($item);
                        $item->addFile($fl);
                        $item->saveFiles();
                    }
                    $counter++;
                }

            }

        }
        echo "Number of items imported from Europeana: ".$counter;

    }
?>

<?php echo pagination_links(); ?>
<table id="search-results">
    <form method="post" class="ajax" id="main">
    <thead>
    <tr>
        <td colspan="3">
            <input type="submit" name="btnsubmit" value="Add to Items">
        </td>
    </tr>
        <tr>
            <th>
                <?php echo __('Selection');?>
                <?php echo '<br>'; echo "<input type='checkbox' class='cbselecctall' id='selecctall'>"; ?>
            </th>
            <th><?php echo __('Record Type');?></th>
            <th><?php echo __('Title');?></th>
            <?php

            //var_dump(metadata($record[2], 'edmPreview'));
/*            echo "<pre>";
            print_r($records[2]);
            echo "</pre>";*/
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
        <tr>
            <td><?php
                $record_id = metadata($record, 'id');
                //file_put_contents("/var/www/html/tunnel/tbr/espace/record_before.txt",print_r($record, true)."\r\n", FILE_APPEND);
                $record_str = base64_encode(serialize($record));
                //echo "<input type='checkbox' class='cbrecord' id='checkboxselect'  value=' . $record_id .' name='eurecords[]'>";
                echo "<input type='checkbox' class='cbrecord' id='checkboxselect'  value=' . $record_str .' name='eurecords[]'>";
                //echo metadata($record, 'id');
                ?>
            </td>
            <td>
                <?php echo __(ucfirst(strtolower(metadata($record, 'type')))); ?>
            </td>
            <td>
                <a href="<?php echo metadata($record, 'edmLandingPage'); ?>"><img src="<?php echo metadata($record, 'edmPreview'); ?>" class="image" alt="" /><?php echo metadata($record, 'title'); ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3">
                <input type="submit" name="btnsubmit" value="Add to Items">
            </td>
        </tr>
    </tbody>
    </form>
</table>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>


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
    });
</script>

<?php echo foot(); ?>