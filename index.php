<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// session_destroy();
session_start();

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ TO_DO ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾
 
/*_________________________________________________ TO_DO _________________________________________________*/


/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONFIG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$url = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dir_db = 'data';
if (!file_exists($dir_db)) {
    mkdir($dir_db, 0777, true);
}
$checked = '';
$URL2POST = '';
$onloadURL2POST = '';
$message = '';
unset($user_ID);
$session_lenght = 3000;
$pagename = "SecretServices";
/*_________________________________________________ CONFIG _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ SESSION ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
// if (time() - $_SESSION['last_visit'] > 300) {  session_destroy(); header('Location: ' . $url);}
// $last_visit = time();

if (!isset($_SESSION['last_visit'])) {$_SESSION['last_visit'] = time();}
if (isset($_POST['logout']) or (time() - $_SESSION['last_visit'] > $session_lenght) ) {
	$_SESSION['login_ID'] = '';
    session_destroy();
    header('Location: ' . $url);
    exit();
}
if (!isset($_SESSION['logged_in'])) {$_SESSION['logged_in'] = false;}
if (!isset($_SESSION['u_name'])) {$_SESSION['u_name'] = '';}
 
/*_________________________________________________ SESSION _________________________________________________*/

// $_POST['u_name'] = "hh";
// $_POST['u_password'] = "hh";
// $_SESSION['logged_in'] = true;

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ LOGIN ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['u_name']) && '' != $_POST['u_name'] && isset($_POST['u_password']) && '' != $_POST['u_password']) {
    $_SESSION['user_ID'] = md5($_POST['u_name'] . $_POST['u_password']);
    $_SESSION['u_name'] = $_POST['u_name'];
    $_SESSION['user_PW'] = md5($_POST['u_password']);
    $_SESSION['logged_in'] = true;
}
if (isset($_SESSION['user_ID'])) {$user_ID = $_SESSION['user_ID'];}
if (isset($_SESSION['u_name'])) {$name = $_SESSION['u_name'];}
/*_________________________________________________ LOGIN _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ GET_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($user_ID)) {
    $db_file = $dir_db . '/' . $user_ID;
    if (!is_file($db_file)) { 
        $pw_hash = $_SESSION['user_PW'];
        delete_empty_files($dir_db);
        $content = '{
		"config":   {"id": "M_001","type": "main","name": "SecretServices","position": "20", "User": "'.$name.'", "PW_Hash": "'.$pw_hash.'", "colorset": "dark", "grid_style": "grid"},
		"C_167834": {"id": "C_167834","type": "container","name": "Mail","color": "color_1", "position": "20"},
		"I_880623": {"id": "I_880623","type": "item","name": "Posteo.de","c_id": "C_167834","url": "http://posteo.de","notes": ""},
		"I_840642": {"id": "I_840642","type": "item","name": "Protonmail.com","c_id": "C_167834","url": "https://protonmail.com/de","notes": ""},
		"I_850633": {"id": "I_850633","type": "item","name": "Mailbox.org","c_id": "C_167834","url": "https://mailbox.org/de","notes": ""},
		"I_834333": {"id": "I_834333","type": "item","name": "Seppmail.de","c_id": "C_167834","url": "https://seppmail.de/","notes": ""},
		
		"C_163844": {"id": "C_163844","type": "container","name": "Mars","color": "color_7", "position": "30"},
		"I_886633": {"id": "I_886633","type": "item","name": "alpha","c_id": "C_163844","url": "http://test1.de","notes": ""},
		"I_856623": {"id": "I_856623","type": "item","name": "gamma","c_id": "C_163844","url": "http://test1.de","notes": ""}, 
		"I_859633": {"id": "I_859633","type": "item","name": "betta","c_id": "C_163844","url": "http://test1.de","notes": ""}
	}';
        secure('encrypt', $_SESSION['user_PW'], $db_file, $content);
    }
    $data = secure('decrypt', $_SESSION['user_PW'], $db_file, '');
} else{
	dummy_data();
}
/*_________________________________________________ GET_DATA _________________________________________________*/

// pprint($_SESSION);
// pprint($data);
// pprint($db_file);


//////////////////////// STYLE SETTINGS

// SET NEW COLORSET
if (isset($_POST['colors'])) {
	$data['config']['colorset'] = $_POST['colors'];
}
// SET NEW GRIDSTYLE
if (isset($_POST['grid_style'])) {
    $data['config']['grid_style'] = $_POST['grid_style'];
}

// SAVE THE NES STYLE IN FILE
if (isset($user_ID) and  (isset($_POST['grid_style']) or isset($_POST['colors']))) {
    secure('encrypt', $_SESSION['user_PW'], $db_file, $data);
    $data = secure('decrypt', $_SESSION['user_PW'], $db_file, '');
}
// FOR TEMPLATE	
$pagename = $data['config']['name'];
$color_set = $data['config']['colorset'];
$grid_styles = array('grid', 'mansory');
$grid_style = $data['config']['grid_style'] ?: $grid_styles[0];
$grid_style_next = next_value($grid_style, $grid_styles);

