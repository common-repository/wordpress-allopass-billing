<?php

/*
Plugin Name: Allopass
Plugin URI: http://www.wordpressallopass.com/
Description: this plugin allow to integrate wordpress with Allopass Billing System..
Version: 1.3
Author: allopass
Author URI: http://www.wordpressallopass.com/
*/


class wp_allopass {

    var $apo;
    var $logged = false;
    var $version = "1.3";
    var $latest_version_url = "http://www.wordpressallopass.com/plugins/plugin_%version%.zip";

    function wp_allopass() {
        global $wpdb;

        $this->apo = new allopass();

        // check autentification

        // check if we already have logged and we have the session cookie
        if(!empty($_COOKIE['admin_allopass_session'])) {
            $this->apo->http_request->addCookie("SID",$_COOKIE['admin_allopass_session']);
            $this->logged = true;
        }
         else {
            // we have to login
            $user = $pass = "";

            // check if we're logging in
            if(!empty($_POST['user']) && !empty($_POST['password'])) {
                $user = $_POST['user'];
                $pass = $_POST['password'];
            } else {
                // check if we have the login data in the cookie
                if(!empty($_COOKIE['admin_allopass'])) {
                    list($user,$pass) = explode("|%|",$_COOKIE['admin_allopass']);
                }
            }

            // if we have user and password
            if($user != "" && $pass != "") {
                // create an ap object with the data
                $this->apo->user_email = $user;
                $this->apo->user_pass = $pass;
                // and check if it's a valid user

                if($this->apo->check_access()) {
                    $this->logged = true;
                    // check if we have the cookie
                    if(!empty($_POST['user']) && !empty($_POST['password'])) {
                        // user data cookie
                        setcookie("admin_allopass", $user."|%|".$pass, time()+3600*24*365, "/");
                    }
                }
            }
            // else we're not logged in and we are not trying to login
         }

        // add menus / subemnus
        add_action('admin_menu', array(&$this, 'allopass_menu'));

        // add filters

        add_filter('the_content', array(&$this, 'parse_content'), 0);
        add_filter('the_excerpt', array(&$this, 'parse_content'), 0);
        add_filter('comment_text',array(&$this, 'parse_content'), 0);

        // editor button
        $this->add_buttons();

    }

    function allopass_menu() {
        // create menus
        add_menu_page('Allopass', '&nbsp;Allopass&nbsp;',8, __FILE__, array(&$this, 'display_allopass_page'));
        add_submenu_page(__FILE__, _WPSM_MYDOCS_, _WPSM_MYDOCS_, 9, 'ap_mydocuments', array(&$this, 'display_mydocuments_page'));
        add_submenu_page(__FILE__, _WPSM_STATS_, _WPSM_STATS_, 9, 'ap_stats', array(&$this, 'display_stats_page'));
        add_submenu_page(__FILE__, _WPSM_CONFIG_, _WPSM_CONFIG_, 9, 'ap_configuration', array(&$this, 'display_configuration_page'));
        add_submenu_page(__FILE__, _WPSM_HELP_, _WPSM_HELP_, 9, 'ap_help', array(&$this, 'display_help_page'));

        if($this->new_version_available()) {
            add_submenu_page(__FILE__, _WPSM_UPDATE_, _WPSM_UPDATE_, 9, 'ap_update', array(&$this, 'display_update_page'));
        }
    }

    // functions to show submenus

    function display_allopass_page() {
        include_once PATH_AP_TPLS."home.tpl";
    }

    function display_mydocuments_page() {
        include_once PATH_AP_TPLS."documents.tpl";
    }

    function display_stats_page() {
        include_once PATH_AP_TPLS."stats.tpl";
    }

    function display_configuration_page() {
        include_once PATH_AP_TPLS."config.tpl";
    }

    function display_help_page() {
        include_once PATH_AP_TPLS."help.tpl";
    }

    function display_update_page() {
        include_once PATH_AP_TPLS."update.tpl";
    }

