
<?php $view = get_view();?>


<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Link items to owner?')?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Add a link from items to the person who added them.')?></p>
        <div class="input-block">        
        <?php echo $view->formCheckbox('user_profiles_link_to_owner', null, array('checked' => (bool) get_option('user_profiles_link_to_owner') ? 'checked' : '')); ?>
        </div>
    </div>
</div>

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

<?php 
    $db = get_db();
    //the Table object might or might not reflect it actually existing,
    //so also check it agains the adapter's list of tables
    $contributionFieldsTable = $db->getTable('ContributionContributorFields');
    $tableName = $contributionFieldsTable->getTableName();
    $tables = $db->getAdapter()->listTables();
    //set_option('user_profiles_contributors_imported', 0);
    if(in_array($tableName, $tables) && get_option('user_profiles_contributors_imported') == 0): ?>
    <div class="field">
        <div class="two columns alpha">
            <label><?php echo __('Import Contribution fields as a user profile type?')?></label>    
        </div>
        <div class="inputs five columns omega">
            <div class="input-block">        
            <?php echo $view->formCheckbox('user_profiles_import_contributors'); ?>
            <p>
            <?php echo __("You have used the Contribution plugin to create Contributor information. " . 
                    "For the Omeka 2.x series, that functionality has been folded into the User Profiles plugin. " .
                    "Check this box if you would like to convert Contributor information over into a user profile. " . 
                    "User Profiles offers many new features for your contributor info. After the import is complete, " .
                    "you might want to edit the 'Contributor Info' profile type that is created."
                    ) ; ?>
            </p>
            </div>
        </div>
    </div>    
    
    <?php endif; ?>