/////////////////////// STYLE SETTINGS




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾URL2POSTGET_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($user_ID) && isset($_POST['url2post'])) {
    $params = $data[$_POST['url2post']]['post_data'];
    $params = preg_replace('/\r\n|[\r\n]/', ' ', $params);
    $path = $data[$_POST['url2post']]['url'];
    $onloadURL2POST = 'onload="URL2post()"';
    $URL2POST = <<<URL2POST
	<script>
	// RUN EXTERNAL URL WITH POST
	function URL2post() {
		const form = document.createElement("form");
		form.method = "post";
		form.action = "$path";
		form.target = "_blank";
		var params = {loginname: 'rasal.de', passwort: ' ', language: 'deutsch'};
		
		for (const key in params) {
			if (params.hasOwnProperty(key)) {
				const hiddenField = document.createElement("input");
				hiddenField.type = "hidden";
				hiddenField.name = key;
				hiddenField.value = params[key];
				form.appendChild(hiddenField);
				console.log(hiddenField);
			}
		}
		console.log(form);
		document.body.appendChild(form);
		form.submit();
	}
	</script>
URL2POST;
}
/*_________________________________________________ URL2POST _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
// session_destroy();

// pprint($content);
// pprint($_POST);
// // pprint($_SESSION);
// pprint($file_array);

// pprint(json_decode(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'), true));
// pprint(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'));

//pprint($_POST);
//pprint($data);
//pprint($_SESSION);
// exit;
/*_________________________________________________ DEBUG _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ EDIT_JSON ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['show_edit_json']) && isset($user_ID)) {
	$show_edit_json = true;
	$edit_json_data = $data;
	$edit_json_call = "edit_json();";
} else {
	$show_edit_json = false;
	$edit_json_data = '';
	$edit_json_call = "";
}
if (isset($_POST['edit_json']) && isset($user_ID)) {
	$data = json_decode($_POST['edit_json'],true);
	if (json_last_error() > 0) {
		$show_edit_json = true;
		$message = 'JSON invalid';
		$data = secure('decrypt', $_SESSION['user_PW'], $db_file, '');
	} else {
		secure('encrypt', $_SESSION['user_PW'], $db_file, $data);
	}
}
/*_________________________________________________ EDIT_JSON _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ NEW_ENTRY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['container']) && isset($user_ID)) {
    // load existig data
    $data = secure('decrypt', $_SESSION['user_PW'], $db_file, '');
    // NEW CONTAINER
    if (isset($_POST['container']['id']) and $_POST['container']['id'] != '') {
        $container_id = $_POST['container']['id'];
    } else {
        $container_id = 'C_' . rand_id();
    }
    $data[$container_id] = array(
        'id' => $container_id,
        'type' => 'container',
        'name' => $_POST['container']['name'] ?? '',
        'position' => $_POST['container']['position'] ?? '',
        'color' => $_POST['container']['color'] ?? ''
    );
    if (!isset($data[$container_id]['color']) || '' == $data[$container_id]['color']) {
        $data[$container_id]['color'] = color($color_set, 'rand', 'class');
    }
    // NEW ITEM
    if (isset($_POST['item']['id']) and $_POST['item']['id'] != '') {
        $item_id = $_POST['item']['id'];
    } else {
        $item_id = 'I_' . rand_id();
    }
    $data[$item_id] = array(
        'id' => $item_id,
        'type' => 'item',
        'name' => $_POST['item']['name'] ?? '',
        'c_id' => $container_id,
        'url' => $_POST['item']['url'] ?? '',
        'user' => $_POST['item']['user'] ?? '',
        'pw' => $_POST['item']['password'] ?? '',
        'post_data' => $_POST['item']['post_data_text'] ?? '',
        'notes' => $_POST['item']['notes_text'] ?? '',
        'icon' => grap_favicon($_POST['item']['url'], $item_id) ?? ''
	);
	
    // DELETE CONTAINER
    if (isset($_POST['container']['del']) and $_POST['container']['del'] === "1") {
        // unset container
        unset($data[$container_id]);
        // unset all container items
        foreach ($data as $key => $value) {
            if (
                isset($data[$key]['c_id']) and
                $data[$key]['c_id'] === $container_id
            ) {
                unset($data[$key]);
            }
        }
    }
    // DELETE ITEM
    if (isset($_POST['item']['del']) and $_POST['item']['del'] === "1") {
        unset($data[$item_id]);
    }
    // pprint($data);
    // file_put_contents($db_file, json_encode($data));
    secure('encrypt', $_SESSION['user_PW'], $db_file, $data);
}
/*_________________________________________________ NEW_ENTRY _________________________________________________*/

