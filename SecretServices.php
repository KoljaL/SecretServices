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
if ( isset($_POST['logout']) ){session_destroy(); header('Location: '.$url); exit;} 
if (!isset($_SESSION['logged_in'])){$_SESSION['logged_in'] = false;}
if ( isset($_POST['colors'])) {$_SESSION['colors'] = $_POST['colors'];}
if (!isset($_POST['colors']) and !isset($_SESSION['colors'])) {$_SESSION['colors'] = 'ONEDARK';}
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
        {"type": "main","name": "SecretServices","color": "#1e1e1e"},
        {"type": "container","name": "Venus","color": "#ff5458", "position": "20"},
        {"type": "item","name": "gamma","container": "Venus","url": "..."},
        {"type": "item","name": "alpha","container": "Venus","url": "..."},
        {"type": "item","name": "betta","container": "Venus", "url": "..."},
        {"type": "container","name": "Mars","color": "#ff5458", "position": "10"},
        {"type": "item","name": "betta","container": "Mars", "url": "..."},  
        {"type": "item","name": "gamma","container": "Mars","url": "..."},
        {"type": "item","name": "alpha","container": "Mars","url": "..."}
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


// exit;


/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ COLORS ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/



// MA-BO
$MA_BO = array( 
    'color_a' => array('yellow', '#FAD201'),
    'color_b' => array('orange', '#FF8C00'),
    'color_c' => array('red', '#8E1B1B'),
    'color_d' => array('blue', '#103B73'),
    'color_e' => array('green', '#315B00'),
    'color_f' => array('violet', '#5C1073'), 
);

// ONEDARK alike
$ONEDARK = array(
    'color_a' => array('red', '#ff5458'),
    'color_b' => array('salomon', '#ff8080'),
    'color_c' => array('orange', '#ffb378'),
    'color_d' => array('sienna', '#ffe9aa'),
    'color_e' => array('green', '#98C379'),
    'color_f' => array('lime', '#62d196'),
    'color_g' => array('cyan', '#56B6C2'),
    'color_h' => array('DarkCyan', '#008B8B'),
    'color_i' => array('Blue', '#09568d'),
    'color_j' => array('SteelBlue', '#4682B4'),
    'color_k' => array('violett', '#906cff'),
    'color_l' => array('purple', '#C678DD'),
    'color_m' => array('mangenta', '#c991e1'),

    // 'color_n' => array('white', '#F8F8F8'),
    // 'color_o' => array('orange2', '#E5C07B'),
    // 'color_p' => array('background', '#282C34'),
    // 'color_q' => array('blue', '#65b2ff'),
    // 'color_r' => array('grey', '#ABB2BF')
);
    
$HTML = array(
    'color_a'   => array('BlueViolet', '#8A2BE2'),
    'color_b'   => array('Violet', '#EE82EE'),
    'color_c' => array('Magenta', '#FF00FF'),
    'color_d'  => array('MediumOrchid', '#BA55D3'),
    'color_e'  => array('MediumPurple', '#9370DB'),
    'color_f'   => array('MediumSlateBlue', '#7B68EE'),
    'color_g' => array('Salmon', '#FA8072'),
    'color_h' => array('DarkRed', '#8B0000'),
    'color_i' => array('Red', '#FF0000'),
    'color_j' => array('Pink', '#FFC0CB'),
    'color_k' => array('HotPink', '#FF69B4'),
    'color_l' => array('PaleVioletRed', '#DB7093'),
    'color_m' => array('OrangeRed', '#FF4500'),
    'color_n' => array('DarkOrange', '#FF8C00'),
    'color_o' => array('Orange', '#FFA500'),
    'color_p' => array('Gold', '#FFD700'),
    'color_q' => array('Yellow', '#FFFF00'),
    'color_r' => array('Khaki', '#F0E68C'),
    'color_s' => array('LimeGreen', '#32CD32'),
    'color_t' => array('PaleGreen', '#98FB98'),
    'color_u' => array('SeaGreen', '#2E8B57'),
    'color_v' => array('YellowGreen', '#9ACD32'),
    'color_w' => array('Olive', '#808000'),
    'color_x' => array('DarkCyan', '#008B8B'),
    'color_y' => array('SteelBlue', '#4682B4'),
    'color_z' => array('DodgerBlue', '#1E90FF'),
    'color_aa' => array('CornflowerBlue', '#6495ED'),
    'color_ab' => array('MediumSlateBlue', '#7B68EE'),
    'color_ac' => array('Blue', '#0000FF'),
    'color_ad' => array('MidnightBlue', '#191970'), 
    'color_ae' => array('SandyBrown', '#F4A460'),
    'color_af' => array('Maroon', '#800000')
);

