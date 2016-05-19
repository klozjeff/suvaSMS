<?php
error_reporting(0);

if(defined('IS_ADMIN') || defined('IS_AJAX')) {
$subf = '../';
} else {
if(defined('IS_HOME')) {
$subf = 'app/';
} else {
$subf = ''; 
} 
}

if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'bulgarian') {
$ago_pos = 1;
} else {
$ago_pos = 0;
}

if(isset($_GET['set-lang'])) {
$language = $_GET['set-lang'];
$icon ='app/'.'languages'.'/'.strtolower($language).'/'.'icon.png';
$path = $subf.'languages'.'/'.strtolower($language).'/language.php';
$_SESSION['lang'] = $language;
header('Location: people');
exit;

} else {

if(!isset($_SESSION['lang'])) {
$language = $default;
$icon = 'languages'.'/'.strtolower($language).'/'.'icon.png';
$path = $subf.'languages'.'/'.strtolower($language).'/language.php';
} else {
$language = $_SESSION['lang'];
$icon = 'languages'.'/'.strtolower($language).'/'.'icon.png';
$path = $subf.'languages'.'/'.strtolower($language).'/language.php';
}

}

require($path);

function isLogged() {
  if(isset($_SESSION['auth'])) {
    return true;
  } else {
    return false;
  }
}

function url_get_contents($url){
   if(function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec') && function_exists('curl_exec')){
     # Use cURL
     $curl = curl_init($url);

     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
     curl_setopt($curl, CURLOPT_TIMEOUT, 5);
     if(stripos($url,'https:') !== false){
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
     }
     $content = @curl_exec($curl);
     curl_close($curl);
   }else{
     # Use FGC, because cURL is not supported
     ini_set('default_socket_timeout',5);
     $content = @file_get_contents($url);
   }
   return $content;
}

function sendSMS($campaign)
{

if(isset($db) && $logged == true) {

$campaign_data=$db->query("SELECT list_id,temp_id FROM campaign WHERE campaign_id='".$campaign."'");
$campaign_data=$campaign_data->fetch_array();	

$recipients=$db->query("SELECT * FROM list_leads WHERE list_id='".$campaign_data['list_id']."'");
$output=array();
while($recipients=$recipients->fetch_array())
{
	$receivers[]=$recipients['phone_no'];
	
}
foreach($receivers as $recipients)
{
$output[]=$recipients;
}
$temp_data=$db->query("SELECT content FROM sms_template WHERE template_id='".$campaign_data['temp_id']."'")->fetch_array();		
$message=$temp_data['content'];	
$people=implode(',', $output);
   $now=time();
$db->query("INSERT INTO sms_outbox(campaign_id,message,recipient,date_done) VALUES('".$campaign."','".$message."','".$people."','".$now."')");
}
else
{
	
	echo "DB not set";
}
}

function time_ago($ptime)
{   
    global $lang;
    global $ago_pos;
    $etime = time() - $ptime;
    if ($etime < 1)
    {
     $diff=$ptime-time();//time returns current time in seconds
$days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
$hours=round(($diff-$days*60*60*24)/(60*60));
if($days<1)
{
echo "<a style='color:red'>$hours hrs</a><br />";	
	
}
else
{
	
	echo "$days days $hours hrs<br />";
}
//Report

    }
    $a = array( 365 * 24 * 60 * 60  =>  $lang['year'],
                 30 * 24 * 60 * 60  =>  $lang['month'],
                      24 * 60 * 60  =>  $lang['day'],
                           60 * 60  =>  $lang['hour'],
                                60  =>  $lang['minute'],
                                 1  =>  $lang['second']
                );
    $a_plural = array( $lang['year']   => $lang['years'],
                       $lang['month']  => $lang['months'],
                       $lang['day']    => $lang['days'],
                       $lang['hour']   => $lang['hours'],
                       $lang['minute'] => $lang['minutes'],
                       $lang['second'] => $lang['seconds']
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            if($ago_pos == 0) {
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' '.$lang['ago'];
            } else {
            return $lang['ago'].' '.$r . ' ' . ($r > 1 ? $a_plural[$str] : $str); 
            }
        }
    }
}

