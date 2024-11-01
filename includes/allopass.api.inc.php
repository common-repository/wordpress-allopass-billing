<?php
// APi

class allopass {

    var $user_email;
    var $user_pass;
    var $signup_code_url = "http://%lang%.allopass.com/index.php4";
    var $signup_url = "http://%lang%.allopass.com/inscription2.php4";
    var $signup2_url = "http://%lang%.allopass.com/inscription4.php4";
    var $login_url = "http://www.allopass.com/login2.php4";
    var $sites_url = "http://www.allopass.com/webm_docs.php4";
    var $sites_submit_new_url = "http://www.allopass.com/webm_edit_sites2.php4";
    var $sites_edit_url = "http://www.allopass.com/webm_edit_sites1.php4?SITE_ID=%site_id%&ACTION=EDIT";
    var $docs_url = "http://www.allopass.com/webm_edit_docs1.php4?SITE_ID=%site_id%";
    var $docs_new_url = "http://%lang%.allopass.com/webm_edit_docs2.php4?SITE_ID=%site_id%&DOC_TYPE_ACCES=A";
    var $docs_submit_url = "http://www.allopass.com/webm_edit_docs3.php4";
    var $docs_edit_url = "http://www.allopass.com/webm_edit_docs2.php4?SITE_ID=%site_id%&DOC_ID=%doc_id%&ACTION=EDIT&DOC_TYPE_ACCES=A";
    var $docs_del_url = "http://www.allopass.com/webm_edit_docs2.php4?DOC_ID=&DOC[%doc_id%]=1&SITE_ID=%site_id%&DOC_TYPE_ACCES=A&CONF=1&ACTION=DEL";
    var $stats_url = "http://%lang%.allopass.com/webm_stats.php4";

    var $user_data = Array();

    function allopass() {
        $this->user_email = $user;
        $this->user_pass = $pass;

        $this->http_request = new HTTP_Request();

    }

    function check_access() {

        $postdata_login["EMAIL"] = $this->user_email;
        $postdata_login["PASSWORD"] = $this->user_pass;

        $this->__process_request($this->login_url,$postdata_login);

        if(strpos($this->http_request->headers['location'],"webm_accueil") !== false) {
            // set session cookie
            foreach($this->http_request->cookies as $cookie) {
                if($cookie['name'] == "SID") {
                    setcookie("admin_allopass_session", $cookie['value'], time() + 60*30, "/");
                    $this->http_request->session_cookie = $cookie['value'];
                }
            }
            return true;
        }
        return false;
    }

    /**
     *     generate the estructure of the documents:
     *     $this->user_data['site'] = Array("id","name");
     *     $this->user_data['site']['documents'] = Array("id","name");
     */


    function check_site($v=0) {

        // get all the sites
        $this->__process_request($this->sites_url);

        $pattern = '/SITE_ID=([0-9]*)/';
        preg_match_all($pattern,$this->http_request->body,$sites);
        $sitesids = array_unique($sites[1]);

        // have to access to the edit page of the sites to get the name
        $this->user_data['site'] = Array();

        foreach($sitesids as $siteid) {
            $urlsite = str_replace("%site_id%",$siteid,$this->sites_edit_url);

            $this->__process_request($urlsite);

            // get the name of the site
            $pattern = '/<input type="text" name="SITE_NOM" size="40" maxlength="50" value="(.*)">/';
            preg_match_all($pattern,$this->http_request->body,$sitename);
            // if it's the name of our site, it's ok
            // else if we don't find it we'll have to create it
            if( strcmp(strtolower($sitename[1][0]),strtolower(HOST_NAME)) == 0 ) {
                $this->user_data['site'] = Array("name" => $sitename[1][0], "id" => $siteid);
                return true;
            }
        }

        if(empty($this->user_data['site'])) {
            // ups, we haven't find the site: we have to create it
            $postdata = Array();
            $postdata['SITE_NOM'] = HOST_NAME;
            $postdata['SITE_CATEGORIE'] = "G";
            $postdata['SITE_URL'] = "http://".HOST_NAME;
            $this->__process_request($this->sites_submit_new_url,$postdata);

            if($v!=1) {
                // avoid oo recursivity
                // PENDIENTE
                $this->check_site(1);
            }

        }

    }

    function parse_documents_xml() {

        $user_login = $this->get_user_login();

        require_once PATH_AP_INCLUDES."xml_parser.class.php";
        $user_data_xml = "http://www.allopass.com/api/get_documents.php?EMAIL=".$user_login['user']."&PASSWORD=".$user_login['pass']."&IDS=".$this->user_data['site']['id'];
        $user_data = xml2Array(file_get_contents($user_data_xml));

        $num_docs = count($user_data['allopass']);
        // if we have just one element we convert it to an array
        if($num_docs == 0) {
            $this->user_data['documents'] = Array();
            $this->user_data['documents_urls'] = Array();
            return;
        }

        // check if it's just one doc
        if(isSet($user_data['allopass']['document']['test_code'])) {
            $user_data['allopass']['document'] = Array($user_data['allopass']['document']);
        }

        foreach($user_data['allopass']['document'] as $doc) {
            $this->user_data['documents'][] = Array(
                                                "name" => $doc['name']['value'],
                                                "auth" => $doc['auth']['value'],
                                                "nbr" => $doc['number']['value'],
                                                "url" => $doc['doc_url']['value']);
            $this->user_data['documents_urls'][] = $doc['doc_url']['value'];
        }

    }

