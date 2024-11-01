<div class="wrap">
    <h2><?echo _WPSM_MYDOCS_?></h2>

<?if($this->logged == true) {

    $this->apo->check_site();

    // check if we're doing an action
    if($_GET['ap_action'] == "add_document") {
        $this->apo->new_document();
    } else if($_GET['ap_action'] == "delete_document") {
        $this->apo->delete_document($_GET['id_document']);
    }

    $this->apo->parse_documents_xml();

    // ok, we have the documents
    // let's check all of them are in the database (wp_ap_protected_urls)
    $this->check_protected_docs();
    ?>
    <a href="#" onClick="document.getElementById('new_doc_form').style.display = 'block'"><?echo _ADD_NEW_DOC_?></a>
    <div id="new_doc_form" style="display:none">
        <br><br>
        <form method="post" action="admin.php?page=ap_mydocuments&ap_action=add_document" name="docs2">
            <input type="hidden" name="SITE_ID" value="<?echo $this->apo->user_data["site"]["id"]?>" />
            <input type="hidden" name="DOC_ID" value="" />
            <input type="hidden" name="DOC_TYPE_ACCES" value="A" />
            <input type="hidden" id="urlAcces" name="DOC_URL_ACCES" size="40" maxlength="150" value="<?echo BASE_URL?>" />
            <input type="hidden" id="urlErreur" name="DOC_URL_ERROR" size="40" maxlength="150" value="" />
            <input type="hidden" name="NO_PRE_SEL" value="0" />
            <input type="hidden" name="CB" value="29" />
            <input type="hidden" name="DOC_MAX_USE" value="0" />
            
            <input type="hidden" name="DOC_PALIER_ES" value="39" />
            <input type="hidden" name="DOC_PALIER_S4" value="25" />
            <input type="hidden" name="DOC_PALIER_FR" value="2" />
            <input type="hidden" name="DOC_PALIER_BE" value="15" />
            <input type="hidden" name="DOC_PALIER_S5" value="51" />
            <input type="hidden" name="DOC_PALIER_CH" value="6" />
            <input type="hidden" name="DOC_PALIER_S2" value="23" />
            <input type="hidden" name="DOC_PALIER_LU" value="12" />
            <input type="hidden" name="DOC_PALIER_T8" value="70" />
            <input type="hidden" name="DOC_PALIER_DE" value="4" />
            <input type="hidden" name="DOC_PALIER_UK" value="5" />
            <input type="hidden" name="DOC_PALIER_S6" value="33" />
            <input type="hidden" name="DOC_PALIER_CA" value="14" />
            <input type="hidden" name="DOC_PALIER_T9" value="72" />
            <input type="hidden" name="DOC_PALIER_U2" value="80" />
            <input type="hidden" name="DOC_PALIER_X3" value="123" />
            <input type="hidden" name="DOC_PALIER_NL" value="41" />
            <input type="hidden" name="DOC_PALIER_S7" value="71" />
            <input type="hidden" name="DOC_PALIER_AT" value="11" />
            <input type="hidden" name="DOC_PALIER_IT" value="13" />
            <input type="hidden" name="DOC_PALIER_T2" value="53" />
            <input type="hidden" name="DOC_PALIER_US" value="115" />
            <input type="hidden" name="DOC_PALIER_S3" value="24" />
            <input type="hidden" name="DOC_PALIER_S9" value="36" />
            <input type="hidden" name="DOC_PALIER_X1" value="114" />
            <input type="hidden" name="DOC_PALIER_W1" value="113" />
            <input type="hidden" name="DOC_PALIER_T4" value="61" />
            <input type="hidden" name="DOC_PALIER_T1" value="48" />
            <input type="hidden" name="DOC_PALIER_T5" value="62" />
            <input type="hidden" name="DOC_PALIER_U6" value="88" />
            <input type="hidden" name="DOC_PALIER_T6" value="63" />
            <input type="hidden" name="DOC_PALIER_T3" value="60" />
            <input type="hidden" name="DOC_PALIER_U4" value="86" />
            <input type="hidden" name="DOC_PALIER_V1" value="101" />
            <input type="hidden" name="DOC_PALIER_V7" value="108" />
            <input type="hidden" name="DOC_PALIER_V9" value="111" />
            <input type="hidden" name="DOC_PALIER_V6" value="107" />
            <input type="hidden" name="DOC_PALIER_V5" value="105" />
            <input type="hidden" name="DOC_PALIER_V4" value="104" />
            <input type="hidden" name="DOC_PALIER_V3" value="103" />
            <input type="hidden" name="DOC_PALIER_U9" value="91" />
            <input type="hidden" name="DOC_PALIER_V2" value="102" />
            <input type="hidden" name="DOC_PALIER_ZZ" value="110" />
            <input type="hidden" name="DOC_PALIER_U1" value="73" />
            <input type="hidden" name="DOC_PALIER_U5" value="87" />
            <input type="hidden" name="DOC_PALIER_T7" value="69" />
            <input type="hidden" name="DOC_PALIER_U8" value="90" />
            <input type="hidden" name="DOC_PALIER_U7" value="89" />


           
            <b><?echo _NEW_DOCUMENT_NAME_TIT_?>:</b>&nbsp;<input type="text" id="nomDocument" name="DOC_NOM" size="40" maxlength="50" value="" />
            <br>
            <?echo _NEW_DOCUMENT_NAME_DESC_?>
            <br><br>
            <b><?echo _NEW_DOCUMENT_URL_TIT_?>:</b>&nbsp;<input type="text" id="urlDocument" name="DOC_URL_DOC" size="40" maxlength="150" value="http://" />
            <br>
            <?echo _NEW_DOCUMENT_URL_DESC_?>
            <br><br>
            <b><?echo _NEW_DOCUMENT_TEST_CODE_TIT_?>:</b>&nbsp;<input type="text" id="codeTest" name="DOC_TEST_CODE" size="8" maxlength="8" value="" />
            <br>
            <?echo _NEW_DOCUMENT_TEST_CODE_DESC_?>
            <br><br>
            <b><?echo _NEW_DOCUMENT_NUM_CODES_TIT_?>:</b>&nbsp;
            <select name="DOC_NBR">
              <option value="1">1 <?echo _NEW_DOCUMENT_CODE_?></option>
              <option value="2">2 <?echo _NEW_DOCUMENT_CODE_?></option>
              <option value="3">3 <?echo _NEW_DOCUMENT_CODE_?></option>
              <option value="4">4 <?echo _NEW_DOCUMENT_CODE_?></option>
              <option value="5">5 <?echo _NEW_DOCUMENT_CODE_?></option>
            </select>
            <br>
            <?echo _NEW_DOCUMENT_NUM_CODES_DESC_?>
            
            <br><br>
            <input type="submit" value="<?echo _ADD_?>" />
        </form>
    </div>
    
    <br><br>    
    <h3><?echo _DOCUMENT_LIST_?></h3>
    <?
     if($_GET['m'] == "nd") {
        echo $this->apo->generate_tree("radio");
     } else {
        echo $this->apo->generate_tree();
    }

} else {?>

    <h4><?echo strtoupper(_NOT_LOGGED_)?></h4>
    <?echo _LOGIN_OR_REGISTER_?> <a href="<?echo BASE_URL?>wp-admin/admin.php?page=ap_configuration"><?echo _CONFIG_PAGE_?></a>.
<?}?>

</div>