$colors = eval('return $'. $_SESSION['colors'] . ';');
    
function color($func='rand', $val='hex'){
  global $colors;
  $keys   = array_keys($colors);
  $count  = count($colors)-1;
  switch ($func) {
    case 'rand':
    return $colors[$keys[random_int(0, $count)]][($val === 'hex') ? 1 : 0];
    case 'css':
        foreach ($colors as $name => $hex) {
            echo "#$name{color:$hex[1];} /*$hex[0]*/\n\t";
        } 
    break;
    } //SWITCH    
}
// echo  color('rand','hex');
/*_________________________________________________ COLORS _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ NEW_ENTRY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['new_entry']) and $_SESSION['logged_in'] == true){
  // load existig data
  $data = secure('decrypt', $_SESSION['password'], $db_file, "");
  // NEW CONTAINER
  if(isset($_POST['new_container']) and $_POST['new_container'] !==12){
    $data[] = array(
      'type' => 'container',
      'name' => $_POST['container'],
      'position' => $_POST['position'],
      'color' => $_POST['color']
    );
    if (!isset($data['color']) or $data['color'] == ''){ $data['color'] = color('rand','hex');}
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
    $str = str_split('qwrertzuiopasdgfhjklmnbvcxy'); 
  shuffle($str); 
  $str = array_slice($str, 0, $nr); 
  $str = implode('', $str); 
  $str = ucfirst($str);
  return $str;
  }
  // delete array content
  $data = array();
  // MAIN ARRAY
  $data[] = array('type' => 'main', 'name' => 'Dummy', 'color' => '#1e1e1e');
  // CONTAINER ARRAYS
  for ($i=0; $i < 25 ; $i++) { 
    $container_name = rand_str(random_int(5, 9));
    $data[] = array('type' => 'container', 'name' => $container_name, 'color' => '');
      // ITEM ARRAYS
      for ($j=0; $j < 15 ; $j++) { 
        $data[] = array('type' => 'item', 'container' => $container_name, 'name' => rand_str(random_int(10, 19)), 'url' => rand_str(11));
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
      // add missing color
      if ($data_array[$value['name']]['color'] == ''){
        $data_array[$value['name']]['color'] = color('rand','hex');
      }

                  if (isset($data_array[$value['name']]['position'])){
        // echo $data_array[$value['name']]['position'];
      }


      // add missing positon
      if (!isset($data_array[$value['name']]['position'])){
        $data_array[$value['name']]['position'] = $data_array[$value['name']]['name'];
      }



    } 
    // build the items
    if(isset($value['type']) and $value['type'] == 'item'){
      foreach ($value as $ke => $va) { 
      $data_array[$value['container']]['item'][$value['name']][$ke] = $va;
      }

    }
}

// SORT FUNCTION
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}


// SORT CONTAINER BY POSITION
$data_array = array_orderby($data_array, 'position', SORT_ASC, 'name', SORT_ASC);

// RESET POSITION NUMBERS
function reset_positions(&$item2, $key, &$i)
{   
    $item2['position'] = $i;
    $i = $i +10;
    // pprint($item2['position']);
}
$i = 10; 
array_walk($data_array, 'reset_positions', $i);


// SORT ITEMS BY NAME
foreach ($data_array as $key => $value) { 
  $data_array[$key]['item'] = array_orderby($data_array[$key]['item'], 'name', SORT_ASC);
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
    @font-face{font-family:Bree;font-style:normal;font-weight:400;src:local("Bree Regular"), local(Bree-Regular), url(css/bree.woff2) format('woff2');}
    /* html{-ms-text-size-adjust:100%;-webkit-tap-highlight-color:rgba(0,0,0,0);-webkit-text-size-adjust:100%;font-family:sans-serif;font-size:10px;} */
    *{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;}
    *,*::before,*::after {  box-sizing: border-box;}
    body{background-color:#232323;display:block;font:16px Lato, Helvetica, Arial, sans-serif;font-family:Lato, Sans-Serif;margin:0px;padding: 5px 20px 20px 20px;}
    .hidden{display:none;}
    .noscrollbar::-webkit-scrollbar{display:none;}
    .noscrollbar{-ms-overflow-style:none;scrollbar-width:none;}
    h1{color:grey;font:2.5em Bitter, serif;line-height: .5em;}
    h2{font:2.5em Bitter, serif;margin:0;}
    a{color:inherit;text-decoration:none;}
    a.button{left:6px;position:relative;}
    .button{font-size:13px;text-align:center;}
    .font_brighter:hover{filter:brightness(150%);}
    .content{margin:0;padding:0;text-align:left;position: relative;}
    li{list-style-type:none;}
    ul{list-style:none;margin:0;padding:0; height:250px;line-height:1.9em;overflow:auto;}
    .container{display:inline-block;font-size:20px;padding:20px;vertical-align:top;width:400px;}
    .container fieldset{border-radius:3px;border-style:solid;border-width:2px;color:inherit;filter:brightness(100%);margin:.5em 1em 1.3em 0;width:350px;}
    .form{margin:0;width:100%;}
    button:hover,.button:hover{background:#202020;}
    button.logout{position:relative;width: auto; padding: .3em;}
    input,button,select,textarea,.button{background:#232323;border:1px solid #00000060;border-radius:3px;color:grey;font-family:Lato, sans-serif;height:26px;padding:.2em;width:145px;min-height: 1.8rem;}
    select#container_select{width: 20px;}
    input#container{width: 120px;}
    fieldset legend{filter:brightness(100%);font-family:Bitter;font-size:1.2em;}
    form p{float:left;margin:1px;padding:5px;width:49%;}
    form button{position:relative;top:7px;}
    form label{display:inline;font-family:Bitter, serif;padding-left: 5px;}
    #login_form:checked ~ div.login_form{background:var(--RtD_lightestgrey);border:solid 1px var(--RtD_lightgrey_border_menu);border-radius:5px;display:inline-block;padding:5px;position:absolute;width:200px;}
    .login_form input{width:40px;}
    label.login_form{font-size:180%;line-height:1.5em;}
    #new_container:checked ~ div.new_container{display:inline;}
    label.new_container{color: #ff5458;}
    
    select {-webkit-appearance: none;-moz-appearance: none;text-indent: 1px;text-overflow: '';}/* HIDE ARROW FROM DOPDOWN */
    select::-ms-expand {display: none;}/* HIDE ARROW FROM DOPDOWN */
    select#container_select{padding:0; color:#232323; }
 


    #new_entry:checked ~ section.new_item_form{border-radius:5px;box-shadow:0 0 1px 5000px rgba(0,0,0,0.8);display:inline;padding:18px 18px 10px 33px;
    /* left: 200px; top: 100px;  */
      position:absolute;
      top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #232323;}
    
    .new_entry_button{color:#9400D3;font-size:180%;}
    #new_item fieldset{border-color:grey;border-radius:3px;color:grey;margin:.5em 1em 1.3em 0;width:350px;}
    div.close_new_item{position:absolute;right:45px;top:40px;color:#ff5458;}
    #grey{color:grey;}
    <?php color('css');?>
 

    </style>
  </head>
  <body style="background-color: <?=$config_array['color']?>;">
    <div class="content">
<h1><a href="<?=$url?>">SecretServices</a></h1> 
<div class="message">


<?pprint($debug_array,0,0,1)?>

</div>


<form method="post">
    <select name="colors" onchange="this.form.submit();">
        <option style='color: grey' value='Colors'>Colors</option>
        <option style='color: grey' value='ONEDARK'>OneDark</option>
        <option style='color: grey' value='HTML'>HTML</option>
        <option style='color: grey' value='MA_BO'>MA</option> 
    </select>
</form>

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
    <form action="<?=$url?>" method="post" id="logout">           
      <input type="hidden" name="logout" value="true"> 
      <button form="logout" class="logout">Logout</button>
    </form>
    <label class='new_entry_button font_brighter' for='new_entry'>&nbsp; &#9733;</label>
  </div>
<?php
; } 
?>
</div>
<!-- _________________________________________________ USER_LINKS _________________________________________________ -->

<!-- < ?= $data_array[$container]['position'] ?> -->

<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONTAINER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
  <? foreach ($data_array as $container => $value) { ?>
    <div style="color:<?= $data_array[$container]['color'] ?>" class="container">
      <fieldset style="border-color: <?= $data_array[$container]['color'] ?>">
      <legend><?= $data_array[$container]['name'] ?></legend>
        <ul class="noscrollbar">
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
      <fieldset class="change_color">
          <legend class="change_color">Neuer Eintrag</legend>
          <div class="form">

          



          
              <p>
                <label for="container" class="change_color">Container</label>
                <label for="new_container" class="new_container font_brighter"> &#9733;</label>
                <input type="text" id="container" name="container" value=" ">

                <select name="container_select" class="container_select" id="container_select">
                  <!-- <option value=" e">&#9662; </option> -->
                  <?php foreach ($data_array as $container => $item) {
                    $name = $item['name']; 
                    echo "<option  id='{$item['color']}' style='color: {$item['color']}' value='$name' onclick=\"setColor('$name','{$item['color']}')\">$name</option>"
                    ;} ?>
                </select>
              </p>




              <div class="close_new_item"><label class="close_new_item font_brighter" for="new_entry">✖</label></div>
              <p style="visibility: hidden;"><label for="blind">blind</label><input type="text"   value=""></p>
              <input type="checkbox" id="new_container" name="new_container" class="hidden" value="1" checked>


              <div class="new_container hidden">
                <p><label for="color" class="change_color">Color</label>
                  <select name="color" id="colors">
                    <option id='grey' value='grey'>grey</option>
                    <?php foreach ($colors as $name => $hex) {echo "<option id='$name' value='$hex[1]'>$hex[0]</option>";} ?>
                  </select> 
                </p>





                <p><label for="position" class="change_color">Position</label>
                  <select name="position" id="position">
                    <option style="color: grey" value="1">first</option>
                      <?php foreach ($data_array as $container => $item) {
                        $next_position = $item['position']+1;
                        echo "<option style='color: {$item['color']}' value='$next_position'>{$item['name']} &#8680;</option>";
                      } ?>
                  </select> 
                </p>
              </div>

              <p><label for="name" class="change_color">Name</label><input type="text" id="name" name="name" value=""></p>
              <p><label for="url" class="change_color">Link</label><input type="text" id="url" name="url" value=""></p>
              <p><label for="pw" class="change_color">Password</label><input type="text" id="pw" name="pw" value=""></p>
              <p><label for="user" class="change_color">User</label><input type="text" id="user" name="user" value=""></p>
              <p><input type="hidden" name="new_entry" value="true"></p>
              <p><button form="new_item" class="">Send</button></p>
          </div>
      </fieldset>
  </form>
</section>
<!-- _________________________________________________ NEW_ENTRY_FORM _________________________________________________ -->




<script>
  // CHANGE COLOR
document.getElementById('colors').addEventListener('change', changeColor);
function changeColor() {
    var color = document.getElementById('colors').value;
    var list = document.getElementsByClassName('change_color');

    for (var i=0; i<list.length; i++) {
        list[i].style.color = color;
        list[i].style.borderColor = color;
    }
    // alert("input_value " + input_value + "\nselect_value " + select_value);

}
  // CHANGE COLOR 

  // CUSTOM INPUT IN OPTIONS
  document.getElementById('container_select').addEventListener('change', fill_input);
  function fill_input() {
    // get selected value from pulldown
    var select_value = document.getElementById('container_select').value;
    // set input value with pulldown value
    document.getElementById('container').value = select_value;
    console.log("select_value " + select_value );  
    ///////WORKS//////////////////////////
    // var select_style = document.getElementById('container_select').id;
    // var e = document.getElementById("container_select");
    // var resultvalue = e.options[e.selectedIndex].value;
    // var resulttext = e.options[e.selectedIndex].text;
  }; 
  // CUSTOM INPUT IN OPTIONS


  
 function setColor(name, color){
       document.getElementById('colors').text = name;
       document.getElementById('colors').value = color;

      var list = document.getElementsByClassName('change_color');

    for (var i=0; i<list.length; i++) {
        list[i].style.color = color;
        list[i].style.borderColor = color;
    }
    // alert("color " + color );
}

  </script>

 
</div> 

    
  </body>
</html>









              <!-- <p>
                <label for="container" class="change_color">container</label>
                <label for="new_container" class="new_container font_brighter"> &#9733;</label>
                <input list="containers"  name="container" id="container">
                <datalist id="containers">< ?php foreach ($data_array as $container => $item) {$name = $item['name']; echo "<option value='$name'>$name</option>";} ?></datalist >
              </p> -->


















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
