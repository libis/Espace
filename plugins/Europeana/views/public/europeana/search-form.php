<?php 
$formAttributes = array(
    'action' => url('europeana'),
    'method' => 'GET',
    'class'  => 'europeana-search',
); 
?>
<?php echo $this->form('europeana-search-form', $formAttributes); ?>
    <?php
        echo "<br>";
        echo $this->formLabel('searchby', 'Search By ');
        echo $this->formSelect('searchfilter', 'Search By', array('class' => 'existing-element-drop-down'),array('title' => 'Title','All' => '*', 'id' => 'Id'), array());
        echo "<br><br>";
    ?>
    <?php echo $this->formText('q', $query, array('title' => __('Search keywords'), 'size' => 30)); ?>
    <?php echo $this->formButton('', __('Search'), array('type' => 'submit')); ?>
</form>
