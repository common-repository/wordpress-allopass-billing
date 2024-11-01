<div class="wrap">
    <h2><?echo _WPSM_HELP_?></h2>
    
    <ol>
        <li>
            <b><?echo _HELP_CREATE_ACCOUNT_?></b>
            <br>
            <?echo _HELP_CREATE_ACCOUNT_DET_1_?>
                <a href="#" onClick="document.getElementById('new_user_form').style.display = 'none'; document.getElementById('new_user_form').style.display = 'block'">
                    <?echo _IN_THIS_FORM_?>.
                </a>
            <br>
            <div id="new_user_form" style="display:none">
                <form method="post" action="admin.php?page=ap_configuration&ap_action=add_user">
                    <b><?echo _NEW_USER_NAME_?>:&nbsp;</b><input type="text" name="prenom" value="">
    
                    <br><br>
                    <b><?echo _NEW_USER_SURNAME_?>:&nbsp;</b><input type="text" name="nom" value="">
    
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
                    <b><?echo _NEW_USER_EMAIL_?>:&nbsp;</b><input type="text" name="email" value="">
                    <br><br>
                    <b><?echo _NEW_USER_CONFEMAIL_?>:&nbsp;</b><input type="text" name="confirm_email" value="">
                    <br><br>
                    <input type="submit" value="<?echo _SEND_?>">
                </form>
                <br>
            </div>            
            <?echo _HELP_CREATE_ACCOUNT_DET_2_?>
        </li>

        <li>
            <b><?echo _HELP_CREATE_DOCUMENT_?></b>
            <br>
            <?echo _HELP_CREATE_DOCUMENT_DET_1_?>
        </li>

        <li>
            <b><?echo _HELP_ADD_DOCUMENT_?></b>
            <br>
            <?echo _HELP_ADD_DOCUMENT_DET_1_?>
        </li>

        <li>
            <b><?echo _HELP_PAYMENTS_?></b>
            <br>
            <?echo _HELP_PAYMENTS_DET_1_?>
        </li>        
        
    </ol>  
</div>
