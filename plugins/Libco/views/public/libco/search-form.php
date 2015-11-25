<?php
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
<?php $searchSources = array("Europeana","DigitalNZ","Mint","Rijksmuseum"); ?>
<table>
    <tr>
        <td> <?php echo __("Search By"); ?></td>
        <td> <?php echo $this->formSelect('searchfilter', 'Search By', array('class' => 'existing-element-drop-down'),array('All' => '*', 'title' => 'Title', 'id' => 'Id'), array()); ?> </td>
    </tr>
    <tr style="column-span: 2">
        <td> <?php echo __("Select Search Source"); ?></td>
    </tr>
    <tr>
        <?php
        foreach($searchSources as $sourceName){
            echo "<td>";
                echo $view->formCheckbox('searchsource_'.$sourceName, null, array('checked'=>'checked'));
            echo "</td>";
            echo "<td>";
                echo $sourceName;
            echo "</td>";
        }
        ?>
    </tr>
    <tr>
        <td>
            <?php echo $this->formText('q', $query, array('title' => __('Search keywords'), 'size' => 30)); ?>
        </td>
        <td>
            <?php echo $this->formButton('', __('Search'), array('type' => 'submit')); ?>
        </td>
    </tr>
</table>

</form>