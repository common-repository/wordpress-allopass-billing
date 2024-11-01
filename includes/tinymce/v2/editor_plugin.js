// Load the language file
tinyMCE.importPluginLanguagePack('allopass', 'en');
var TinyMCE_allopass = {
    
    getInfo : function() {
        return {
            longname : 'allopass',
            author : 'allopass',
            authorurl : 'http://www.allopass.com',
            infourl : 'http://www.allopass.com',
            version : "1.0"
        };
    },
    
    getControlHTML : function(cn) {
        switch (cn) {
        case "allopass":
            return tinyMCE.getButtonHTML(cn, 'lang_allopass_desc', JS_BASE_URL+'includes/allopass.gif', 'mceallopass');
        }
        return "";
    },
    
    execCommand : function(editor_id, element, command, user_interface, value) {
        switch (command) {
        // Remember to have the "mce" prefix.
        case "mceallopass":
            // Call the script
            edInsertAllopass();
            return true;
        }
        return false;
    }
};
// Adds the plugin class to the list of available TinyMCE plugins
tinyMCE.addPlugin("allopass", TinyMCE_allopass);
