<?php

define('GUEST_USER_PLUGIN_DIR', PLUGIN_DIR . '/GuestUser');
include(FORM_DIR . '/User.php');


class GuestUserPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'uninstall',
        'define_acl',
        'public_header',
        'public_head',
        'admin_theme_header',
        'config',
        'config_form',
        'before_save_user',
        'initialize',
        'users_browse_sql'
    );

    protected $_filters = array(
        'public_navigation_admin_bar',
        'public_show_admin_bar',
        'guest_user_widgets',
        'admin_navigation_main'
    );


    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        add_translation_source(dirname(__FILE__) . '/languages');
    }

    public function setUp()
    {
        parent::setUp();
        require_once(GUEST_USER_PLUGIN_DIR . '/libraries/GuestUser_ControllerPlugin.php');
        Zend_Controller_Front::getInstance()->registerPlugin(new GuestUser_ControllerPlugin);
    }

    public function hookInstall()
    {
        $db = $this->_db;
        $sql = "CREATE TABLE IF NOT EXISTS `$db->GuestUserTokens` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `token` text COLLATE utf8_unicode_ci NOT NULL,
                  `user_id` int NOT NULL,
                  `email` tinytext COLLATE utf8_unicode_ci NOT NULL,
                  `created` datetime NOT NULL,
                  `confirmed` tinyint(1) DEFAULT '0',
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci;
                ";

        $db->query($sql);

        //if plugin was uninstalled/reinstalled, reactivate the guest users
        $guestUsers = $this->_db->getTable('User')->findBy(array('role'=>'guest'));
        //skip activation emails when reinstalling
        if(count($guestUsers) != 0) {
            set_option('guest_user_skip_activation_email', true);
            foreach($guestUsers as $user) {
                $user->active = true;
                $user->save();
            }
            set_option('guest_user_skip_activation_email', false);
        }

        set_option('guest_user_login_text', __('Login'));
        set_option('guest_user_register_text', __('Register'));
        set_option('guest_user_dashboard_label', __('My Account'));
    }

    public function hookUninstall($args)
    {
        //deactivate the guest users
        $guestUsers = $this->_db->getTable('User')->findBy(array('role'=>'guest'));
        foreach($guestUsers as $user) {
            $user->active = false;
            $user->save();
        }
    }

    public function hookDefineAcl($args)
    {
        $acl = $args['acl'];
        $acl->addRole(new Zend_Acl_Role('guest'), null);

        //libis_start
        // Allow 'contributor' role to make its item public/private.
        $acl->allow('contributor', 'Items', array('makePublic'));
        //libis_end

        //libis_start
        // Do not show other's private items and collections, and search on them to 'contributor' role.
        $acl->deny('contributor', array('Items', 'Collections', 'Search'), array('showNotPublic'));
        //libis_end
    }

    public function hookConfig($args)
    {
        $post = $args['post'];
        foreach($post as $option=>$value) {
            set_option($option, $value);
        }
    }

    public function hookConfigForm()
    {
        include 'config_form.php';
    }

    public function hookAdminThemeHeader($args)
    {
        $request = $args['request'];
        if($request->getControllerName() == 'plugins' && $request->getParam('name') == 'GuestUser') {
            queue_js_file('tiny_mce/tiny_mce');
            $js = "if (typeof(Omeka) !== 'undefined'){
                Omeka.wysiwyg();
            };";
            queue_js_string($js);
        }

    }
    public function hookPublicHead($args)
    {
        queue_css_file('guest-user');
        queue_js_file('guest-user');
    }

    public function hookPublicHeader($args)
    {
        $html = "<div id='guest-user-register-info'>";
        $user = current_user();
        if(!$user) {
            $shortCapabilities = get_option('guest_user_short_capabilities');
            if($shortCapabilities != '') {
                $html .= $shortCapabilities;
            }
        }
        $html .= "</div>";
        echo $html;
    }

    public function hookBeforeSaveUser($args)
    {
        if(get_option('guest_user_skip_activation_email')) {
            return;
        }
        $post = $args['post'];
        $record = $args['record'];
        //compare the active status being set with what's actually in the database
        if($record->exists()) {
            $dbUser = get_db()->getTable('User')->find($record->id);
            if($record->role == 'guest' && $record->active && !$dbUser->active) {
                try {
                    $this->_sendMadeActiveEmail($record);
                } catch (Exception $e) {
                    _log($e);
                }
            }
        }
    }

    public function hookUsersBrowseSql($args)
    {
        $select = $args['select'];
        $params = $args['params'];

        if(isset($params['sort_field']) && $params['sort_field'] == 'added') {
            $db = get_db();
            $sortDir = 'ASC';
            if (array_key_exists('sort_dir', $params)) {
                $sortDir = trim($params['sort_dir']);

                if ($sortDir === 'a') {
                    $dir = 'ASC';
                } else if ($sortDir === 'd') {
                    $dir = 'DESC';
                }
            } else {
                $dir = 'ASC';
            }
            $uaAlias = $db->getTable('UsersActivations')->getTableAlias();
            $select->join(array($uaAlias => $db->UsersActivations),
                            "$uaAlias.user_id = users.id", array());
            $select->order("$uaAlias.added $dir");
        }
    }

    public function filterPublicShowAdminBar($show)
    {
        return true;
    }

    public function filterAdminNavigationMain($navLinks)
    {
        //libis_start
        /*
         * Add a menu item to let user go to the espace dashboard (not omeka dashboard). The menu item will be added
         * to the admin navigation menu therefore will only be available to logged in users. Addition of this menu item
         * should be done before show/hide (following code) to make sure that it is always a part of the menu)
         * */
        $navLinks['Espace'] = array(
            'label' => __('My Space'),
            'uri' => public_url('/guest-user/user/me')
        );
        //libis_end

        //libis_start
        /*
         * If this plugin is configured to be hidden for the current user's role then do not add it to
         * the navigation links ($navLinks)
         * */
        $user = current_user();
        $currentClass = get_class() ;
        if(isset($user, $currentClass)){
            $pluginName = str_replace('plugin', '', strtolower($currentClass));
            $hide = get_option(strtolower($pluginName.'_'.$user->role.'_hide'));
            if($hide == 1)
                return $navLinks;
        }
        //libis_end

        $navLinks['Guest User'] = array('label' => __("Guest Users"),
                                        'uri' => url("guest-user/user/browse?role=guest"));
        return $navLinks;
    }

    public function filterPublicNavigationAdminBar($navLinks)
    {
        //Clobber the default admin link if user is guest
        $user = current_user();
        if($user) {
            if($user->role == 'guest') {
                unset($navLinks[1]);
            }

            //libis_start
            /*  Restrict profile editing via omeka admin user interface. Currently, a user is redirected to user edit part of
                the omeka admin by clicking 'Welcome, User Name' on user menu bar. This fix restricts profile editing
                via omeka admin. User should edit profile via 'My Profiles' (UserProfiles plugin) option in menu bar.
            */
            $navLinks[0]['label'] = "My Space";     /* Change the label of the menu. */
            $navLinks[0]['uri'] = absolute_url('/guest-user/user/me');
            //libis_end

            $navLinks[0]['id'] = 'admin-bar-welcome';
            $meLink = array('id'=>'guest-user-me',
                    'uri'=>url('guest-user/user/me'),
                    'label' => get_option('guest_user_dashboard_label')
            );
            $filteredLinks = apply_filters('guest_user_links' , array('guest-user-me'=>$meLink) );
            $navLinks[0]['pages'] = $filteredLinks;

            return $navLinks;
        }
        $loginLabel = get_option('guest_user_login_text') ? get_option('guest_user_login_text') : __('Login');
        $registerLabel = get_option('guest_user_register_text') ? get_option('guest_user_register_text') : __('Register');
        $navLinks = array(
                'guest-user-login' => array(
                    'id' => 'guest-user-login',
                    'label' => $loginLabel,
                    'uri' => url('guest-user/user/login')
                ),
                'guest-user-register' => array(
                    'id' => 'guest-user-register',
                    'label' => $registerLabel,
                    'uri' => url('guest-user/user/register'),
                    )
            //libis_start
            /* Add activate account link to the menu bar. */
            ,
            'guest-user-activate' => array(
                'id' => 'guest-user-activate',
                'label' => 'Activate Account',
                'uri' => url('guest-user/user/activate'),
            )
            //libis_end
                );
        return $navLinks;
    }

    public function filterGuestUserWidgets($widgets)
    {
        $widget = array('label'=> __('My Account'));
        $passwordUrl = url('guest-user/user/change-password');
        $accountUrl = url('guest-user/user/update-account');
        $html = "<ul>";
        $html .= "<li><a href='$accountUrl'>" . __("Update account info and password") . "</a></li>";

        //libis_start
        /* Add profile link to the user's setting panel. */
        $userProfile = new UserProfilesPlugin();
        $urls = $userProfile->filterGuestUserLinks(null);
        if(isset($urls) && array_key_exists("UserProfiles", $urls)){
            $userUrls = $urls["UserProfiles"];
            if(array_key_exists("uri", $userUrls))
                $html .= "<li><a href='".$userUrls["uri"]."'>" . __("Profile") . "</a></li>";

        }
        //libis_end

        $html .= "</ul>";
        $widget['content'] = $html;
        $widgets[] = $widget;

        //libis_start
        /* Create a panel for user contents. */
        $stories = $this->storyUserWidget();
        if(isset($stories) && sizeof($stories) > 0)
            $widgets [] = $stories;
        //libis_end

        return $widgets;
    }

    private function storyUserWidget(){
        $html = "";
        $widgetUserContent = array('label'=> __('My Contents'));
        $html .= "<ul>";
        $html .= "<li><a href='".url('admin/exhibits')."'>" . __("Stories") . "</a></li>";
        $html .= "<li><a href='".url('admin/items/browse')."'>" . __("Items") . "</a></li>";
        $html .= "<li><a href='".url('admin/collections/browse')."'>" . __("Collections") . "</a></li>";
        $html .= "</ul>";
        $widgetUserContent['content'] = $html;
        return $widgetUserContent;
    }

    private function _sendMadeActiveEmail($record)
    {
        $email = $record->email;
        $name = $record->name;

        $siteTitle  = get_option('site_title');
        $subject = __("Your %s account", $siteTitle);
        $body = "<p>";
        $body .= __("An admin has made your account on %s active. You can now log in to with your password at this link:", $siteTitle );
        $body .= "</p>";
        $body .= "<p><a href='" . WEB_ROOT . "/users/login'>$siteTitle</a></p>";
        $from = get_option('administrator_email');
        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyHtml($body);
        $mail->setFrom($from, "$siteTitle Administrator");
        $mail->addTo($email, $name);
        $mail->setSubject($subject);
        $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
        $mail->send();
    }
    
    public static function guestUserWidget($widget)
    {
        if(is_array($widget)) {
        $html = "<h2 class='guest-user-widget-label'>" . $widget['label'] . "</h2>";
        $html .= $widget['content'];
        return $html;
    } else {
        return $widget;
    }
    }
}

?>