function time_ago2($ptime)
{   
    global $lang;
    global $ago_pos;
    $etime = time() - $ptime;
    if ($etime < 1)
    {
     $diff=$ptime-time();//time returns current time in seconds
$days=floor($diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
$hours=round(($diff-$days*60*60*24)/(60*60));
if($days<1)
{
echo "<a style='color:red'>$hours hrs</a><br />";	
	
}
else
{
	
	echo "$days days $hours hrs<br />";
}
//Report

    }
    $a = array( 365 * 24 * 60 * 60  =>  $lang['yr'],
                 30 * 24 * 60 * 60  =>  $lang['month'],
                      24 * 60 * 60  =>  $lang['day'],
                           60 * 60  =>  $lang['hr'],
                                60  =>  $lang['min'],
                                 1  =>  $lang['sec']
                );
    $a_plural = array( $lang['yrs']   => $lang['yrs'],
                       $lang['month']  => $lang['months'],
                       $lang['day']    => $lang['days'],
                       $lang['hr']   => $lang['hrs'],
                       $lang['min'] => $lang['mins'],
                       $lang['sec'] => $lang['secs']
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            if($ago_pos == 0) {
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' '.$lang['ago'];
            } else {
            return $lang['ago'].' '.$r . ' ' . ($r > 1 ? $a_plural[$str] : $str); 
            }
        }
    }
}

function geoinfo() {
  $user_ip = $_SERVER['REMOTE_ADDR'];
  $user_info = url_get_contents('http://www.geoplugin.net/json.gp?ip='.$user_ip);
  return json_decode($user_info,true);
}

$logged = isLogged();

if(isset($db) && $logged == true) {
  $user = $db->query("SELECT * FROM users WHERE email='".$_SESSION['email']."'");
  if($user->num_rows >= 1) {
    $user = $user->fetch_array();
    $split_name = explode(' ', $user['full_name']);
    $full_name = $user['full_name'];
    $first_name = $split_name[0];
    $last_name = $split_name[1];
    } else {
      session_destroy();
      header('Location: '.$subf.'login');
      exit;
    }
}

function coordsToCity($lat,$lng) {
$geocode=url_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false');
$output= json_decode($geocode);
return $output->results[0]->address_components[2]->long_name;
}

function coordsToKm($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return ($angle * $earthRadius)/1000;
}

function isLocalDating($user) {
  if($user['local_dating'] == 1) {
    return true;
  } else {
    return false;
  }
}

function _hash($str) {
  return md5($str);
}

function splitName($name) {
  $split_name = explode(' ', $name);
  return array('first_name' => $split_name[0], 'last_name' => $split_name[1]);
}

function parseEmoticons($domain, $text) {
  $text = str_replace('<3', '&lt;3', $text);
  $text = strip_tags($text);
  $text = str_replace(':)', '<img src="'.$domain.'/app/img/emoticons/Smile.png" class="emoticon">', $text);
  $text = str_replace(';)', '<img src="'.$domain.'/app/img/emoticons/Wink.png" class="emoticon">', $text);
  $text = str_ireplace(':D', '<img src="'.$domain.'/app/img/emoticons/Laughing.png" class="emoticon">', $text);
  $text = str_replace('&lt;3', '<img src="'.$domain.'/app/img/emoticons/Heart.png" class="emoticon">', $text);
  $text = str_ireplace(':P', '<img src="'.$domain.'/app/img/emoticons/Crazy.png" class="emoticon">', $text);
  $text = str_replace(':$', '<img src="'.$domain.'/app/img/emoticons/Money-Mouth.png" class="emoticon">', $text);
  $text = str_replace(':*', '<img src="'.$domain.'/app/img/emoticons/Kiss.png" class="emoticon">', $text);
  $text = str_replace(':(', '<img src="'.$domain.'/app/img/emoticons/Frown.png" class="emoticon">', $text);
  $text = str_ireplace(':X', '<img src="'.$domain.'/app/img/emoticons/Sealed.png" class="emoticon">', $text);
  $text = str_ireplace('8|', '<img src="'.$domain.'/app/img/emoticons/Cool.png" class="emoticon">', $text);
  $text = str_ireplace('(y)', '<img src="'.$domain.'/app/img/emoticons/Thumbs-Up.png" class="emoticon">', $text);
  $text = str_ireplace('O:]', '<img src="'.$domain.'/app/img/emoticons/Innocent.png" class="emoticon">', $text);
  $text = str_ireplace(':O', '<img src="'.$domain.'/app/img/emoticons/Gasp.png" class="emoticon">', $text);
  $text = str_ireplace('3:]', '<img src="'.$domain.'/app/img/emoticons/Naughty.png" class="emoticon">', $text);
  $text = str_ireplace('8-)', '<img src="'.$domain.'/app/img/emoticons/Nerd.png" class="emoticon">', $text);
  $text = str_ireplace('V_V', '<img src="'.$domain.'/app/img/emoticons/HeartEyes.png" class="emoticon">', $text);
  return $text;
  return $text;
}