    function parse_content($content) {

        global $wp_query;
        global $flgs;
        global $js;
        global $js_dsp;

        $pay_form_tpl = file_get_contents(PATH_AP_TPLS."pay_form.tpl");
        $pay_form_tpl = str_replace("#url_ap_includes#",URL_AP_INCLUDES,$pay_form_tpl);
        $pay_form_tpl = str_replace("#flags#",$flgs,$pay_form_tpl);

        if($js_dsp == false) {
            echo $js;
            include_once PATH_AP_INCLUDES."js4payform.js";
            include_once PATH_AP_LANGS."public.lang";
            $js_dsp = true;
        }


        // check if we are allowed to watch the content

        // get the protected urls
        $protected_urls = $this->get_protected_docs("url");

        if(strstr(CURRENT_URL,"wp-admin")) return;

        if(count($protected_urls)) {
            // check if we want so see a protected one
            foreach($protected_urls as $purl) {
                if(empty($purl)) continue;
                if(strstr(CURRENT_URL,$purl) !== false || strstr(get_permalink($wp_query->post->id),$purl) !== false) {

                    // it's a protected one
                    // check the code

                    // get te params sent by check_code.php
                    if(isSet($_COOKIE['AP_AC'])) {
                        list($recall,$auth) = explode("|",base64_decode($_COOKIE['AP_AC']));

                        // ask the server for the correctness of the code
                        $r=@file("http://www.allopass.com/check/vf.php4?CODE=$recall&AUTH=$auth");
                        // parse the answer
                    }

                    if (!isSet($_COOKIE['AP_AC']) || (ereg("OK",$r[0]) === false)) {

                        // access denied
                        // show the pay form
                        // get the form vars
                        $pdoc = $this->get_protected_docs("full",$purl);
                        $pdoc = $pdoc[0];
                        list($site_id,$doc_id) = explode("/",$pdoc['auth']);

                        $pay_form = str_replace("#site_id#",$site_id,$pay_form_tpl);
                        $pay_form = str_replace("#doc_id#",$doc_id,$pay_form);
                        $pay_form = str_replace("#doc_auth#",$pdoc['auth'],$pay_form);
                        $pay_form = str_replace("#destination#",$wp_query->post->guid,$pay_form);

                        for($j=0;$j<$pdoc['nbr'];$j++) {
                            $num_codes_html .= '<input type="text" size="8" maxlength="10" value="Code '.($j+1).'" name="CODE'.$j.'" onfocus="if (this.form.CODE'.$j.'.value==\'Code '.($j+1).'\') this.form.CODE'.$j.'.value=\'\'" style="background-color: #E7E7E7; border-bottom: #000080 1px solid; border-left: #000080 1px solid; border-right: #000080 1px solid; border-top: #000080 1px solid; color: #000080; cursor: text; font-family: Arial; font-size: 10pt; font-weight:bold; letter-spacing: normal; width: 70px; text-align: center;">';
                        }
                        $pay_form = str_replace("#insert_codes#",$num_codes_html,$pay_form);

                        // do we come from check code?
                        if($_COOKIE['AP_AC'] == "KO") {
                            $ret = "<script>write_invalid_code()</script><br /><br />";
                        }

                        $post_content = split("\n",$content);
                        //preg_match_all("/<p>(.*)<\/p>/",$content,$post_content);
                        $ret .= $post_content[0];

                        $ret .= "
                        <span style='cursor:pointer;' onClick=\"document.getElementById('".$doc_id."').style.display = 'block'; document.getElementById('".$doc_id."').focus(); this.style.display='none';\">
                            <script>write_protected_post()</script>
                        </span>";
                        $ret .= $pay_form;

                        return $ret;

                    } else {
                        // warn the user about leaving the post?
                        /*$ret = _WARN_LEAVE_POST_;
                        $ret .= $content;
                        return $ret;*/
                    }

                    break; // we have parsed the url we're in

                }

            }
        }

        // look for allopasses forms

        $doc_pattern = '/\[allopass id="(.*)?"\](.*)?\[\/allopass\]/';
        $docs = preg_match_all($doc_pattern,$content,$results);
        $num_docs = count($results[0]);

        for($i=0;$i<$num_docs;$i++) {
            list($site_id,$doc_id,$user_id,$num_codes) = explode("/",$results[1][$i]);

            $pay_form = str_replace("#site_id#",$site_id,$pay_form_tpl);
            $pay_form = str_replace("#doc_id#",$doc_id,$pay_form);
            $doc_auth = $site_id."/".$doc_id."/".$user_id;
            $pay_form = str_replace("#doc_auth#",$doc_auth,$pay_form);
            $pay_form = str_replace("#destination#",$wp_query->post->guid,$pay_form);

            $num_codes_html = "";
            $num_codes = (int) $num_codes;
            for($j=0;$j<$num_codes;$j++) {
                $num_codes_html .= '<input type="text" size="8" maxlength="10" value="Code '.($j+1).'" name="CODE'.$j.'" onfocus="if (this.form.CODE'.$j.'.value==\'Code '.($j+1).'\') this.form.CODE'.$j.'.value=\'\'" style="background-color: #E7E7E7; border-bottom: #000080 1px solid; border-left: #000080 1px solid; border-right: #000080 1px solid; border-top: #000080 1px solid; color: #000080; cursor: text; font-family: Arial; font-size: 10pt; font-weight:bold; letter-spacing: normal; width: 70px; text-align: center;">';
            }

            if($_COOKIE['AP_AC'] == "KO") {
                $pf = "<script>write_invalid_code()</script><br /><br />";
            }

            $pf .= "<span style='cursor:pointer;' onClick=\"document.getElementById('".$doc_id."').style.display = 'block'; document.getElementById('".$doc_id."').focus(); this.style.display='none';\">".$results[2][$i]."</span>";
            $pf .= str_replace("#insert_codes#",$num_codes_html,$pay_form);
            $pay_form = $pf;

            $content = str_replace($results[0][$i],$pay_form,$content);
        }


        return $content;
    }

