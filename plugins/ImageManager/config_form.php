<div class="field">
    <!--<div id="simple_pages_filter_page_content_label" class="two columns alpha">
        <label for="simple_pages_filter_page_content"><?php echo __('Filter User Input For Page Content?'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <?php echo get_view()->formCheckbox('simple_pages_filter_page_content', true, 
        array('checked'=>(boolean)get_option('simple_pages_filter_page_content'))); ?>
        <p class="explanation"><?php echo __(
            'If checked, this plugin will filter all simple pages using the HTML ' 
          . 'filters set on the security settings page. PHP code will not be ' 
          . 'allowed in the content of pages if this box is checked.'
        ); ?></p>
    </div>-->
</div>

<?php $view = get_view(); ?>
<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Hide from"); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __("Hide plugin from navigation menu for roles:"); ?></p>
        <div class="input-block">
            <?php
            /*
             * Hide/Show plugin from main navigation menu, for user role(s).
             */
            $userRoles = get_user_roles();
            $currentClass = get_class() ;
            if(isset($userRoles, $currentClass)){
                $pluginName = str_replace('plugin', '', strtolower($currentClass));
                echo "<table>";
                echo "<tr><th>Role</th><th>Hide</th></tr>";
                foreach($userRoles as $role){
                    $userKey = strtolower($pluginName."_".$role."_hide");
                    $hidden = get_option($userKey);
                    if($hidden) {
                        $hiddenOptions = array('checked'=>'checked');
                    } else {
                        $hiddenOptions = array();
                    }
                    echo "<tr>";
                    echo "<td>$role</td>";
                    echo "<td>";
                    echo $view->formCheckbox($userKey, null, $hiddenOptions);
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </div>
    </div>
</div>