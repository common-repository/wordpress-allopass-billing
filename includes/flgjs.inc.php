<?php
$AP_FLAGS[] = Array(
    'FR' => Array('country' => 'France', 'isms' => '', 'itel' => '2_CG'),
    'ZZ' => Array('country' => 'DOM', 'isms' => '', 'itel' => 110),
    'US' => Array('country' => 'United States', 'isms' => 53, 'itel' => ''),
    'CA' => Array('country' => 'Canada', 'isms' => '', 'itel' => 14),
    'ES' => Array('country' => 'Espa&ntilde;a', 'isms' => 25, 'itel' => 39),
    'UK' => Array('country' => 'United Kingdom', 'isms' => 33, 'itel' => 5),
    'DE' => Array('country' => 'Deutschland', 'isms' => '', 'itel' => '4_adult'),
    'IT' => Array('country' => 'Italia', 'isms' => '', 'itel' => 13),
    'PT' => Array('country' => 'Portugal', 'isms' => 69, 'itel' => ''),
    'AT' => Array('country' => '&Ouml;sterreich', 'isms' => '', 'itel' => 11),
    'AU' => Array('country' => 'Australia', 'isms' => 80, 'itel' => ''),
    'CZ' => Array('country' => 'Cesk&aacute; republika', 'isms' => '61_adult', 'itel' => ''),
    'BE' => Array('country' => 'Belgique', 'isms' => '', 'itel' => 15),
    'CH' => Array('country' => 'Suisse', 'isms' => '', 'itel' => 6),
    'LU' => Array('country' => 'Luxemburg', 'isms' => '', 'itel' => 12),
    'NL' => Array('country' => 'Nederland', 'isms' => '', 'itel' => 41),
    'SE' => Array('country' => 'Sverige', 'isms' => 24, 'itel' => ''),
    'NO' => Array('country' => 'Norge', 'isms' => 36, 'itel' => ''),
    'FI' => Array('country' => 'Finland', 'isms' => 114, 'itel' => ''),
    'PL' => Array('country' => 'Polska', 'isms' => 49, 'itel' => ''),
    'SK' => Array('country' => 'Slovensko', 'isms' => 62, 'itel' => ''),
    'RO' => Array('country' => 'Rom&acirc;nia', 'isms' => 63, 'itel' => ''),
    'HU' => Array('country' => 'Magyarorsz&aacute;g', 'isms' => 60, 'itel' => ''),
    'GR' => Array('country' => 'Greece', 'isms' => 86, 'itel' => ''),
    'EE' => Array('country' => 'Eesti', 'isms' => 101, 'itel' => ''),
    'UA' => Array('country' => 'Ukrajina', 'isms' => 108, 'itel' => ''),
    'BG' => Array('country' => 'Bulgarija', 'isms' => 111, 'itel' => ''),
    'RU' => Array('country' => 'Rossiya', 'isms' => 107, 'itel' => ''),
    'LT' => Array('country' => 'Lietuva', 'isms' => 105, 'itel' => ''),
    //'LV' => Array('country' => 'Latvija', 'isms' => 104, 'itel' => ''),
    'IL' => Array('country' => 'Israel', 'isms' => 102, 'itel' => ''),
    'AR' => Array('country' => 'Argentina', 'isms' => 123, 'itel' => ''),
    'PE' => Array('country' => 'Peru', 'isms' => 88, 'itel' => ''),
    'CO' => Array('country' => 'Colombia', 'isms' => 91, 'itel' => ''),
    'VE' => Array('country' => 'Venezuela', 'isms' => 87, 'itel' => ''),
    'EC' => Array('country' => 'Ecuador', 'isms' => 90, 'itel' => ''),
    'MX' => Array('country' => 'Mexico', 'isms' => 89, 'itel' => ''),    
);

$js = "<script>
        var AP_COUNTRIES = Array();
";
foreach($AP_FLAGS as $ct => $cs) {
    foreach($cs as $cc => $data) {
        $flgs .= '<img title="'.$data['country'].'" onClick="coco(\''.strtoupper($cc).'\')" border="0" src="http://www.allopass.com/imgweb/common/flag_'.strtolower($cc).'.gif" width="35" height="29" alt="" style="cursor:pointer;" />';
        $js .= '        AP_COUNTRIES["'.$cc.'"] = Array();
        AP_COUNTRIES["'.$cc.'"]["country"] = "'.$data['country'].'";
        AP_COUNTRIES["'.$cc.'"]["isms"] = "'.$data['isms'].'";
        AP_COUNTRIES["'.$cc.'"]["itel"] = "'.$data['itel'].'";
        ';
    }
}

$js .= "</script>
";

$js_dsp = false;
  
?>