    function check_protected_docs() {
        global $wpdb;

        $urlsdb = $urls_to_add = $urls_to_delete = Array();

        // check if table exists
        $sql = "CREATE TABLE IF NOT EXISTS wp_ap_protected_docs (
            doc_id INT NOT NULL AUTO_INCREMENT ,
            name varchar(255) NOT NULL ,
            auth varchar(255) NOT NULL ,
            nbr INT NOT NULL ,
            url TEXT NOT NULL ,
            PRIMARY KEY (doc_id)
            )";
        $wpdb->query($sql);

        // get the urls in our db
        $urlsdb = $this->get_protected_docs();
        // check that all the urls protected in ap are in my db
        foreach($this->apo->user_data['documents'] as $apdoc)
            if(!in_array($apdoc['url'],$urlsdb)) {
                // those are the "values" of the sql insert
                $urls_to_add[] = "'".$apdoc['name']."','".$apdoc['auth']."',".$apdoc['nbr'].",'".$apdoc['url']."'";
            }
        if(count($urls_to_add)) {
            $sql = "INSERT INTO wp_ap_protected_docs(name, auth, nbr, url) VALUES ";
            $sql .= "(" . implode("),(",$urls_to_add) .")";
            $wpdb->query($sql);
        }

        // check that all the urls in my db must be protected
        foreach($urlsdb as $myurl)
            if(!in_array($myurl,$this->apo->user_data['documents_urls'])) $urls_to_delete[] = $myurl;
        if(count($urls_to_delete)) {
            $sql = " DELETE FROM wp_ap_protected_docs WHERE url IN ";
            $sql .= "('". implode("','",$urls_to_delete) . "');";
            $wpdb->query($sql);
        }


    }

    // get our protected urls
    function get_protected_docs($type="full",$url="") {
        global $wpdb;

        $urlsdb = Array();

        $sql = "SELECT doc_id, name, auth, nbr, url FROM wp_ap_protected_docs";
        if($url != "") {
            $sql .= " WHERE url = '".$url."'";
        }
        $urls = $wpdb->get_results($sql);

        $num_urls = count($urls);

        switch($type) {
            // return just urls
            // it's used to check the actual url in parse_content
            case "url":
                for($i=0;$i<$num_urls;$i++) {
                    $urlsdb[] = $urls[$i]->url;
                }
                break;
            case "full":
            default:
                for($i=0;$i<$num_urls;$i++) {
                    $urlsdb[] = Array(
                                    "doc_id" => $urls[$i]->doc_id,
                                    "name" => $urls[$i]->name,
                                    "auth" => $urls[$i]->auth,
                                    "nbr" => $urls[$i]->nbr,
                                    "url" => $urls[$i]->url);
                    $urlsdb['urls'] = $urls[$i]->url;
                }
        }

        return $urlsdb;
    }

    // functions to add the editor button

    function add_buttons() {

		global $wp_db_version;
        // Create the buttons based on the WP version number
		// WordPress 2.5+ (TinyMCE 3.x)
		if ( $wp_db_version > 6124 ) {
			add_filter( 'mce_external_plugins', array(&$this, 'mce_external_plugins') );
			add_filter( 'mce_buttons_3', array(&$this, 'mce_buttons') );
			add_action('edit_form_advanced', array(&$this, 'add_tmcebutton_js'));
		} elseif ($wp_db_version > 4772) {
		     // WordPress 2.1+ (TinyMCE 2.x)
			add_filter('mce_plugins', array(&$this, 'mce_plugins'));
			add_filter('mce_buttons', array(&$this, 'mce_buttons'));
			add_action('tinymce_before_init', array(&$this, 'tinymce_before_init'));
			add_action('edit_form_advanced', array(&$this, 'add_tmcebutton_js'));
		} else {
			buttonsnap_separator();
			buttonsnap_jsbutton(PATH_AP_INCLUDES . 'allopass.gif', __('Allopass', 'allopass'), "edInsertAllopass();");
			add_action('edit_form_advanced', array(&$this, 'add_tmcebutton_js'));
		}
    }

    // wp > 2.5
	function mce_external_plugins( $plugins ) {
		// WordPress 2.5
		$plugins['allopass'] = URL_AP_INCLUDES. 'tinymce/v3/editor_plugin.js';
		return $plugins;
	}
    // used to insert button in wordpress 2.1x editor
    function mce_plugins($plugins) {
        array_push($plugins, "separator", "allopass");
        return $plugins;
    }

    // Tell TinyMCE that there is a plugin (wp2.1)
    function mce_buttons($buttons) {
        array_push($buttons, "allopass");
        $this->add_tmcebutton_js();
        return $buttons;
    }

    // Load the TinyMCE plugin : editor_plugin.js (wp2.1)
	function tinymce_before_init() {
		// WordPress 2.1
		echo 'var JS_BASE_URL = "'.URL_AP_ROOT.'";
        ';
        echo 'tinyMCE.loadPlugin("allopass", "'.URL_AP_INCLUDES.'tinymce/v2/");'."\n";
        return;
	}


    function add_tmcebutton_js() {

        echo "<script type=\"text/javascript\"><!--\n" .
             "function edInsertAllopass() {\n" .
             "    var nw = window.open('admin.php?page=ap_mydocuments&m=nd','docs','width=800;height=500;screenX=100').focus();\n" .
             "}\n\n" .
             "function add_document(name,id) {\n" .
             "    var text = '[allopass id=\"'+id+'\"]'+name+'[/allopass]<br />';\n" .
             "    if (window.tinyMCE) {\n" .
             "        text = text.replace(/\\n/g, '<br />');" .
             "        window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, text);\n" .
             "        tinyMCE.execCommand('mceCleanup');\n" .
             "    }\n" .
             "    else {\n" .
             "        edInsertContent(edCanvas, text);\n" .
             "    }\n" .
             "}\n" .
             "//--></script>\n";
    }

    function new_version_available() {
        $latest_version_url = str_replace("%version%",$this->version,$this->latest_version_url);
        $this->apo->__process_request($latest_version_url);
        if( $this->apo->http_request->headers['content-type'] == "application/zip" ) {
            return false;
        } else {
            return true;
        }
    }

}

$host = $_SERVER['HTTP_HOST'];
$host = str_replace("www.","",$host);
define('HOST_NAME',$host);

define('BASE_URL',get_option('siteurl')."/");

define('URL_AP_ROOT',BASE_URL."wp-content/plugins/allopass/");
define('URL_AP_INCLUDES',URL_AP_ROOT."includes/");
define('URL_AP_TPLS',URL_AP_ROOT."tpls/");

define('PATH_AP_ROOT', dirname(__FILE__)."/");
define('PATH_AP_INCLUDES', PATH_AP_ROOT."includes/");
define('PATH_AP_TPLS', PATH_AP_ROOT."tpls/");
define('PATH_AP_LANGS', PATH_AP_INCLUDES."langs/");

include_once PATH_AP_INCLUDES."config.php";

$wpap = new wp_allopass();

?>
