<script language="JavaScript" src="http://j.maxmind.com/app/country.js"></script>

<script>
        
    var AP_PAYINFO_IMG_BASEURL = "http://img.allopass.com/imgweb/script_normal/paliers/acces_top_";
    
    var geoip_func = "geoip_country_code"; 
    
    if (eval("typeof " + geoip_func + " == 'function'")) {
        var cc = geoip_country_code();
    } 
    
    if(cc == undefined ||cc == "") {
        cc = "FR";
    }
 
    if(AP_COUNTRIES[cc]["country"] != "") {
        if(AP_COUNTRIES[cc]["isms"] != "") {
            var img = AP_PAYINFO_IMG_BASEURL + AP_COUNTRIES[cc]["isms"] + ".gif";
        } else if(AP_COUNTRIES[cc]["itel"] != "") {
            var img = AP_PAYINFO_IMG_BASEURL + AP_COUNTRIES[cc]["itel"] + ".gif";
        } else {
            ap_data = "5";
        }
    }

    function coco(cc) {
        if(AP_COUNTRIES[cc]["isms"] != "") {
            img = AP_PAYINFO_IMG_BASEURL + AP_COUNTRIES[cc]["isms"] + ".gif";
        } else if(AP_COUNTRIES[cc]["itel"] != "") {
            img = AP_PAYINFO_IMG_BASEURL + AP_COUNTRIES[cc]["itel"] + ".gif";
        } else {
            img = AP_PAYINFO_IMG_BASEURL + AP_COUNTRIES["UK"]["itel"] + ".gif";
        }
        for(i=0;i<n;i++) {
            document.getElementsByName("ap_head")[i].src = img;
        }
    }

    function write_protected_post() {
        if(PROTECTED_POST[cc] != undefined) {
            document.write(PROTECTED_POST[cc])
        } else {
            document.write(PROTECTED_POST["DEFAULT"])
        }
    }
    function write_invalid_code() {
        if(INVALID_CODE[cc] != undefined) {
            document.write(INVALID_CODE[cc])
        } else {
            document.write(INVALID_CODE["DEFAULT"])
        }
    }
        
</script>