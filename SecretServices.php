<?php
session_start();

// session_destroy();


/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONFIG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dir_db = 'data1';
if (!file_exists($dir_db)) {mkdir($dir_db, 0777, true);}
$debug_array['$dir_db']= $dir_db;
/*_________________________________________________ CONFIG _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ SESSION ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$_SESSION['last_visit'] = time();
if (!isset($_SESSION['last_visit'])) {$_SESSION['last_visit'] = time();}
if ((time() - $_SESSION['last_visit']) > 300){session_destroy(); header('Location: '.$url);}
if (isset($_GET['logout']) ){session_destroy(); header('Location: '.$url); exit;} 
if(!isset($_SESSION['logged_in'])){$_SESSION['logged_in'] = false;}
/*_________________________________________________ SESSION _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DELETE_EMPTY_FILES ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if ($handle = opendir($dir_db)) {  
  while (false !== ($file = readdir($handle))) {
    if($file == '.' or $file == '..') continue;
    // echo $dir_db.'/'.$file .' Size: '. filesize($dir_db.'/'.$file).'<br>';
    if (is_writable($dir_db.'/'.$file) && filesize($dir_db.'/'.$file) < (161)) {
      unlink($dir_db.'/'.$file);
    }
    // $file = str_replace('.json', '', $file);
    // $file_exp = explode('_',$file);
    // $file_array[$file_exp[0]] = $file_exp[1];
  }
closedir($handle);
}
/*_________________________________________________ DELETE_EMPTY_FILES _________________________________________________*/


 

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ LOGIN ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['name']) and $_POST['name'] != "" and isset($_POST['password']) and $_POST['password'] != "") { 
        $_SESSION['name']           = $_POST['name'];
        $_SESSION['password']       = $_POST['password']; 
        $_SESSION['logged_in']      = true; 
} 

/*_________________________________________________ LOGIN _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ SECURE ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function secure($action, $key, $file, $string) {
    $output      = false;
    $method      = "AES-256-CBC"; 
    $init_vector = 'just some ramdom text';
    $key         = hash('sha256', $key);
    $init_vector = substr(hash('sha256', $init_vector), 0, 16);

    if ($action == 'encrypt') {
        $string = json_encode($string);
        $string = openssl_encrypt($string, $method, $key, 0, $init_vector);
        $string = base64_encode($string);
        if (file_put_contents($file, $string) > 0 ){
            $output = true;
        }
    } 
    else if( $action == 'decrypt' ) {
        $string = file_get_contents($file);
        $string = base64_decode($string);
        $string = openssl_decrypt($string, $method, $key, 0, $init_vector);
        $output = json_decode($string, true);
        if( is_string($output)){
        $output = json_decode($output, true);
        }
    }
    return $output;
}
/*_________________________________________________ SECURE _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ GET_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true) {
  $db_file = $dir_db.'/'.$_SESSION['name'].'_'.md5($_SESSION['password']).'.json';

  if(!is_file($db_file)){
      $content = '[
        {"type": "main","name": "Orga","color": "#1e1e1e"},
        {"type": "container","name": "Home","color": "red"},
        {"type": "item","name": "Test1","container": "Home","url": "http://test.de","user": "","pw": ""},
        {"type": "item","name": "Test2","container": "Home", "url": "http://test1.de","user": "","pw": ""} 
      ]';         
      // file_put_contents($db_file, $content);     
      secure('encrypt', $_SESSION['password'], $db_file, $content);
  }
  // $data = json_decode(file_get_contents($db_file), true);
  $data = secure('decrypt', $_SESSION['password'], $db_file, '');
  $debug_array['$_SESSION[name]']     = $_SESSION['name'];
  $debug_array['$_SESSION[password]'] = $_SESSION['password'];
  $debug_array['db_file']             = $db_file;
}
/*_________________________________________________ GET_DATA _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
// session_destroy();

// pprint($data);
// pprint($_POST);
// pprint($_SESSION);
// pprint($file_array);

  // pprint(json_decode(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'), true));
  // pprint(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'));

/*_________________________________________________ DEBUG _________________________________________________*/