function _secure($str) {
  return strip_tags($str);
}

function thisPage() {
  return basename($_SERVER['PHP_SELF']);
}

function trimMessage($msg, $max) {
if(strlen($msg) > $max) {
  return substr($msg,0,$max).'...';
} else {
  return $msg;
}
}

function getLocaleCodeForDisplayLanguage($name){
    $languageCodes = array(
    "aa" => "Afar",
    "ab" => "Abkhazian",
    "ae" => "Avestan",
    "af" => "Afrikaans",
    "ak" => "Akan",
    "am" => "Amharic",
    "an" => "Aragonese",
    "ar" => "Arabic",
    "as" => "Assamese",
    "av" => "Avaric",
    "ay" => "Aymara",
    "az" => "Azerbaijani",
    "ba" => "Bashkir",
    "be" => "Belarusian",
    "bg" => "Bulgarian",
    "bh" => "Bihari",
    "bi" => "Bislama",
    "bm" => "Bambara",
    "bn" => "Bengali",
    "bo" => "Tibetan",
    "br" => "Breton",
    "bs" => "Bosnian",
    "ca" => "Catalan",
    "ce" => "Chechen",
    "ch" => "Chamorro",
    "co" => "Corsican",
    "cr" => "Cree",
    "cs" => "Czech",
    "cu" => "Church Slavic",
    "cv" => "Chuvash",
    "cy" => "Welsh",
    "da" => "Danish",
    "de" => "German",
    "dv" => "Divehi",
    "dz" => "Dzongkha",
    "ee" => "Ewe",
    "el" => "Greek",
    "en" => "English",
    "eo" => "Esperanto",
    "es" => "Spanish",
    "et" => "Estonian",
    "eu" => "Basque",
    "fa" => "Persian",
    "ff" => "Fulah",
    "fi" => "Finnish",
    "fj" => "Fijian",
    "fo" => "Faroese",
    "fr" => "French",
    "fy" => "Western Frisian",
    "ga" => "Irish",
    "gd" => "Scottish Gaelic",
    "gl" => "Galician",
    "gn" => "Guarani",
    "gu" => "Gujarati",
    "gv" => "Manx",
    "ha" => "Hausa",
    "he" => "Hebrew",
    "hi" => "Hindi",
    "ho" => "Hiri Motu",
    "hr" => "Croatian",
    "ht" => "Haitian",
    "hu" => "Hungarian",
    "hy" => "Armenian",
    "hz" => "Herero",
    "ia" => "Interlingua (International Auxiliary Language Association)",
    "id" => "Indonesian",
    "ie" => "Interlingue",
    "ig" => "Igbo",
    "ii" => "Sichuan Yi",
    "ik" => "Inupiaq",
    "io" => "Ido",
    "is" => "Icelandic",
    "it" => "Italian",
    "iu" => "Inuktitut",
    "ja" => "Japanese",
    "jv" => "Javanese",
    "ka" => "Georgian",
    "kg" => "Kongo",
    "ki" => "Kikuyu",
    "kj" => "Kwanyama",
    "kk" => "Kazakh",
    "kl" => "Kalaallisut",
    "km" => "Khmer",
    "kn" => "Kannada",
    "ko" => "Korean",
    "kr" => "Kanuri",
    "ks" => "Kashmiri",
    "ku" => "Kurdish",
    "kv" => "Komi",
    "kw" => "Cornish",
    "ky" => "Kirghiz",
    "la" => "Latin",
    "lb" => "Luxembourgish",
    "lg" => "Ganda",
    "li" => "Limburgish",
    "ln" => "Lingala",
    "lo" => "Lao",
    "lt" => "Lithuanian",
    "lu" => "Luba-Katanga",
    "lv" => "Latvian",
    "mg" => "Malagasy",
    "mh" => "Marshallese",
    "mi" => "Maori",
    "mk" => "Macedonian",
    "ml" => "Malayalam",
    "mn" => "Mongolian",
    "mr" => "Marathi",
    "ms" => "Malay",
    "mt" => "Maltese",
    "my" => "Burmese",
    "na" => "Nauru",
    "nb" => "Norwegian Bokmal",
    "nd" => "North Ndebele",
    "ne" => "Nepali",
    "ng" => "Ndonga",
    "nl" => "Dutch",
    "nn" => "Norwegian Nynorsk",
    "no" => "Norwegian",
    "nr" => "South Ndebele",
    "nv" => "Navajo",
    "ny" => "Chichewa",
    "oc" => "Occitan",
    "oj" => "Ojibwa",
    "om" => "Oromo",
    "or" => "Oriya",
    "os" => "Ossetian",
    "pa" => "Panjabi",
    "pi" => "Pali",
    "pl" => "Polish",
    "ps" => "Pashto",
    "pt" => "Portuguese",
    "qu" => "Quechua",
    "rm" => "Raeto-Romance",
    "rn" => "Kirundi",
    "ro" => "Romanian",
    "ru" => "Russian",
    "rw" => "Kinyarwanda",
    "sa" => "Sanskrit",
    "sc" => "Sardinian",
    "sd" => "Sindhi",
    "se" => "Northern Sami",
    "sg" => "Sango",
    "si" => "Sinhala",
    "sk" => "Slovak",
    "sl" => "Slovenian",
    "sm" => "Samoan",
    "sn" => "Shona",
    "so" => "Somali",
    "sq" => "Albanian",
    "sr" => "Serbian",
    "ss" => "Swati",
    "st" => "Southern Sotho",
    "su" => "Sundanese",
    "sv" => "Swedish",
    "sw" => "Swahili",
    "ta" => "Tamil",
    "te" => "Telugu",
    "tg" => "Tajik",
    "th" => "Thai",
    "ti" => "Tigrinya",
    "tk" => "Turkmen",
    "tl" => "Tagalog",
    "tn" => "Tswana",
    "to" => "Tonga",
    "tr" => "Turkish",
    "ts" => "Tsonga",
    "tt" => "Tatar",
    "tw" => "Twi",
    "ty" => "Tahitian",
    "ug" => "Uighur",
    "uk" => "Ukrainian",
    "ur" => "Urdu",
    "uz" => "Uzbek",
    "ve" => "Venda",
    "vi" => "Vietnamese",
    "vo" => "Volapuk",
    "wa" => "Walloon",
    "wo" => "Wolof",
    "xh" => "Xhosa",
    "yi" => "Yiddish",
    "yo" => "Yoruba",
    "za" => "Zhuang",
    "zh" => "Chinese",
    "zu" => "Zulu"
    );
    return array_search($name, $languageCodes);
}

