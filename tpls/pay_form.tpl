<div style="display:inline">
    <div id="#doc_id#" style="display: none; float: left; border:1px solid black; padding: 10px; width:320px;">
        <div style="display:block">
        #flags#
        </div>
        <img name="ap_head" src="">
        <br>
        <form name="APform" action="#url_ap_includes#check_code.php" method="post">
            <input type="hidden" name="SITE_ID" value="#site_id#" />
            <input type="hidden" name="DOC_ID" value="#doc_id#" />
            <input type="hidden" name="AUTH" value="#doc_auth#" />
            <input type="hidden" name="RECALL" value="1" />
            <input type="hidden" name="LG_SCRIPT" value="es_uk" />
            <input type="hidden" name="DESTINATION" value="#destination#" />
            #insert_codes#
            <br /><br><br><br>
            <input type="button" name="APsub" value="" onclick=" this.form.submit();this.form.APsub.disabled=true;" style="border:0px;margin:0px;padding:0px;width:48px;height:18px;background:url('http://www.allopass.com/imgweb/common/bt_ok.gif');" /><br />
        </form>
        <a href="#null" onclick="window.open('https://secure.allopass.com/show_ccard.php4?LG=UK&SITE_ID=#site_id#&DOC_ID=#doc_id#','ccard','toolbar=0,location=0,directories=0,status=1,scrollbars=1,resizable=1,copyhistory=0,menuBar=0,width=675,height=700');">
        <img src="http://www.allopass.com/imgweb/script/uk/achat_cc.jpg" border="0" alt="If your country is not listed, click here">
        </a>
        <span align="right" style="font-size:10px"><a href="http://www.wordpressallopass.com">http://www.wordpressallopass.com</a></span>
    </div>
</div>
<script>
var n = document.getElementsByName("ap_head").length;
for(i=0;i<n;i++) {
    document.getElementsByName("ap_head")[i].src = img;
}
</script>