/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ COLORS ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$colors = array(
    'lightstgrey' => '#cbe3e7',
    'background' => '#1e1c31',
    'almostwhite' => '#fbfcfc',
    'darkgrey' => '#565575',
    'black' => '#100e23',
    'salomon' => '#ff8080',
    'red' => '#ff5458',
    'lime' => '#95ffa4',
    'green' => '#62d196',
    'sienna' => '#ffe9aa',
    'orange' => '#ffb378',
    'sky' => '#91ddff',
    'blue' => '#65b2ff',
    'mangenta' => '#c991e1',
    'violett' => '#906cff',
    'lightgreen' => '#aaffe4',
    'cyan' => '#63f2f1',
    'lightgrey' => '#cbe3e7',
    'grey' => '#a6b3cc'
    );
function rand_color(){
  global $colors;
  return array_rand(array_flip($colors),1);
}
// echo rand_color();
/*_________________________________________________ COLORS _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ NEW_ENTRY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['new_entry']) and $_SESSION['logged_in'] == true){
  // load existig data
  // $data = file_get_contents($db_file);
  // $data = json_decode($data, true);
  $data = secure('decrypt', $_SESSION['password'], $db_file, "");
  // $data = json_decode($data, true);

  // pprint($data,1,1,1);

  // NEW CONTAINER
  if(isset($_POST['new_container']) and $_POST['new_container'] ==1){
    $data[] = array(
      'type' => 'container',
      'name' => $_POST['container'],
      'color' => $_POST['color']
    );
    if (!isset($data['color']) or $data['color'] == ''){ $data['color'] = rand_color();}
  }
  // NEW ITEM
  $data[] = array(
    'type' => 'item',
    'name' => $_POST['name'],
    'container' => $_POST['container'],
    'url' => $_POST['url'],
    'user' => $_POST['user'],
    'pw' => $_POST['pw']
  ); 
  // file_put_contents($db_file, json_encode($data));
  secure('encrypt', $_SESSION['password'], $db_file, $data);

}
/*_________________________________________________ NEW_ENTRY _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DUMMY_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] == false) {
  // MAKE RANDOM STRINGS FOR DUMMYS
  function rand_str($nr=7){
    // $str = str_split('⼈⼉⼊⼋⼌⼍⼎⼏⼐⼑⼒⼓⼔⼕⼖⼗⼘⼙⼚⼛⼜⼝⼞⼟⼠⼡⼢⼣⼤⼥⼦⼧⼩⼪⼫⼭⼮⼯⼱⼲⼳⼵⼶⼷⼸⼹⼺⼻⼼⼽⼾⼿⽀⽁⽂⽃⽄⽅⽆⽇⽈⽉⽊⽋⽌⽍⽎⽏⽐⽑⽒⽓⽔⽕⽖⽗⽘⽙⽚⽛⽜⽝⽞⽟⽠⽡⽢⽣⽤⽥⽦⽧⽨⽩⽪⽫⽬⽭⽮⽯⽰⽱⽲⽳⽴⽵⽶⽷⽸⽹⽺⽻⽼⽽⽾⽿⾀⾁⾂⾃⾄⾅⾆⾇⾈⾉⾊⾋⾌⾍⾎⾏⾐⾑⾒⾓⾔⾕⾖⾗'); 

    $str = str_split('qwrertzuiopasdgfhjklmnbvcxy1234567890'); 
    // $str = explode(',','U+2F00,U+2F01,U+2F02,U+2F03,U+2F04,U+2F05,U+2F06,U+2F07,U+2F08,U+2F09,U+2F0A,U+2F0B,U+2F0C,U+2F0D,U+2F0E,U+2F0F,U+2F10,U+2F11,U+2F12,U+2F13,U+2F14,U+2F15,U+2F16,U+2F17,U+2F18,U+2F19,U+2F1A,U+2F1B,U+2F1C,U+2F1D,U+2F1E,U+2F1F,U+2F20,U+2F21,U+2F22,U+2F23,U+2F24,U+2F25,U+2F26,U+2F27,U+2F28,U+2F29,U+2F2A,U+2F2B,U+2F2C,U+2F2D,U+2F2E,U+2F2F,U+2F30,U+2F31,U+2F32,U+2F33,U+2F34,U+2F35,U+2F36,U+2F37,U+2F38,U+2F39,U+2F3A,U+2F3B,U+2F3C,U+2F3D,U+2F3E,U+2F3F,U+2F40,U+2F41,U+2F42,U+2F43,U+2F44,U+2F45,U+2F46,U+2F47,U+2F48,U+2F49,U+2F4A,U+2F4B,U+2F4C,U+2F4D,U+2F4E,U+2F4F,U+2F50,U+2F51,U+2F52,U+2F53,U+2F54,U+2F55,U+2F56,U+2F57,U+2F58,U+2F59,U+2F5A,U+2F5B,U+2F5C,U+2F5D,U+2F5E,U+2F5F,U+2F60,U+2F61,U+2F62,U+2F63,U+2F64,U+2F65,U+2F66,U+2F67,U+2F68,U+2F69,U+2F6A,U+2F6B,U+2F6C,U+2F6D,U+2F6E,U+2F6F,U+2F70,U+2F71,U+2F72,U+2F73,U+2F74,U+2F75,U+2F76,U+2F77,U+2F78,U+2F79,U+2F7A,U+2F7B,U+2F7C,U+2F7D,U+2F7E,U+2F7F,U+2F80,U+2F81,U+2F82,U+2F83,U+2F84,U+2F85,U+2F86,U+2F87,U+2F88,U+2F89,U+2F8A,U+2F8B,U+2F8C,U+2F8D,U+2F8E,U+2F8F,U+2F90,U+2F91,U+2F92,U+2F93,U+2F94,U+2F95,U+2F96,U+2F97'); 
  shuffle($str); 
  $str = array_slice($str, 0, $nr); 
  $str = implode('', $str); 
  return $str;
  }
  // delete array content
  $data = array();
  // MAIN ARRAY
  $data[] = array('type' => 'main', 'name' => 'Dummy', 'color' => '#1e1e1e');
  // CONTAINER ARRAYS
  for ($i=0; $i < $_GET['dummy'] = 15 ; $i++) { 
    $container_name = rand_str(6);
    $data[] = array('type' => 'container', 'name' => $container_name, 'color' => '');
      // ITEM ARRAYS
      for ($j=0; $j < $_GET['dummy'] = 5 ; $j++) { 
        $data[] = array('type' => 'item', 'container' => $container_name, 'name' => rand_str(11), 'url' => rand_str(11));
    }
  }
  $db_file ='';
  $debug_array['$_SESSION[logged_in]'] = $_SESSION['logged_in'];

// pprint($data);
}
/*_________________________________________________ DUMMY_DATA _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DATA_ARRAY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  $data_array   = array();
  $config_array = array();
  foreach ($data as $item => $value) {
    // build the main (config) information 
    if(isset($value['type']) and $value['type'] == 'main'){
      foreach ($value as $ke => $va) { 
        $config_array[$ke] = $va;
        }
    }
    // build the container
    if(isset($value['type']) and $value['type'] == 'container'){
      foreach ($value as $ke => $va) {
        $data_array[$value['name']][$ke] = $va; 
      }
      if ($data_array[$value['name']]['color'] == ''){
        $data_array[$value['name']]['color'] = rand_color();
      }
    } 
    // build the entries
    if(isset($value['type']) and $value['type'] == 'item'){
      foreach ($value as $ke => $va) { 
      $data_array[$value['container']]['item'][$value['name']][$ke] = $va;
      }
    }
}
$debug_array['$config_array'] = $config_array;
$debug_array['$data_array']   = $data_array;

/*_________________________________________________ DATA_ARRAY _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/

// alle verfügbaren Schlüssel der Arrays von Variablen ausgeben
// pprint(array_keys(get_defined_vars()));
//    <a class="" href="<?= $db_file ? >"><?= $db_file ? ></a>
// 
// <?= $config_array['name']? >
/*_________________________________________________ DEBUG _________________________________________________*/