function getCountryNameByCode($code){
      $code = strtoupper($code);
      $country = '';
      if( $code == 'AF' ) $country = 'Afghanistan';
      if( $code == 'AX' ) $country = 'Aland Islands';
      if( $code == 'AL' ) $country = 'Albania';
      if( $code == 'DZ' ) $country = 'Algeria';
      if( $code == 'AS' ) $country = 'American Samoa';
      if( $code == 'AD' ) $country = 'Andorra';
      if( $code == 'AO' ) $country = 'Angola';
      if( $code == 'AI' ) $country = 'Anguilla';
      if( $code == 'AQ' ) $country = 'Antarctica';
      if( $code == 'AG' ) $country = 'Antigua and Barbuda';
      if( $code == 'AR' ) $country = 'Argentina';
      if( $code == 'AM' ) $country = 'Armenia';
      if( $code == 'AW' ) $country = 'Aruba';
      if( $code == 'AU' ) $country = 'Australia';
      if( $code == 'AT' ) $country = 'Austria';
      if( $code == 'AZ' ) $country = 'Azerbaijan';
      if( $code == 'BS' ) $country = 'Bahamas the';
      if( $code == 'BH' ) $country = 'Bahrain';
      if( $code == 'BD' ) $country = 'Bangladesh';
      if( $code == 'BB' ) $country = 'Barbados';
      if( $code == 'BY' ) $country = 'Belarus';
      if( $code == 'BE' ) $country = 'Belgium';
      if( $code == 'BZ' ) $country = 'Belize';
      if( $code == 'BJ' ) $country = 'Benin';
      if( $code == 'BM' ) $country = 'Bermuda';
      if( $code == 'BT' ) $country = 'Bhutan';
      if( $code == 'BO' ) $country = 'Bolivia';
      if( $code == 'BA' ) $country = 'Bosnia and Herzegovina';
      if( $code == 'BW' ) $country = 'Botswana';
      if( $code == 'BV' ) $country = 'Bouvet Island (Bouvetoya)';
      if( $code == 'BR' ) $country = 'Brazil';
      if( $code == 'IO' ) $country = 'British Indian Ocean Territory (Chagos Archipelago)';
      if( $code == 'VG' ) $country = 'British Virgin Islands';
      if( $code == 'BN' ) $country = 'Brunei Darussalam';
      if( $code == 'BG' ) $country = 'Bulgaria';
      if( $code == 'BF' ) $country = 'Burkina Faso';
      if( $code == 'BI' ) $country = 'Burundi';
      if( $code == 'KH' ) $country = 'Cambodia';
      if( $code == 'CM' ) $country = 'Cameroon';
      if( $code == 'CA' ) $country = 'Canada';
      if( $code == 'CV' ) $country = 'Cape Verde';
      if( $code == 'KY' ) $country = 'Cayman Islands';
      if( $code == 'CF' ) $country = 'Central African Republic';
      if( $code == 'TD' ) $country = 'Chad';
      if( $code == 'CL' ) $country = 'Chile';
      if( $code == 'CN' ) $country = 'China';
      if( $code == 'CX' ) $country = 'Christmas Island';
      if( $code == 'CC' ) $country = 'Cocos (Keeling) Islands';
      if( $code == 'CO' ) $country = 'Colombia';
      if( $code == 'KM' ) $country = 'Comoros the';
      if( $code == 'CD' ) $country = 'Congo';
      if( $code == 'CG' ) $country = 'Congo the';
      if( $code == 'CK' ) $country = 'Cook Islands';
      if( $code == 'CR' ) $country = 'Costa Rica';
      if( $code == 'CI' ) $country = 'Cote d\'Ivoire';
      if( $code == 'HR' ) $country = 'Croatia';
      if( $code == 'CU' ) $country = 'Cuba';
      if( $code == 'CY' ) $country = 'Cyprus';
      if( $code == 'CZ' ) $country = 'Czech Republic';
      if( $code == 'DK' ) $country = 'Denmark';
      if( $code == 'DJ' ) $country = 'Djibouti';
      if( $code == 'DM' ) $country = 'Dominica';
      if( $code == 'DO' ) $country = 'Dominican Republic';
      if( $code == 'EC' ) $country = 'Ecuador';
      if( $code == 'EG' ) $country = 'Egypt';
      if( $code == 'SV' ) $country = 'El Salvador';
      if( $code == 'GQ' ) $country = 'Equatorial Guinea';
      if( $code == 'ER' ) $country = 'Eritrea';
      if( $code == 'EE' ) $country = 'Estonia';
      if( $code == 'ET' ) $country = 'Ethiopia';
      if( $code == 'FO' ) $country = 'Faroe Islands';
      if( $code == 'FK' ) $country = 'Falkland Islands (Malvinas)';
      if( $code == 'FJ' ) $country = 'Fiji the Fiji Islands';
      if( $code == 'FI' ) $country = 'Finland';
      if( $code == 'FR' ) $country = 'France';
      if( $code == 'GF' ) $country = 'French Guiana';
      if( $code == 'PF' ) $country = 'French Polynesia';
      if( $code == 'TF' ) $country = 'French Southern Territories';
      if( $code == 'GA' ) $country = 'Gabon';
      if( $code == 'GM' ) $country = 'Gambia the';
      if( $code == 'GE' ) $country = 'Georgia';
      if( $code == 'DE' ) $country = 'Germany';
      if( $code == 'GH' ) $country = 'Ghana';
      if( $code == 'GI' ) $country = 'Gibraltar';
      if( $code == 'GR' ) $country = 'Greece';
      if( $code == 'GL' ) $country = 'Greenland';
      if( $code == 'GD' ) $country = 'Grenada';
      if( $code == 'GP' ) $country = 'Guadeloupe';
      if( $code == 'GU' ) $country = 'Guam';
      if( $code == 'GT' ) $country = 'Guatemala';
      if( $code == 'GG' ) $country = 'Guernsey';
      if( $code == 'GN' ) $country = 'Guinea';
      if( $code == 'GW' ) $country = 'Guinea-Bissau';
      if( $code == 'GY' ) $country = 'Guyana';
      if( $code == 'HT' ) $country = 'Haiti';
      if( $code == 'HM' ) $country = 'Heard Island and McDonald Islands';
      if( $code == 'VA' ) $country = 'Holy See (Vatican City State)';
      if( $code == 'HN' ) $country = 'Honduras';
      if( $code == 'HK' ) $country = 'Hong Kong';
      if( $code == 'HU' ) $country = 'Hungary';
      if( $code == 'IS' ) $country = 'Iceland';
      if( $code == 'IN' ) $country = 'India';
      if( $code == 'ID' ) $country = 'Indonesia';
      if( $code == 'IR' ) $country = 'Iran';
      if( $code == 'IQ' ) $country = 'Iraq';
      if( $code == 'IE' ) $country = 'Ireland';
      if( $code == 'IM' ) $country = 'Isle of Man';
      if( $code == 'IL' ) $country = 'Israel';
      if( $code == 'IT' ) $country = 'Italy';
      if( $code == 'JM' ) $country = 'Jamaica';
      if( $code == 'JP' ) $country = 'Japan';
      if( $code == 'JE' ) $country = 'Jersey';
      if( $code == 'JO' ) $country = 'Jordan';
      if( $code == 'KZ' ) $country = 'Kazakhstan';
      if( $code == 'KE' ) $country = 'Kenya';
      if( $code == 'KI' ) $country = 'Kiribati';
      if( $code == 'KP' ) $country = 'Korea';
      if( $code == 'KR' ) $country = 'Korea';
      if( $code == 'KW' ) $country = 'Kuwait';
      if( $code == 'KG' ) $country = 'Kyrgyz Republic';
      if( $code == 'LA' ) $country = 'Lao';
      if( $code == 'LV' ) $country = 'Latvia';
      if( $code == 'LB' ) $country = 'Lebanon';
      if( $code == 'LS' ) $country = 'Lesotho';
      if( $code == 'LR' ) $country = 'Liberia';
      if( $code == 'LY' ) $country = 'Libyan Arab Jamahiriya';
      if( $code == 'LI' ) $country = 'Liechtenstein';
      if( $code == 'LT' ) $country = 'Lithuania';
      if( $code == 'LU' ) $country = 'Luxembourg';
      if( $code == 'MO' ) $country = 'Macao';
      if( $code == 'MK' ) $country = 'Macedonia';
      if( $code == 'MG' ) $country = 'Madagascar';
      if( $code == 'MW' ) $country = 'Malawi';
      if( $code == 'MY' ) $country = 'Malaysia';
      if( $code == 'MV' ) $country = 'Maldives';
      if( $code == 'ML' ) $country = 'Mali';
      if( $code == 'MT' ) $country = 'Malta';
      if( $code == 'MH' ) $country = 'Marshall Islands';
      if( $code == 'MQ' ) $country = 'Martinique';
      if( $code == 'MR' ) $country = 'Mauritania';
      if( $code == 'MU' ) $country = 'Mauritius';
      if( $code == 'YT' ) $country = 'Mayotte';
      if( $code == 'MX' ) $country = 'Mexico';
      if( $code == 'FM' ) $country = 'Micronesia';
      if( $code == 'MD' ) $country = 'Moldova';
      if( $code == 'MC' ) $country = 'Monaco';
      if( $code == 'MN' ) $country = 'Mongolia';
      if( $code == 'ME' ) $country = 'Montenegro';
      if( $code == 'MS' ) $country = 'Montserrat';
      if( $code == 'MA' ) $country = 'Morocco';
      if( $code == 'MZ' ) $country = 'Mozambique';
      if( $code == 'MM' ) $country = 'Myanmar';
      if( $code == 'NA' ) $country = 'Namibia';
      if( $code == 'NR' ) $country = 'Nauru';
      if( $code == 'NP' ) $country = 'Nepal';
      if( $code == 'AN' ) $country = 'Netherlands Antilles';
      if( $code == 'NL' ) $country = 'Netherlands the';
      if( $code == 'NC' ) $country = 'New Caledonia';
      if( $code == 'NZ' ) $country = 'New Zealand';
      if( $code == 'NI' ) $country = 'Nicaragua';
      if( $code == 'NE' ) $country = 'Niger';
      if( $code == 'NG' ) $country = 'Nigeria';
      if( $code == 'NU' ) $country = 'Niue';
      if( $code == 'NF' ) $country = 'Norfolk Island';
      if( $code == 'MP' ) $country = 'Northern Mariana Islands';
      if( $code == 'NO' ) $country = 'Norway';
      if( $code == 'OM' ) $country = 'Oman';
      if( $code == 'PK' ) $country = 'Pakistan';
      if( $code == 'PW' ) $country = 'Palau';
      if( $code == 'PS' ) $country = 'Palestinian Territory';
      if( $code == 'PA' ) $country = 'Panama';
      if( $code == 'PG' ) $country = 'Papua New Guinea';
      if( $code == 'PY' ) $country = 'Paraguay';
      if( $code == 'PE' ) $country = 'Peru';
      if( $code == 'PH' ) $country = 'Philippines';
      if( $code == 'PN' ) $country = 'Pitcairn Islands';
      if( $code == 'PL' ) $country = 'Poland';
      if( $code == 'PT' ) $country = 'Portugal, Portuguese Republic';
      if( $code == 'PR' ) $country = 'Puerto Rico';
      if( $code == 'QA' ) $country = 'Qatar';
      if( $code == 'RE' ) $country = 'Reunion';
      if( $code == 'RO' ) $country = 'Romania';
      if( $code == 'RU' ) $country = 'Russian Federation';
      if( $code == 'RW' ) $country = 'Rwanda';
      if( $code == 'BL' ) $country = 'Saint Barthelemy';
      if( $code == 'SH' ) $country = 'Saint Helena';
      if( $code == 'KN' ) $country = 'Saint Kitts and Nevis';
      if( $code == 'LC' ) $country = 'Saint Lucia';
      if( $code == 'MF' ) $country = 'Saint Martin';
      if( $code == 'PM' ) $country = 'Saint Pierre and Miquelon';
      if( $code == 'VC' ) $country = 'Saint Vincent and the Grenadines';
      if( $code == 'WS' ) $country = 'Samoa';
      if( $code == 'SM' ) $country = 'San Marino';
      if( $code == 'ST' ) $country = 'Sao Tome and Principe';
      if( $code == 'SA' ) $country = 'Saudi Arabia';
      if( $code == 'SN' ) $country = 'Senegal';
      if( $code == 'RS' ) $country = 'Serbia';
      if( $code == 'SC' ) $country = 'Seychelles';
      if( $code == 'SL' ) $country = 'Sierra Leone';
      if( $code == 'SG' ) $country = 'Singapore';
      if( $code == 'SK' ) $country = 'Slovakia (Slovak Republic)';
      if( $code == 'SI' ) $country = 'Slovenia';
      if( $code == 'SB' ) $country = 'Solomon Islands';
      if( $code == 'SO' ) $country = 'Somalia, Somali Republic';
      if( $code == 'ZA' ) $country = 'South Africa';
      if( $code == 'GS' ) $country = 'South Georgia and the South Sandwich Islands';
      if( $code == 'ES' ) $country = 'Spain';
      if( $code == 'LK' ) $country = 'Sri Lanka';
      if( $code == 'SD' ) $country = 'Sudan';
      if( $code == 'SR' ) $country = 'Suriname';
      if( $code == 'SJ' ) $country = 'Svalbard & Jan Mayen Islands';
      if( $code == 'SZ' ) $country = 'Swaziland';
      if( $code == 'SE' ) $country = 'Sweden';
      if( $code == 'CH' ) $country = 'Switzerland, Swiss Confederation';
      if( $code == 'SY' ) $country = 'Syrian Arab Republic';
      if( $code == 'TW' ) $country = 'Taiwan';
      if( $code == 'TJ' ) $country = 'Tajikistan';
      if( $code == 'TZ' ) $country = 'Tanzania';
      if( $code == 'TH' ) $country = 'Thailand';
      if( $code == 'TL' ) $country = 'Timor-Leste';
      if( $code == 'TG' ) $country = 'Togo';
      if( $code == 'TK' ) $country = 'Tokelau';
      if( $code == 'TO' ) $country = 'Tonga';
      if( $code == 'TT' ) $country = 'Trinidad and Tobago';
      if( $code == 'TN' ) $country = 'Tunisia';
      if( $code == 'TR' ) $country = 'Turkey';
      if( $code == 'TM' ) $country = 'Turkmenistan';
      if( $code == 'TC' ) $country = 'Turks and Caicos Islands';
      if( $code == 'TV' ) $country = 'Tuvalu';
      if( $code == 'UG' ) $country = 'Uganda';
      if( $code == 'UA' ) $country = 'Ukraine';
      if( $code == 'AE' ) $country = 'United Arab Emirates';
      if( $code == 'GB' ) $country = 'United Kingdom';
      if( $code == 'US' ) $country = 'United States';
      if( $code == 'UM' ) $country = 'United States Minor Outlying Islands';
      if( $code == 'VI' ) $country = 'United States Virgin Islands';
      if( $code == 'UY' ) $country = 'Uruguay, Eastern Republic of';
      if( $code == 'UZ' ) $country = 'Uzbekistan';
      if( $code == 'VU' ) $country = 'Vanuatu';
      if( $code == 'VE' ) $country = 'Venezuela';
      if( $code == 'VN' ) $country = 'Vietnam';
      if( $code == 'WF' ) $country = 'Wallis and Futuna';
      if( $code == 'EH' ) $country = 'Western Sahara';
      if( $code == 'YE' ) $country = 'Yemen';
      if( $code == 'ZM' ) $country = 'Zambia';
      if( $code == 'ZW' ) $country = 'Zimbabwe';
      if( $country == '') $country = $code;
      return $country;
  } 

foreach ($lang as $l => $v) {
$lang[$l] = utf8_encode($v);
}

foreach ($lang as $l => $v) {
$lang[$l] = utf8_decode($v);
}


function base64_url_encode($input) {
 return strtr(base64_encode($input), '+/=', '-_,');
}

function base64_url_decode($input) {
 return base64_decode(strtr($input, '-_,', '+/='));
}

function getProfilePicture($domain, $user) {
    if(filter_var($user['profile_picture'],FILTER_VALIDATE_URL)) {
    return $user['profile_picture'];
    } else {
    return 'uploads/'.$user['profile_picture'];
    }
}

function isGraph($str) {
    if(strpos($str,'graph.facebook')) {
      return true;
    } else {
      return false;
    }
}

function isActivePlugin($dir,$name) {
  if(file_exists($dir.'/'.$name.'/'.'status.lock')) {
    return true;
  } else {
    return false;
  }
}

if(isActivePlugin('plugins','monthly_subscriptions')) {
$config = json_decode(file_get_contents('plugins'.'/monthly_subscriptions/'.'config.json'),true);
}

require('compressor.php');