// $favicon = grap_favicon($url, $name);
// echo '<img title="' . $favicon . '" style="width:32px;padding-right:32px;" src="' . $favicon . '">';


/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DATA_ARRAY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
foreach ($data as $key => $value) {
	if($value['name'] == ''){
		unset($data[$key]);
	}
}

// SORT ITEMS BY NAME
$data = array_orderby($data, 'name', SORT_ASC);
// make DATA_ARRAY()
$data_array = [];
foreach ($data as $item => $value) {
    // build the main (config) information
    if (isset($value['type']) && 'main' == $value['type']) {
        foreach ($value as $ke => $va) {
            $data_array['config_params'][$ke] = $va;
        }
    }
    // build the container
    if (isset($value['type']) && 'container' == $value['type']) {
		// get hex of color from colorset		
		$value['color_hex'] = color($color_set, 'get_hex', $value['color']);
        foreach ($value as $ke => $va) {
            $data_array[$value['id']][$ke] = $va;
        }
    }
    // build the items
    if (isset($value['type']) && 'item' == $value['type']) {
        foreach ($value as $ke => $va) {
            $data_array[$value['c_id']][$value['id']][$ke] = $va;
		}
    }
}

// SORT FUNCTION
function array_orderby(){
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = [];
            foreach ($data as $key => $row) {
                $tmp[$key] = $row[$field];
            }
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

// SORT CONTAINER BY POSITION
$data_array = array_orderby($data_array, 'position', SORT_ASC);

// RESET POSITION NUMBERS
function reset_positions(&$item2, $key, &$i){
    $item2['position'] = $i;
    $i = $i + 10;
    // pprint($item2['position']);
}
$i = 0;
array_walk($data_array, 'reset_positions', $i);

/*_________________________________________________ DATA_ARRAY _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ EDIT ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['edit']) && isset($user_ID)) {
    $form_case = 'edit';
    $container_key = $data[$_POST['edit']]['c_id'] ?? '';
    $item_key = $_POST['edit'] ?? '';

    $c_id = $data_array[$container_key]['id'] ?? '';
    $c_name = $data_array[$container_key]['name'] ?? '';
    $c_pos = $data_array[$container_key]['position'] ?? '';
    $c_color = $data_array[$container_key]['color_hex'] ?? '';
    $c_color_name = $data_array[$container_key]['color'] ?? '';

    $i_id = $data_array[$container_key][$item_key]['id'] ?? '';
    $i_name = $data_array[$container_key][$item_key]['name'] ?? '';
    $i_url = $data_array[$container_key][$item_key]['url'] ?? '';
    $i_user = $data_array[$container_key][$item_key]['user'] ?? '';
    $i_pw = $data_array[$container_key][$item_key]['pw'] ?? '';
    $i_post_data = $data_array[$container_key][$item_key]['post_data'] ?? '';
    $i_notes = $data_array[$container_key][$item_key]['notes'] ?? '';
	$checked = 'checked';
	$entry_text = 'Edit Entry';
	$container_extra_checkbox = '';
	$item_extra_checkbox = 'checked';
	$edit_css = "#hr{background: $c_color;} .new_item_form fieldset, .new_item_form legend, .new_item_form label.change_color{color: $c_color; border-color:$c_color;}";
} else {
    $form_case = 'new';
    $c_id = '';
    $c_name = '';
    $c_pos = '';
	$c_color = '';
	$c_color_name = '';
    $i_id = '';
    $i_name = '';
    $i_url = '';
    $i_user = '';
    $i_pw = '';
    $i_post_data = '';
	$i_notes = '';
	$edit_css ='';
	$entry_text = 'New Entry';
	$container_extra_checkbox = 'checked';
	$item_extra_checkbox = '';

}
/*_________________________________________________ EDIT _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/

// alle verfügbaren Schlüssel der Arrays von Variablen ausgeben
// pprint(array_keys(get_defined_vars()));
//    <a class="" href="<?= $db_file ? >"><?= $db_file ? ></a>
//
// <?= $config_array['name']? >

$debug_array['$_POST'] = $_POST;
$debug_array['config_params'] = $data_array['config_params'];
$debug_array['$_SESSION'] = $_SESSION;
/*_________________________________________________ DEBUG _________________________________________________*/

  

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ TIDY_TEMPLATE ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
 ob_start();
require 'template.php';
$html = ob_get_clean();
$config = array(
    'indent' => true,
    'indent-spaces' => 2,
    'output-html' => true,
    'escape-cdata' => true, 
    'drop-empty-elements' => false, 
    'clean' => false,
    'hide-comments' => true, 
    'wrap' => 0
 );
$tidy = new tidy;
$tidy->parseString($html, $config, 'utf8');
$tidy->cleanRepair();
echo $tidy;
 
/*_________________________________________________ TIDY_TEMPLATE _________________________________________________*/
 

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ HELPER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function br($nr = 1){
    for ($i = 0; $i < $nr; ++$i) {
        $br .= '<br >';
    }
    return $br;
}

// GET NEXT VALUE FROM ARRAY
function next_value($value, $array){
    $index = array_search($value, $array);
    if ($index !== false) {
        if ($index < count($array) - 1) {
            return $array[$index + 1];
        } else {
            return $array[0];
        }
    }
}

// MAKE RANDOM STRINGS FOR DUMMYS
function rand_str($nr = 7){
    $str = str_split('qwrertzuiopasdgfhjklmnbvcxy');
    shuffle($str);
    $str = array_slice($str, 0, $nr);
    $str = implode('', $str);
    $str = ucfirst($str);
    return $str;
}
function rand_id(){
    return str_pad(rand(0, 999999), 6, 0, STR_PAD_LEFT);
}



// COLORS 
function color($color_set, $func = 'rand', $val = 'hex'){
    $color_sets = array(
        'bright' => array(
            'container' => array(
                'color_1' => array('Red', '#800000'),
                'color_2' => array('lightRed', '#FF0000'),
                'color_3' => array('Orange', '#FF4500'),
                'color_4' => array('Yellow', '#FFD700'),
                'color_5' => array('Green', '#2E8B57'),
                'color_6' => array('lightGreen', '#9ACD32'),
                'color_7' => array('Blue', '#4682B4'),
                'color_8' => array('lightBlue', '#1E90FF'),
                'color_9' => array('Violet', '#8A2BE2'),
                'color_0' => array('Purple', '#EE82EE')
            ),
            'site' => array(
                'bg_out' => array('White', '#dbdbdb'),
                'bg_in' => array('White', '#ececec'),
                'bg_in_focus' => array('White', '#ffffff'),
                'border_fieldset' => array('Grey', '#5b5b5b'),
                'border_formfields' => array('Grey', '#5b5b5b'),
                'font_label' => array('Grey', '#808080'),
                'font_text' => array('Grey', '#808080'),
                'font_error' => array('Red', '#9F0000'),
                'font_shaddow' => array('Grey', '#0009'),
                'icon_red' => array('Red', '#9F0000'),
                'icon_red_ckeck' => array('Red', '#ff0101'),
                'icon_blue' => array('Red', '#09568d')
            )
        ),
        'oneDark' => array(
            'container' => array(
                'color_1' => array('red', '#ff5458'),
                'color_2' => array('salomon', '#ff8080'),
                'color_3' => array('orange', '#ffb378'),
                'color_4' => array('sienna', '#ffe9aa'),
                'color_5' => array('green', '#98C379'),
                'color_6' => array('lime', '#62d196'),
                'color_7' => array('Blue', '#09568d'),
                'color_8' => array('SteelBlue', '#4682B4'),
                'color_9' => array('violett', '#906cff'),
                'color_0' => array('mangenta', '#c991e1')
            ),
            'site' => array(
                'bg_out' => array('Red', '#1e1e1e'),
                'bg_in' => array('Red', '#232323'),
                'bg_in_focus' => array('Red', '#242424'),
                'border_fieldset' => array('Red', '#808080'),
                'border_formfields' => array('Red', '#00000060'),
                'font_label' => array('Red', '#808080'),
                'font_text' => array('Red', '#808080'),
                'font_error' => array('Red', '#D35151'),
                'font_shaddow' => array('Red', '#000000dd'),
                'icon_red' => array('Red', '#500000'),
                'icon_red_ckeck' => array('Red', '#FF5050'),
                'icon_blue' => array('Red', '#09568d')
            )
        ),
        'dark' => array(
            'container' => array(
                'color_1' => array('Sizzling Red', '#FF3855'),
                'color_2' => array('Fiery Rose', '#FF5470'),
                'color_3' => array('Heat Wave', '#FF7A00'),
                'color_4' => array('Sizzling Sunrise', '#FFDB00'),
                'color_5' => array('Slimy Green', '#299617'),
                'color_6' => array('Green Lizard', '#A7F432'),
                'color_7' => array('Denim Blue', '#2243B6'),
                'color_8' => array('Blue Jeans', '#5DADEC'),
                'color_9' => array('Purple Plum', '#9C51B6'),
                'color_0' => array('Frostbite', '#E936A7')
            ),
            'site' => array(
                'bg_out' => array('Red', '#0f0f0f'),
                'bg_in' => array('Red', '#131313'),
                'bg_in_focus' => array('Red', '#1e1e1e'),
                'border_fieldset' => array('Red', '#000000'),
                'border_formfields' => array('Red', '#000000'),
                'font_label' => array('Red', '#808080'),
                'font_text' => array('Red', '#808080'),
                'font_error' => array('Red', '#D35151'),
                'font_shaddow' => array('Red', '#000000dd'),
                'icon_red' => array('Red', '#500000'),
                'icon_red_ckeck' => array('Red', '#FF5050'),
                'icon_blue' => array('Red', '#09568d')
            )
        )
    );
    $keys = array_keys($color_sets[$color_set]['container']);
    $count = count($color_sets[$color_set]['container']) - 1;
    switch ($func) {
        case 'rand':
            if ($val === 'hex') {
                return $color_sets[$color_set]['container'][$keys[random_int(0, $count)]][1];
            }
            if ($val === 'name') {
                return $color_sets[$color_set]['container'][$keys[random_int(0, $count)]][0];
            }
            if ($val === 'class') {
                return $keys[array_rand($keys)];
            }
        case 'container_color':
            foreach ($color_sets[$color_set]['container'] as $name => $value) {
                $selected = ($val == $name) ? 'selected' : '';
                echo "<option id='$value[1]' class='$name' value='$name' $selected>$value[0]</option>";
            }
            break;
        case 'get_hex':
            return $color_sets[$color_set]['container'][$val][1] ?? 'grey';
            break;
        case 'get_colorsets':
            foreach ($color_sets as $name => $val) {
                $selected = ($name == $color_set) ? 'selected' : '';
                echo "<option value='$name' $selected>$name</option>";
            }	 
            break;
        case 'get_container_colors':
            foreach ($color_sets[$color_set]['container'] as $name => $val) {
                echo "\n.$name{color:$val[1]; border-color:$val[1];} /*$val[0]*/";
            }
            break;
		case 'get_site_colors':
		    echo "\n:root{";
            foreach ($color_sets[$color_set]['site'] as $name => $val) {
                echo "\n--$name: $val[1];  /*$val[0]*/";
            }	 
		    echo "\n}";
            break;
    } //SWITCH
}
// echo  color('bright', 'rand','hex').'<br>';
// echo  color('bright', 'rand','name').'<br>';
// echo  color('bright', 'rand','class').'<br>';
// echo  color('bright', 'css','').'<br>';
// echo  color('bright', 'container_color','color_5').'<br>';
// echo  color('bright', 'get_hex','color_1').'<br>';
// echo  color('bright', 'get_colorsets','').'<br>';
// echo  color('bright', 'get_site_colors','').'<br>';

// DUMMY_DATA  
function dummy_data(){
	global $data, $db_file;
	// delete array content
	$data = [];
	// MAIN ARRAY
	$data['config'] = [
		'type' => 'main',
		'name' => 'Dummy',
		'colorset' => 'oneDark',
		'grid_style' => 'grid',
		'position' => '0'
	];
	// CONTAINER ARRAYS
	for ($i = 0; $i < 12; ++$i) {
		$container_name = rand_str(random_int(5, 9));
		$container_id = 'C_' . rand_id();
		$data[] = [
			'id' => $container_id,
			'type' => 'container',
			'name' => $container_name,
			'position' => 1,
			'color' => color('oneDark','rand', 'class')
		];
		// ITEM ARRAYS
		for ($j = 0; $j < random_int(1, 19); ++$j) {
			$data[] = [
				'id' => 'I_' . rand_id(),
				'type' => 'item',
				'c_id' => $container_id,
				'name' => rand_str(random_int(10, 19)),
				'url' => rand_str(11),
				'user' => rand_str(5),
				'pw' => rand_str(5),
				'post_data' => rand_str(5),
				'notes' =>
				rand_str(5) .
				"<br> " .
				rand_str(3) .
				" " .
				rand_str(7) .
				" " .
				rand_str(6) .
					" " .
					rand_str(7)
			];
		}
	}
	$db_file = '';
}  

// DELETE_EMPTY_FILES
function delete_empty_files($dir_db){
	if ($handle = opendir($dir_db)) {
		while (false !== ($file = readdir($handle))) {
        if ('.' == $file || '..' == $file) {continue;}
        if (is_writable($dir_db . '/' . $file) && filesize($dir_db . '/' . $file) < 161) {
			unlink($dir_db . '/' . $file);
			}
			$file_array[] = $file;
		}
		closedir($handle);
	}
	return $file_array;
}


// SECURE  
function secure($action, $key, $file, $string){
    $output = false;
    $method = 'AES-256-CBC';
    $init_vector = 'just some ramdom text';
    $key = hash('sha256', $key);
    $init_vector = substr(hash('sha256', $init_vector), 0, 16);

    if ('encrypt' == $action) {
        $string = json_encode($string);
        $string = openssl_encrypt($string, $method, $key, 0, $init_vector);
        $string = base64_encode($string);
        if (file_put_contents($file, $string) > 0) {
            $output = true;
        }
    } elseif ('decrypt' == $action) {
        $string = file_get_contents($file);
        $string = base64_decode($string);
        $string = openssl_decrypt($string, $method, $key, 0, $init_vector);
        $output = json_decode($string, true);
        if (is_string($output)) {
            $output = json_decode($output, true);
        }
    }
    return $output;
}











/// FAVICON 
// https://github.com/audreyr/favicon-cheat-sheet
function grap_favicon($url, $name){
	if(empty($url)){ return null;}
    $directory = './data';
    $DEBUG     = null; // Give all Debug-Messages ('debug') or only make the work (null)
    // avoid script runtime timeout
    $max_execution_time = ini_get("max_execution_time");
    set_time_limit(0); // 0 = no timelimit
    // URL to lower case
    $url = strtolower($url);
    // Get the Domain from the URL
    $domain = parse_url($url, PHP_URL_HOST);
    // Check Domain
    $domainParts = explode('.', $domain);
    if (count($domainParts) == 3 and $domainParts[0] != 'www') {
        // With Subdomain (if not www)
        $domain = $domainParts[0] . '.' . $domainParts[count($domainParts) - 2] . '.' . $domainParts[count($domainParts) - 1];
    } else if (count($domainParts) >= 2) {
        // Without Subdomain
        $domain = $domainParts[count($domainParts) - 2] . '.' . $domainParts[count($domainParts) - 1];
    } else {
        // Without http(s)
        $domain = $url;
    }
    if ($DEBUG == 'debug'){print('<b style="color:red;">Domain</b> #' . @$domain . '#<br>');}
    // Make Path & Filename
    $filePath = preg_replace('#\/\/#', '/', $directory . '/' . $domain . '.png');
    // If Favicon not already exists local
    // if (!file_exists($filePath) or @filesize($filePath) == 0) {
        // Load Page
        $html = load($url, $DEBUG);
        // Find Favicon with RegEx
        $regExPattern = '/((<link[^>]+rel=.(icon|shortcut icon|alternate icon)[^>]+>))/i';
        if (@preg_match($regExPattern, $html, $matchTag)) {
            $regExPattern = '/href=(\'|\")(.*?)\1/i';
            if (isset($matchTag[1]) and @preg_match($regExPattern, $matchTag[1], $matchUrl)) {
                if (isset($matchUrl[2])) {
                    // Build Favicon Link
                    $favicon = rel2abs(trim($matchUrl[2]), 'http://' . $domain . '/');
                    // FOR DEBUG ONLY
                    if ($DEBUG == 'debug'){print('<b style="color:red;">Match</b> #' . @$favicon . '#<br>');}
                }
            }
        }
        // If there is no Match: Try if there is a Favicon in the Root of the Domain
        if (empty($favicon)) {
            $favicon = 'http://' . $domain . '/favicon.ico';
            // Try to Load Favicon
            if (!@getimagesize($favicon)) {
                unset($favicon);
            }
        }
        // If nothink works: Get the Favicon from API
        if (!isset($favicon) or empty($favicon)) {        
            // Select API by Random
            $random = rand(1, 3);       
            // Faviconkit API
            if ($random == 1 or empty($favicon)) {$favicon = 'https://api.faviconkit.com/' . $domain . '/16';}         
            // Favicongrabber API
            if ($random == 2 or empty($favicon)) {$echo = json_decode(load('http://favicongrabber.com/api/grab/' . $domain, FALSE), TRUE);          $favicon = @$echo['icons']['0']['src'];            }          
            // Google API (check also md5() later)
            if ($random == 3) {$favicon = 'http://www.google.com/s2/favicons?domain=' . $domain;}
            // FOR DEBUG ONLY
            if ($DEBUG == 'debug'){print('<b style="color:red;">' . $random . '. API</b> #' . @$favicon . '#<br>');}
        } // END If nothink works: Get the Favicon from API
        
        // Write Favicon local
        // $filePath = preg_replace('#\/\/#', '/', $directory . '/' . $domain . '.png');            
        //  Load Favicon
        $content = load($favicon, $DEBUG);

        // If Google API don't know and deliver a default Favicon (World)
        // if ( isset($random) and $random == 3 and md5($content) == '3ca64f83fdcf25135d87e08af65e68c9' ) {$domain = 'default';  }

        if ($DEBUG == 'debug'){print($content);}
        if ( (md5($content) == '3ca64f83fdcf25135d87e08af65e68c9') or ($content == '') ) {return;}

        // Write 
        $fh = @fopen($filePath, 'wb');
        fwrite($fh, $content);
        fclose($fh);
        
        // FOR DEBUG ONLY
        if ($DEBUG == 'debug'){print('<b style="color:red;">Write-File</b> #' . @$filePath . '#<br>');}
    // } // END If Favicon not already exists local
    
    // FOR DEBUG ONLY
    if ($DEBUG == 'debug') {
        // Load the Favicon from local file
        if (!function_exists('file_get_contents')) {
            $fh = @fopen($filePath, 'r');
            while (!feof($fh)) {
                $content .= fread($fh, 128); // Because filesize() will not work on URLS?
            }
            fclose($fh);
        } else {
            $content = file_get_contents($filePath);
        }
        print('<b style="color:red;">Image</b> <img style="width:32px;" src="data:image/png;base64,' . base64_encode($content) . '"><hr size="1">');
    }
    
    // reset script runtime timeout
    set_time_limit($max_execution_time); // set it back to the old value
    // Return Favicon Url
    return $filePath;
    
} // END MAIN Function

/* HELPER load use curl or file_get_contents (both with user_agent) and fopen/fread as fallback */
function load($url, $DEBUG){
    if (function_exists('curl_version')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'FaviconBot/1.0 (+http://' . $_SERVER['SERVER_NAME'] . '/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        if ($DEBUG == 'debug') { // FOR DEBUG ONLY
            $http_code = curl_getinfo($ch);
            print('<b style="color:red;">cURL</b> #' . $http_code['http_code'] . '#<br>');
        }
        curl_close($ch);
        unset($ch);
    } else {
        $context = array(
            'http' => array(
                'user_agent' => 'FaviconBot/1.0 (+http://' . $_SERVER['SERVER_NAME'] . '/)'
            )
        );
        $context = stream_context_create($context);
        if (!function_exists('file_get_contents')) {
            $fh      = fopen($url, 'r', FALSE, $context);
            $content = '';
            while (!feof($fh)) {
                $content .= fread($fh, 128); // Because filesize() will not work on URLS?
            }
            fclose($fh);
        } else {
            $content = file_get_contents($url, NULL, $context);
        }
    }
    return $content;
}