?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecretServices</title>
    <style>
    <?= pprint_css(); ?>
      #login_form:checked ~ div.login_form{background:var(--RtD_lightestgrey);border:solid 1px var(--RtD_lightgrey_border_menu);border-radius:5px;display:inline-block;padding:5px;position:absolute;width:200px;}
      #new_container:checked ~ div.new_container{display:inline;}
      #new_entry:checked ~ section.new_item_form{border-radius:5px;box-shadow:0 0 1px 5000px rgba(0,0,0,0.8);display:inline;padding:18px 18px 10px 33px;position:absolute;}
      #new_item fieldset{border-color:#25a88e;border-radius:3px;color:#25a88e;margin:.5em 1em 1.3em 0;width:350px;}
      *{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;}
      .button{font-size:13px;text-align:center;}
      .container{display:inline-block;font-size:20px;padding:20px;vertical-align:top;width:300px;}
      .container fieldset{border-radius:3px;border-style:solid;border-width:2px;color:inherit;filter:brightness(150%);margin:.5em 1em 1.3em 0;width:250px;}
      .content{margin:0;padding:0;text-align:left;}
      .font_brighter:hover{filter:brightness(150%);}
      .form{margin:0;width:100%;}
      .hidden{display:none;}
      .login_form input{width:40px;}
      .new_entry_button{color:#9400D3;font-size:180%;}
      @font-face{font-family:Bree;font-style:normal;font-weight:400;src:local("Bree Regular"), local(Bree-Regular), url(css/bree.woff2) format('woff2');}
      a{color:inherit;text-decoration:none;}
      a.button{left:6px;position:relative;}
      body{background-color:#232323;display:block;font:16px Lato, Helvetica, Arial, sans-serif;font-family:Lato, Sans-Serif;margin:8px;padding:50px;}
      button:hover,.button:hover{background:#202020;}
      div.close_new_item{position:absolute;right:45px;top:40px;}
      fieldset legend{filter:brightness(80%);font-family:Bitter;font-size:1.2em;}
      form button{position:relative;top:7px;}
      form label{display:inline;font-family:Bitter, serif;}
      form p{float:left;margin:1px;padding:5px;width:49%;}
      h1{color:grey;font:2.5em Bitter, serif;}
      h2{font:2.5em Bitter, serif;margin:0;}
      /* html{-ms-text-size-adjust:100%;-webkit-tap-highlight-color:rgba(0,0,0,0);-webkit-text-size-adjust:100%;font-family:sans-serif;font-size:10px;} */
      input,button,select,textarea,.button{background:#232323;border:1px solid #25a88e;border-radius:3px;color:grey;font-family:Lato, sans-serif;height:26px;padding:.2em;width:100%;}
      label.close_new_item{color:#8B0000;}
      label.login_form{font-size:180%;line-height:1.5em;}
      li{list-style-type:none;}
      ul{list-style:none;margin:0;padding:0;}
      ul.scroll{height:110px;line-height:1.9em;overflow:auto;-ms-overflow-style:none;scrollbar-width:none;}
      ul.scroll::-webkit-scrollbar{display:none;}
    </style>
  </head>
  <body style="background-color: <?=$config_array['color']?>;">
    <div class="content">
<h1><a href="<?=$url?>">home</a></h1> 
<div class="message">


<?pprint($debug_array,0,0,1)?>

</div>


<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ USER_LINKS ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
<div class="user_links">
<?php 
if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] == false) {
?>

<label class="login_form font_brighter" for="login_form">&#9733;</label>
<input type="checkbox" id="login_form" class="hidden">
<div class="login_form hidden">
    <form action="<?=$url?>" method="POST">
    <input type="text" placeholder="name" name="name" value="">&nbsp;
    <input type="password" placeholder="password" name="password" value="">&nbsp;
    <input type="submit" class="button" value="&check;" />
    </form>
</div>
<?php
}
if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true) {
?>

  <div id='logged_in' class='overlay1'>
  <a href='?logout' class='button'>&nbsp;logout&nbsp;</a>
  <label class='new_entry_button font_brighter' for='new_entry'>&nbsp; &#9733;</label>
  </div>
<?php
; } 
?>
</div>
<!-- _________________________________________________ USER_LINKS _________________________________________________ -->



<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONTAINER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
  <? foreach ($data_array as $container => $value) { ?>
    <div style="color:<?= $data_array[$container]['color'] ?>" class="container">
      <fieldset style="border-color: <?= $data_array[$container]['color'] ?>">
      <legend><?= $container ?></legend>
        <ul class="scroll">
        <!-- ITEMS  -->
        <?  foreach ($value['item'] as $item => $param) { ?>
          <!-- < ?pprint($value)?> -->
          <li class="font_brighter"><a href=<?= $param['url'] ?>><?= $item ?></a></li>
        <? } ?>
        <!-- ITEMS  -->
        </ul>
      </fieldset>
    </div>
  <? } ?>
<!-- _________________________________________________ CONTAINER _________________________________________________ -->



<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ NEW_ENTRY_FORM ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
<input type="checkbox" id="new_entry" class="hidden">
<section class="new_item_form hidden">
  <form action="<?=$url?>" method="post" id="new_item" autocomplete="off">
      <fieldset>
          <legend>Neuer Eintrag</legend>
          <div class="form">
              <p><label for="container">container</label>
              <label for="new_container" class="new_container font_brighter"> &#9733;</label>
                <input list="containers"  name="container" id="container">
                <datalist id="containers"><?php foreach ($data_array as $container => $item) {$name = $item['name']; echo "<option value='$name'>$name</option>";} ?></datalist >
              </p>
              <div class="close_new_item"><label class="close_new_item font_brighter" for="new_entry">✖</label></div>



              <input type="checkbox" id="new_container" name="new_container" class="hidden" value="1">

              <div class="new_container hidden">
                <p><label for="color">color</label><input type="text" id="color" name="color" value=""></p>
                <p><label for="show_to">show_to</label><input type="text" id="show_to" name="show_to" value=""></p>  
              </div>

              <p><label for="name">name</label><input type="text" id="name" name="name" value=""></p>
              <p><label for="url">url</label><input type="text" id="url" name="url" value=""></p>
              <p><label for="pw">pw</label><input type="text" id="pw" name="pw" value=""></p>
              <p><label for="user">user</label><input type="text" id="user" name="user" value=""></p>
              <p><input type="hidden" name="new_entry" value="true"></p>
              <p><button form="new_item">Ausführen</button></p>
          </div>
      </fieldset>
  </form>
</section>
<!-- _________________________________________________ NEW_ENTRY_FORM _________________________________________________ -->


</div> 

    
  </body>
</html>




























<?php
/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ HELPER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function br($nr=1){
  for ($i=0; $i < $nr; $i++) { 
  $br .= '<br>';
  }
  return $br;
}
/*_________________________________________________ HELPER _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ PRETTY_PRINT ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function pprint_css() {
	echo <<<EOL
 #pretty_print {font-family: Consolas, monaco, monospace;font-size: 1em;background-color: #b1b1b1;border: 1px solid #949494;border-radius: 5px;width: max-content;margin: 20px;}
\t #pretty_print input[type="checkbox"] {position: absolute;left: -100vw;}
\t #pretty_print label {display: inline-block;width: 100%;font-weight: bold;margin: .2em;cursor: pointer;}
\t #pretty_print label span.linenumber {position: relative;top: 3px;right: 10px;float:right;font-weight: normal;font-size: 80%; color:white;}
\t #pretty_print pre {background: lightgray;margin: 0px;padding: 5px;overflow-y: scroll;max-height: 400px;padding-right: 50px;}
\t #pretty_print pre::-webkit-scrollbar {display: none;}
\t #pretty_print pre{-ms-overflow-style: none;  scrollbar-width: none; }
\t #pretty_print pre span {line-height: 1.5em;}
\t #pretty_print pre span.null {color: black;}
\t #pretty_print pre span.boolean {color: brown;}
\t #pretty_print pre span.double {color: darkgreen;}
\t #pretty_print pre span.integer {color: green;}
\t #pretty_print pre span.string {color: darkblue;}
\t #pretty_print pre span.array {color: black;}
\t #pretty_print pre span.object {color: black;}
\t #pretty_print pre span.type {color: grey;}
\t #pretty_print pre span.public {color: darkgreen;}
\t #pretty_print pre span.protected {color: red;}
\t #pretty_print pre span.private {color: darkorange;}\n
EOL;
}


function pprint($arr, $printable = 0, $type = 0, $hide = 0) {
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$id = random_int(0, 999);
	echo "\n<!-- PRETTY_PRINT -->\n";
	// var_dump($caller);
	echo "<div id='pretty_print'>\n\t";
	echo "<style>#hide_$id:checked ~ pre{display: none;}</style>\n\t";
	echo "<label for='hide_$id'>$".print_var_name($arr). " <span class='linenumber'>&nbsp; ".basename($caller['file']).":".$caller['line']."</span></label>\n\t";
	echo "<input type='checkbox' id='hide_$id' class='hidden' ".(($hide) ? ' checked' : '')." >\n\t";
	echo "<pre>\n";
	pprint_array($arr, "", $printable, $type);
	echo ($printable) ? ";" : '';
	echo "\t</pre>\n";
	echo "</div>\n";
	echo "<!-- PRETTY_PRINT -->\n\n";
}

function pprint_array($arr, $p, $printable, $type) {
	if ($printable == 1) {
		$arround = array('array_1' => 'array(', 'array_2' => ')', 'key_1' => '[', 'key_2' => ']', 'value_1' => '"', 'value_2' => '"', 'type_1' => '[', 'type_2' => ']', 'sep' => ',');
	} else {
		$arround = array('array_1' => '', 'array_2' => '', 'key_1' => '', 'key_2' => '', 'value_1' => '', 'value_2' => '', 'type_1' => '', 'type_2' => '', 'sep' => '');
	}
	$t = gettype($arr);
	switch ($t) {

		case "NULL":
			echo '<span class="null"><b>NULL</b></span>'.$arround['sep'];
			break;

		case "boolean":
			echo '<span class="boolean">'.($arr == 0 ? "false" : "true").'</span>'.$arround['sep'].(($type) ? ' <span class="type">boolean</span>' : '');
			break;

		case "double":
			echo '<span class="double">'.$arr.'</span>'.$arround['sep'].(($type) ? ' <span class="type">double</span>' : '');
			break;

		case "integer":
			echo '<span class="integer">'.$arr.'</span>'.$arround['sep'].(($type) ? ' <span class="type">integer</span>' : '');
			break;

		case "string":
			echo $arround['value_1'].'<span class="string">'.$arr.'</span>'.$arround['value_2'].$arround['sep'].(($type) ? ' <span class="type">string('.strlen($arr).')</span>' : '');
			break;

		case "array":
			echo $arround['array_1'].(($type) ? ' <span class="type">('.count($arr).')</span>' : '')."\r\n";

			foreach ($arr as $k => $v) {
				if (gettype($k) == "string") {
					echo $p."\t".$arround['key_1'].$k.$arround['key_2'].' => ';
				} else {
					echo $p."\t"."".$k." => ";
				}
				pprint_array($v, $p."\t", $printable, $type);
				echo "\r\n";
			} // foreach $arr
			echo $p.$arround['array_2'].$arround['sep'];
			break;

		case "object":
			$class = get_class($arr);
			$super = get_parent_class($arr);
			echo "<span class='object'>Object</span>(".$class.($super != false ? " exdends ".$super : "").")";
			echo (($printable) ? "{" : '')."\r\n";
			$o = (array)$arr;
			foreach ($o as $k => $v) {
				$o_type = "";
				$name = "";
				if (substr($k, 1, 1) == "*") {
					$o_type = "protected";
					$name = substr($k, 2);
				} else if (substr($k, 1, strlen($class)) == $class) {
					$o_type = "private";
					$name = substr($k, strlen($class) + 1);
				} else if ($super != false && substr($k, 1, strlen($super)) == $super) {
					$o_type = $super." private";
					$name = substr($k, strlen($super) + 1);
				} else {
					$o_type = "public";
					$name = $k;
				}
				if ($printable) {
					echo $p."\t".$arround['type_1']."<span class='$o_type'>".$o_type.": ".$name."</span>".$arround['type_2']." => ";
				} else {
					echo $p."\t"."<span class='$o_type'>".$name."</span> => ";
				}

				pprint_array($v, $p."\t", $printable, $type);
				echo "\r\n";
			}
			echo $p.($printable) ? "}" : '';
			break;

		default:
			break;
	} // switch
} // function


// get name of $var as string
function print_var_name($var) {
	foreach ($GLOBALS as $var_name => $value) {
		if ($value === $var) {
			return $var_name;
		}
	}
	return 'array';
}
/*_________________________________________________ PRETTY_PRINT _________________________________________________*/
