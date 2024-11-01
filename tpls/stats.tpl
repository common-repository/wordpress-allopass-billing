<style>
<!-- 
.titres_rubriques4 {
font-family: Arial,Verdana,Helvetica,sans-serif;
font-size: 14px;
font-weight: bold;
text-decoration: none;
color: #000084;
}

.cellule_1_uk {
background-color: #ababd6;
font-family: Verdana,Arial,Helvetica,sans-serif;
font-size: 11px;
font-weight: bold;
text-decoration: none;
color: #333333;
}

.cellule_2 {
    background-color: #efeffc;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 9px;
    text-decoration: none;
    color: #333333;
}

table {
font-size: 1em;
}

.table_decoration {
border-top-style: solid;
border-right-style: solid;
border-bottom-style: solid;
border-left-style: solid;
border-top-width: 1px;
border-right-width: 1px;
border-bottom-width: 1px;
border-left-width: 1px;
border-top-color: #efeffc;
border-right-color: #efeffc;
border-bottom-color: #efeffc;
border-left-color: #efeffc;
}


.texte_petit_bleu {
font-family: Verdana,Arial,Helvetica,sans-serif;
font-size: 9px;
text-decoration: none;
color: #000084;
}

.texte_petit4 {
font-family: Arial,Helvetica,sans-serif;
font-size: 12px;
text-decoration: none;
color: #333333;
}

.cellule_3b {
background-color: #ffffff;
border-bottom-style: solid;
border-bottom-width: 1px;
border-bottom-color: #000084;
font-family: Arial,Helvetica,sans-serif;
font-size: 9px;
text-decoration: none;
color: #333333;
}

.verdana_10_bleu_gras {
font-family: Verdana,Arial,Helvetica,sans-serif;
font-size: 10px;
font-weight: bold;
text-decoration: none;
color: #000084;
 } -->
</style>

<div class="wrap">
    <h2><?echo _WPSM_STATS_?></h2>

    <?
        if($this->logged == false) {?>
            <h4><?echo strtoupper(_NOT_LOGGED_)?></h4>
            <?echo _LOGIN_OR_REGISTER_?> <a href="<?echo BASE_URL?>wp-admin/admin.php?page=ap_configuration"><?echo _CONFIG_PAGE_?></a>.            
        <?} else {
            echo $this->apo->get_stats();
        }
    ?>
</div>
