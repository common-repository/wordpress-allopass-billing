<div class="wrap">
    <h2><?echo _WPSM_CONFIG_?></h2>

    <p>
    
        <?if($this->logged == false) {?>
            <?
            if($_GET['ap_action'] == "add_user") {
            
                if($this->apo->create_account($_POST)) {
                    echo _SIGNUP_OK_;
                } else {
                    echo str_replace("%signup_url%",$this->apo->__parse_lang($this->apo->signup_code_url),_SIGNUP_KO_);
                }
                
            } else if(!empty($_POST['user']) || !empty($_POST['password'])) {?>
            
                <h3 style="color:red"><?echo _INVALID_LOGIN_DATA_?>
                <br />
                <?echo _WARN_MULTI_FAILS_?> support@allopass.com
                </h3>
                
            <?}?>
            
            <h4><?echo strtoupper(_NOT_LOGGED_)?></h4>
            <?echo _USE_FORM_BELOW_?>&nbsp;
            <a href="#" onClick="document.getElementById('login_form').style.display = 'none'; document.getElementById('new_user_form').style.display = 'block'"><?echo _CLICK_HERE_?></a>
            <?echo _GET_AP_ACCOUNT_?>.
            <br><br>
            
            <div id="login_form" style="display:block">
                <form action="" method="post">
                    <?echo _USERNAME_?>:&nbsp;<input type="text" name="user" value="">
                    <br>
                    <?echo _PASSWORD_?>:&nbsp;&nbsp;<input type="password" name="password" value="">
                    <br><br><input type="submit" value="login">
                </form> 
            </div>
            
            <div id="new_user_form" style="display:none">
                <form method="post" action="admin.php?page=ap_configuration&ap_action=add_user">
                    <input type="hidden" name="CIVILITE" value="1" />
                    <input type="hidden" name="r_soc" value="non" />
                    <input type="hidden" name="ADRESSE" value="<?echo BASE_URL?>" />
                    <input type="hidden" name="CP" value="555555" />
                    <input type="hidden" name="VILLE" value="es" />
                    <input type="hidden" name="PAYS" value="ES" />
                    <input type="hidden" name="TEL" value="55555" />
                    <input type="hidden" name="PUB" value="0" />
                    <input type="hidden" name="PUB2" value="0" />
                    <input type="hidden" name="OK" value="1" />
                    
                    <b><?echo _NEW_USER_NAME_?>:&nbsp;</b><input type="text" name="PRENOM" value="">
    
                    <br><br>
                    <b><?echo _NEW_USER_SURNAME_?>:&nbsp;</b><input type="text" name="NOM" value="">
    
                    <br><br>
                    <b><?echo _NEW_USER_BIRTHDATE_?>:&nbsp;</b>
                    <select name="Date_Day">
                        <?for($i=1;$i<32;$i++) {?>
                            <option value="<?echo $i?>"><?echo $i?></option>
                        <?}?>
                    </select>
                    <select name="Date_Month">
                        <?for($i=1;$i<13;$i++) {?>
                            <option value="<?echo $i?>"><?echo $i?></option>
                        <?}?>
                    </select>
                    <select name="Date_Year">
                        <?$ay = date("Y"); $ay++; for($i=1920;$i<$ay;$i++) {?>
                            <option value="<?echo $i?>"><?echo $i?></option>
                        <?}?>
                    </select>
                    <br>
                    <?echo _NEW_USER_CONFBIRTH_?>
                    
                    <br><br>
                    <b><?echo _NEW_USER_EMAIL_?>:&nbsp;</b><input type="text" name="EMAIL" value="">
                    <br><br>
                    <b><?echo _NEW_USER_CONFEMAIL_?>:&nbsp;</b><input type="text" name="CONFIRM_EMAIL" value="">
                    <br><br>
                    <input type="submit" value="<?echo _SEND_?>">
                </form>
            </div>

        <?} else {?>
            <h4><?echo _LOGGED_IN_?></h4>
        <?}?>

        <br />
        <hr>
        <br />
        <?echo _SELECT_LANG_?>:
        <br>
        
        <form action="" method="post">
            <select name="ap_lang">
                <?
                $langs = get_languages();
                foreach($langs as $l) {?>
                    <option <?if($l == $_COOKIE['ap_lang']) echo 'selected="selected "';?>value="<?echo $l?>"><?echo $l?></option>
                <?}?>
            </select>
            <input type="submit" value="<?echo _CHANGE_?>" />
        </form>

    </p>
</div>

