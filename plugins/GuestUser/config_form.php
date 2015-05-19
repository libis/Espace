<?php $view = get_view(); ?>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __('Registration Features'); ?></label>    
    </div>
    <div class="inputs five columns omega" >
        <p class='explanation'><?php echo __("Add some text to the registration screen so people will know what they get for registering.
        As you enable and configure plugins that make use of the guest user, please give them guidance
        about what they can and cannot do."); ?>
        </p>
        <div class="input-block">
            <textarea name="guest_user_capabilities" ><?php echo get_option('guest_user_capabilities'); ?></textarea>
        </div>

    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Short Registration Features"); ?></label>    
    </div>
    <div class="inputs five columns omega" >
        <p class='explanation'><?php echo __("Add a shorter version to use as a dropdown from the user bar. If empty, no dropdown will appear."); ?>
        </p>        
        <div class="input-block">
            <textarea name="guest_user_short_capabilities" ><?php echo get_option('guest_user_short_capabilities'); ?></textarea>
        </div>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Dashboard Label"); ?></label>    
    </div>    
    <div class="inputs five columns omega">
        <p class='explanation'><?php echo __("The text to use for the label on the user's dashboard"); ?></p>
        <div class="input-block">        
            <input name="guest_user_dashboard_label" type="text" value="<?php echo get_option('guest_user_dashboard_label'); ?>" />        
        </div>
    </div>
</div>

<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Login Text"); ?></label>    
    </div>    
    <div class="inputs five columns omega">
        <p class='explanation'><?php echo __("The text to use for the 'Login' link in the user bar"); ?></p>
        <div class="input-block">        
        <input name="guest_user_login_text" type="text" value="<?php echo get_option('guest_user_login_text'); ?>" />        
        </div>
    </div>
</div>
        
<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Register Text"); ?></label>    
    </div>    
    <div class="inputs five columns omega">
        <p class='explanation'><?php echo __("The text to use for the 'Register' link in the user bar."); ?></p>
        <div class="input-block">        
            <input name="guest_user_register_text" type="text" value="<?php echo get_option('guest_user_register_text'); ?>" />        
        </div>
    </div>
</div>


<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Allow open registration?"); ?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __("Allow guest user registration without administrator approval?"); ?></p>
        <div class="input-block">        
            <?php
                $checked = ( get_option('guest_user_open') == 1) ;
                if($checked) {
                    $options = array('checked'=>'checked');
                } else {
                    $options = array();
                }
                echo $view->formCheckbox('guest_user_open', null, $options);                
            ?>
        </div>
    </div>
</div>
        
        
<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Allow instant access?"); ?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __("Allow instant access for 20 minutes for new users"); ?></p>
        <div class="input-block">        
            <?php
                $checked = ( get_option('guest_user_instant_access') == 1 );
                if($checked) {
                    $options = array('checked'=>'checked');
                } else {
                    $options = array();
                }            
                echo $view->formCheckbox('guest_user_instant_access', null, $options);
            ?>
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

<?php if(get_option('recaptcha_public_key') && get_option('recaptcha_private_key')): ?>
<div class="field">
    <div class="two columns alpha">
        <label><?php echo __("Require ReCaptcha?"); ?></label>    
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __("Check this to require passing a ReCaptcha test when registering"); ?></p>
        <div class="input-block">        
            <?php
                $checked = ( get_option('guest_user_recaptcha') == 1);
                if($checked) {
                    $options = array('checked'=>'checked');
                } else {
                    $options = array();
                }
                echo $view->formCheckbox('guest_user_recaptcha', null, $options);
            ?>           
        </div>
    </div>
</div>
<?php else:?>
<p><?php echo __("You have not set up ReCaptcha keys in the security settings. We strongly recommend using ReCaptcha to prevent spam account creation."); ?>

<?php endif;?>