    // generate the js tree for the sites/documents
    function generate_tree($type="list") {

        if($type=="radio") {
            $li_style = ' style="list-style-image: none"';
            $html = "<script>
                    function validaDoc(f) {
                        if(f.link_name.value == '') {
                            alert('"._JS_LINK_TEXT_."');
                            return false;
                        }
                        return true;
                    }
                    </script>";

            if(!empty($_POST['link_name']) && !empty($_POST['id'])) {
                $html .= "<script>
                    window.opener.add_document('".str_replace("'","\"",urldecode($_POST['link_name']))."','".$_POST['id']."')
                    self.close();
                </script>";
            }
            $html .= '<form action="#" method="post" onSubmit="return validaDoc(this)">';
            $html .= _INSERT_LINK_TEXT_."&nbsp;";
            $html .= '<input type="text" name="link_name" value="'._DOWNLOAD_DOC_TXT_.'" /><br /><br />';
        }

        if(count($this->user_data['documents'])) {
            $html .= "<ul".$li_style.">";
            foreach($this->user_data['documents'] as $doc) {
                $html .= "<li>";
                    if($type == "radio") {
                        $html .= '&nbsp;&nbsp;<input type="radio" name="id" value="'.$doc['auth'].'/'.$doc['nbr'].'" />';
                    }
                    $html .= $doc['name'];
                    if($type != "radio") {
                        list($site,$doc_id,$u) = explode("/",$doc['auth']);
                        $html .= '&nbsp;&nbsp;<a style="text-decoration: none" href="admin.php?page=ap_mydocuments&ap_action=delete_document&id_document='.$doc_id.'"><img src="'.URL_AP_INCLUDES.'delete.gif" border="0"></a>';
                    }
                $html .= "</li>";
            }
            $html .= "</ul>";
        } else {
            $html .= "<ul><li>"._NO_DOCUMENTS_."</li></ul>";
        }

        if($type == "radio") {
            $html .= '<br /><input type="submit" value="'._ADD_.'" />';
            $html .= "</form>";
        }

        $html .= "<br /><br />";

        return $html;
    }

    function get_user_login() {
        list($user,$pass) = explode("|%|",$_COOKIE['admin_allopass']);
        return Array("user" => $user, "pass" => $pass);
    }

    function create_account($data) {
        // first, we need to get the session cookie with the adv value
        $this->__process_request($this->__parse_lang($this->signup_code_url));

        if(count($this->http_request->cookies)) {
            foreach($this->http_request->cookies as $cookie) {
                if($cookie['name'] == "SID") {
                    $this->http_request->addCookie("SID",$cookie['value'],0,"/");
                    break;
                }
            }

            // we have the session cookie in the request, we can send the signup form
            $this->__process_request($this->__parse_lang($this->signup_url),$_POST);

            if($this->http_request->headers['location'] == "inscription3.php4") {

                if(count($this->http_request->cookies)) {
                    foreach($this->http_request->cookies as $cookie) {
                        if($cookie['name'] == "SID") {
                            $this->http_request->addCookie("SID",$cookie['value'],0,"/");
                            break;
                        }
                    }
                }

                $this->__process_request($this->__parse_lang($this->signup2_url));
                if(strstr($this->http_request->headers['location'],"inscription5.php4?EMAIL=")) {
                    return true;
                }

            } else if(strstr($this->http_request->headers['location'],"inscription.php4")) {
                preg_match("/ERRMSG=(.*)&/",$this->http_request->headers['location'],$r);
                $err = urldecode($r[1]);
                echo "<h3><u>".$err."</u></h3>";
            }

        }

        return false;

    }

    function new_document() {
        $this->__process_request($this->docs_submit_url,$_POST);

    }

    function delete_document($id) {

        $url = str_replace("%site_id%",$this->user_data['site']['id'],$this->docs_del_url);
        $url = str_replace("%doc_id%",$id,$url);

        $this->__process_request($url);

    }

    function get_stats() {
        $this->__process_request($this->__parse_lang($this->stats_url));

        $regex = '/<\/form>(.*)<br\/>/s';
        preg_match($regex,$this->http_request->body,$res);

        $form = str_replace("src=\"","src=\"http://www.allopass.com",$res[1]);
        $form = str_replace("webm_infos_url",$this->__parse_lang("http://%lang%.allopass.com/webm_infos_url"),$form);

        $form = str_replace("src=\"","src=\"http://www.allopass.com",$form);
        $form = str_replace("webm_stats",$this->__parse_lang("http://%lang%.allopass.com/webm_stats"),$form);

        return $form;

    }

    // gets the langauage
    function __parse_lang($url) {
        global $lang_code;
        return str_replace("%lang%",$lang_code,$url);
    }

    function __process_request($url,$postdata=Array()) {

        $this->http_request->setURL($url);
        if(count($postdata)) {
            $this->http_request->setMethod(HTTP_REQUEST_METHOD_POST);
            $this->http_request->addPostData($postdata);
        } else {
            $this->http_request->setMethod(HTTP_REQUEST_METHOD_GET);
        }
        $this->http_request->sendRequest();
        $this->http_request->body = $this->http_request->getResponseBody();
        $this->http_request->headers = $this->http_request->getResponseHeader();
        $this->http_request->cookies = $this->http_request->getResponseCookies();
    }

}
?>