/* HELPER: Change URL from relative to absolute */
function rel2abs($rel, $base){
    extract(parse_url($base));
    if (strpos($rel, "//") === 0)
        return $scheme . ':' . $rel;
    if (parse_url($rel, PHP_URL_SCHEME) != '')
        return $rel;
    if ($rel[0] == '#' or $rel[0] == '?')
        return $base . $rel;
    $path = preg_replace('#/[^/]*$#', '', $path);
    if ($rel[0] == '/')
        $path = '';
    $abs = $host . $path . "/" . $rel;
    $abs = preg_replace("/(\/\.?\/)/", "/", $abs);
    $abs = preg_replace("/\/(?!\.\.)[^\/]+\/\.\.\//", "/", $abs);
    return $scheme . '://' . $abs;
} 
/// FAVICON 

// // add missing color
// if ('' == $data_array[$value['id']]['color']) {$data_array[$value['id']]['color'] = color('rand', 'class');}
// // add missing positon
// if (!isset($data_array[$value['id']]['position'])) {$data_array[$value['id']]['position'] = $data_array[$value['id']]['name'];}

/*_________________________________________________ HELPER _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ PRETTY_PRINT ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function pprint_css(){
    echo <<<EOL
 #pretty_print {font-family: Consolas, monaco, monospace;font-size: 1em;background-color: #b1b1b1;border: 1px solid #949494;border-radius: 5px;width: max-content; height: max-content; margin: 20px;}
 #pretty_print input[type="checkbox"] {position: absolute;left: -100vw;}
 #pretty_print label {display: inline-block;width: 100%;font-weight: bold;margin: .2em;cursor: pointer;}
 #pretty_print label span.linenumber {position: relative;top: 3px;right: 10px;float:right;font-weight: normal;font-size: 80%; color:white;}
 #pretty_print pre {background: lightgray;margin: 0px;padding: 5px;overflow-y: scroll;max-height: 400px;padding-right: 50px;}
 #pretty_print pre::-webkit-scrollbar {display: none;}
 #pretty_print pre{-ms-overflow-style: none;  scrollbar-width: none; }
 #pretty_print pre span {line-height: 1.5em;}
 #pretty_print pre span.null {color: black;}
 #pretty_print pre span.boolean {color: brown;}
 #pretty_print pre span.double {color: darkgreen;}
 #pretty_print pre span.integer {color: green;}
 #pretty_print pre span.string {color: darkblue;}
 #pretty_print pre span.array {color: black;}
 #pretty_print pre span.object {color: black;}
 #pretty_print pre span.type {color: grey;}
 #pretty_print pre span.public {color: darkgreen;}
 #pretty_print pre span.protected {color: red;}
 #pretty_print pre span.private {color: darkorange;}
EOL;
}

function pprint($arr, $printable = 0, $type = 0, $hide = 0){
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $id = random_int(0, 999);
    echo "\n<!-- PRETTY_PRINT -->\n";
    // var_dump($caller);
    echo "<div id='pretty_print'>\n\t";
    echo "<style>#hide_$id:checked ~ pre{display: none;}</style>\n\t";
    echo "<label for='hide_$id'>$" .
        print_var_name($arr) .
        " <span class='linenumber'>&nbsp; " .
        basename($caller['file']) .
        ':' .
        $caller['line'] .
        "</span></label>\n\t";
    echo "<input type='checkbox' id='hide_$id' class='hidden' " .
        ($hide ? ' checked' : '') .
        " >\n\t";
    echo "<pre>\n";
    pprint_array($arr, '', $printable, $type);
    echo $printable ? ';' : '';
    echo "\t</pre>\n";
    echo "</div>\n";
    echo "<!-- PRETTY_PRINT -->\n\n";
}

function pprint_array($arr, $p, $printable, $type){
    if (1 == $printable) {
        $arround = [
            'array_1' => 'array(',
            'array_2' => ')',
            'key_1' => '[',
            'key_2' => ']',
            'value_1' => '"',
            'value_2' => '"',
            'type_1' => '[',
            'type_2' => ']',
            'sep' => ','
        ];
    } else {
        $arround = [
            'array_1' => '',
            'array_2' => '',
            'key_1' => '',
            'key_2' => '',
            'value_1' => '',
            'value_2' => '',
            'type_1' => '',
            'type_2' => '',
            'sep' => ''
        ];
    }
    $t = gettype($arr);
    switch ($t) {
        case 'NULL':
            echo '<span class="null"><b>NULL</b></span>' . $arround['sep'];
            break;

        case 'boolean':
            echo '<span class="boolean">' .
                (0 == $arr ? 'false' : 'true') .
                '</span>' .
                $arround['sep'] .
                ($type ? ' <span class="type">boolean</span>' : '');
            break;

        case 'double':
            echo '<span class="double">' .
                $arr .
                '</span>' .
                $arround['sep'] .
                ($type ? ' <span class="type">double</span>' : '');
            break;

        case 'integer':
            echo '<span class="integer">' .
                $arr .
                '</span>' .
                $arround['sep'] .
                ($type ? ' <span class="type">integer</span>' : '');
            break;

        case 'string':
            echo $arround['value_1'] .
                '<span class="string">' .
                $arr .
                '</span>' .
                $arround['value_2'] .
                $arround['sep'] .
                ($type
                    ? ' <span class="type">string(' . strlen($arr) . ')</span>'
                    : '');
            break;

        case 'array':
            echo $arround['array_1'] .
                ($type
                    ? ' <span class="type">(' . count($arr) . ')</span>'
                    : '') .
                "\r\n";

            foreach ($arr as $k => $v) {
                if ('string' == gettype($k)) {
                    echo $p .
                        "\t" .
                        $arround['key_1'] .
                        $k .
                        $arround['key_2'] .
                        ' => ';
                } else {
                    echo $p . "\t" . '' . $k . ' => ';
                }
                pprint_array($v, $p . "\t", $printable, $type);
                echo "\r\n";
            } // foreach $arr
            echo $p . $arround['array_2'] . $arround['sep'];
            break;

        case 'object':
            $class = get_class($arr);
            $super = get_parent_class($arr);
            echo "<span class='object'>Object</span>(" .
                $class .
                (false != $super ? ' exdends ' . $super : '') .
                ')';
            echo ($printable ? '{' : '') . "\r\n";
            $o = (array) $arr;
            foreach ($o as $k => $v) {
                $o_type = '';
                $name = '';
                if ('*' == substr($k, 1, 1)) {
                    $o_type = 'protected';
                    $name = substr($k, 2);
                } elseif (substr($k, 1, strlen($class)) == $class) {
                    $o_type = 'private';
                    $name = substr($k, strlen($class) + 1);
                } elseif (
                    false != $super &&
                    substr($k, 1, strlen($super)) == $super
                ) {
                    $o_type = $super . ' private';
                    $name = substr($k, strlen($super) + 1);
                } else {
                    $o_type = 'public';
                    $name = $k;
                }
                if ($printable) {
                    echo $p .
                        "\t" .
                        $arround['type_1'] .
                        "<span class='$o_type'>" .
                        $o_type .
                        ': ' .
                        $name .
                        '</span>' .
                        $arround['type_2'] .
                        ' => ';
                } else {
                    echo $p .
                        "\t" .
                        "<span class='$o_type'>" .
                        $name .
                        '</span> => ';
                }

                pprint_array($v, $p . "\t", $printable, $type);
                echo "\r\n";
            }
            echo $p . $printable ? '}' : '';
            break;

        default:
            break;
    } // switch
} // function

// get name of $var as string
function print_var_name($var){
    foreach ($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }
    return 'array';
}
/*_________________________________________________ PRETTY_PRINT _________________________________________________*/
