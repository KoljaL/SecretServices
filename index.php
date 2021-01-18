<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_destroy();
session_start();

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ TO_DO ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾
Editiern fügt neue Einträge hinzu

Löschem con Containern und Items

über den Link nicht die exterbne Seite aufrufen, sondern wieder sieses Skript, mit Parametern zu Container und Item,
damit dann die post_data in array suchen und anschließend dass javascript ausführen

Farbsätze mit gleichen Bezeichnungen erstellen und damit die Farben auch für erstellte Container austauschbar machen

individuelle Farben für Items?
/*_________________________________________________ TO_DO _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONFIG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$url = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dir_db = 'data';
if (!file_exists($dir_db)) {
	mkdir($dir_db, 0777, true);
}
$debug_array['$dir_db'] = $dir_db;
$checked = '';
/*_________________________________________________ CONFIG _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ SESSION ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$_SESSION['last_visit'] = time();
if (!isset($_SESSION['last_visit'])) {
	$_SESSION['last_visit'] = time();
}
if ((time() - $_SESSION['last_visit']) > 300) {
	session_destroy();
	header('Location: '.$url);
}
if (isset($_POST['logout'])) {
	session_destroy();
	header('Location: '.$url);
	exit;
}
if (!isset($_SESSION['logged_in'])) {
	$_SESSION['logged_in'] = false;
}
if (isset($_POST['colors'])) {
	$_SESSION['colors'] = $_POST['colors'];
}
if (!isset($_POST['colors']) && !isset($_SESSION['colors'])) {
	$_SESSION['colors'] = 'ONEDARK';
}
/*_________________________________________________ SESSION _________________________________________________*/
// pprint($_POST);

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DELETE_EMPTY_FILES ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if ($handle = opendir($dir_db)) {
	while (false !== ($file = readdir($handle))) {
		if ('.' == $file || '..' == $file) {
			continue;
		}
		// echo $dir_db.'/'.$file .' Size: '. filesize($dir_db.'/'.$file).'<br>';
		if (is_writable($dir_db.'/'.$file) && filesize($dir_db.'/'.$file) < (161)) {
			unlink($dir_db.'/'.$file);
		}
		// $file = str_replace('.json', '', $file);
		// $file_exp = explode('_', $file);
		// $file_array[$file_exp[0]] = $file_exp[1];
		$file_array[] = $file;
	}
	closedir($handle);
}
/*_________________________________________________ DELETE_EMPTY_FILES _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ LOGIN ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['u_name']) && '' != $_POST['u_name'] && isset($_POST['u_password']) && '' != $_POST['u_password']) {
	$_SESSION['name'] = $_POST['u_name'];
	$_SESSION['password'] = $_POST['u_password'];
	$_SESSION['logged_in'] = true;
}

/*_________________________________________________ LOGIN _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ SECURE ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function secure($action, $key, $file, $string) {
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
/*_________________________________________________ SECURE _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ GET_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_SESSION['logged_in']) && true == $_SESSION['logged_in']) {
	
	// $_SESSION['db_file'] = $dir_db.'/'.md5($_SESSION['name'].$_SESSION['password']); 
	$_SESSION['db_file'] = $dir_db.'/'.$_SESSION['name'].$_SESSION['password']; 
	$db_file = $_SESSION['db_file'];

	if (!is_file($db_file)) {
		// $data = secure('decrypt', $_SESSION['password'], $db_file, '');
		// if(count($data) < 100){
 

$content = '{
    "M_00001": {"id": "M_001","type": "main","name": "SecretServices","color": "#1e1e1e"},
    "C_167834": {"id": "C_167834","type": "container","name": "Venus","color": "#ff5458", "position": "20"},
    "I_880623": {"id": "I_880623","type": "item","name": "alpha","c_id": "C_167834","url": "http://test2.de"},
    "I_840642": {"id": "I_840642","type": "item","name": "gamma","c_id": "C_167834","url": "http://test2.de"},
    "I_850633": {"id": "I_850633","type": "item","name": "betta","c_id": "C_167834","url": "http://test2.de"},
    
    "C_163844": {"id": "C_163844","type": "container","name": "Mars","color": "#09568d", "position": "30"},
    "I_886633": {"id": "I_886633","type": "item","name": "alpha","c_id": "C_163844","url": "http://test1.de"},
    "I_856623": {"id": "I_856623","type": "item","name": "gamma","c_id": "C_163844","url": "http://test1.de"},
    "I_859633": {"id": "I_859633","type": "item","name": "betta","c_id": "C_163844","url": "http://test1.de"}
}';      
			// file_put_contents($db_file, $content);
			secure('encrypt', $_SESSION['password'], $db_file, $content);
			
		}
	// }
	// $data = json_decode(file_get_contents($db_file), true);
	$data = secure('decrypt', $_SESSION['password'], $db_file, '');
	$debug_array['$_SESSION[name]'] = $_SESSION['name'];
	$debug_array['$_SESSION[password]'] = $_SESSION['password'];
	$debug_array['$_SESSION[db_file]'] = $_SESSION['db_file'];
	$debug_array['db_file'] = $db_file;
}
/*_________________________________________________ GET_DATA _________________________________________________*/

 

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
// session_destroy();

// pprint($content);
// pprint($_POST);
// // pprint($_SESSION);
// pprint($file_array);

// pprint(json_decode(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'), true));
// pprint(file_get_contents('data/user_e22a63fb76874c99488435f26b117e37.json'));

// exit;
/*_________________________________________________ DEBUG _________________________________________________*/


/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ COLORS ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
// TODO: farbarrays in die Funktion un die Funktion an Ende
// MA-BO
$MA_BO = [
	'color_a' => ['yellow', '#FAD201'],
	'color_b' => ['orange', '#FF8C00'],
	'color_c' => ['red', '#8E1B1B'],
	'color_d' => ['blue', '#103B73'],
	'color_e' => ['green', '#315B00'],
	'color_f' => ['violet', '#5C1073'],
];

// ONEDARK alike
$ONEDARK = [
	'color_a' => ['red', '#ff5458'],
	'color_b' => ['salomon', '#ff8080'],
	'color_c' => ['orange', '#ffb378'],
	'color_d' => ['sienna', '#ffe9aa'],
	'color_e' => ['green', '#98C379'],
	'color_f' => ['lime', '#62d196'],
	'color_g' => ['cyan', '#56B6C2'],
	'color_h' => ['DarkCyan', '#008B8B'],
	'color_i' => ['Blue', '#09568d'],
	'color_j' => ['SteelBlue', '#4682B4'],
	'color_k' => ['violett', '#906cff'],
	'color_l' => ['purple', '#C678DD'],
	'color_m' => ['mangenta', '#c991e1'],

	// 'color_n' => array('white', '#F8F8F8'),
	// 'color_o' => array('orange2', '#E5C07B'),
	// 'color_p' => array('background', '#282C34'),
	// 'color_q' => array('blue', '#65b2ff'),
	// 'color_r' => array('grey', '#ABB2BF')
];

$HTML = [
	'color_a' => ['BlueViolet', '#8A2BE2'],
	'color_b' => ['Violet', '#EE82EE'],
	'color_c' => ['Magenta', '#FF00FF'],
	'color_d' => ['MediumOrchid', '#BA55D3'],
	'color_e' => ['MediumPurple', '#9370DB'],
	'color_f' => ['MediumSlateBlue', '#7B68EE'],
	'color_g' => ['Salmon', '#FA8072'],
	'color_h' => ['DarkRed', '#8B0000'],
	'color_i' => ['Red', '#FF0000'],
	'color_j' => ['Pink', '#FFC0CB'],
	'color_k' => ['HotPink', '#FF69B4'],
	'color_l' => ['PaleVioletRed', '#DB7093'],
	'color_m' => ['OrangeRed', '#FF4500'],
	'color_n' => ['DarkOrange', '#FF8C00'],
	'color_o' => ['Orange', '#FFA500'],
	'color_p' => ['Gold', '#FFD700'],
	'color_q' => ['Yellow', '#FFFF00'],
	'color_r' => ['Khaki', '#F0E68C'],
	'color_s' => ['LimeGreen', '#32CD32'],
	'color_t' => ['PaleGreen', '#98FB98'],
	'color_u' => ['SeaGreen', '#2E8B57'],
	'color_v' => ['YellowGreen', '#9ACD32'],
	'color_w' => ['Olive', '#808000'],
	'color_x' => ['DarkCyan', '#008B8B'],
	'color_y' => ['SteelBlue', '#4682B4'],
	'color_z' => ['DodgerBlue', '#1E90FF'],
	'color_aa' => ['CornflowerBlue', '#6495ED'],
	'color_ab' => ['MediumSlateBlue', '#7B68EE'],
	'color_ac' => ['Blue', '#0000FF'],
	'color_ad' => ['MidnightBlue', '#191970'],
	'color_ae' => ['SandyBrown', '#F4A460'],
	'color_af' => ['Maroon', '#800000'],
];
$Colors = $ONEDARK;
// make a string (from POST) to var name
$colors = eval('return $'. $_SESSION['colors'] . ';');

function color($func = 'rand', $val = 'hex') {
	global $colors;
	$keys = array_keys($colors);
	$count = count($colors) - 1;
	switch ($func) {
		case 'rand':
			return $colors[$keys[random_int(0, $count)]][('hex' === $val) ? 1 : 0];
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
if (isset($_POST['container']) && true == $_SESSION['logged_in']) {

	// load existig data
	$data = secure('decrypt', $_SESSION['password'], $db_file, '');
	// $data = array();

	// NEW CONTAINER
	if (isset($_POST['container']['id']) and $_POST['container']['id'] != '') {
		$container_id = $_POST['container']['id'];
	} else {
		$container_id = 'C_'.rand_id();
	}
	$data[$container_id] = [
		'id' => $container_id,
		'type' => 'container',
		'name' => $_POST['container']['name'] ?? '',
		'position' => $_POST['container']['position'] ?? '',
		'color' => $_POST['container']['color'] ?? '',
	];
	if (!isset($data[$container_id]['color']) || '' == $data[$container_id]['color']) {
		$data[$container_id]['color'] = color('rand', 'hex');
	}

	// NEW ITEM
	if (isset($_POST['item']['id'])  and $_POST['item']['id'] != '') {
		$item_id = $_POST['item']['id'];
	} else {
		$item_id = 'I_'.rand_id();
	}
	$data[$item_id] = [
		'id' => $item_id,
		'type' => 'item',
		'name' => $_POST['item']['name'] ?? '',
		'c_id' => $container_id,
		'url' => $_POST['item']['url'] ?? '',
		'user' => $_POST['item']['user'] ?? '',
		'pw' => $_POST['item']['password'] ?? '',
		'post_data' => $_POST['item']['post_data_text '] ?? '',
		'notes' => $_POST['item']['notes_text '] ?? '',
	];
	// pprint($data);
	// file_put_contents($db_file, json_encode($data));
	secure('encrypt', $_SESSION['password'], $db_file, $data);
}
/*_________________________________________________ NEW_ENTRY _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DUMMY_DATA ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (!isset($_SESSION['logged_in']) || false == $_SESSION['logged_in']) {
	// delete array content
	$data = [];
	// MAIN ARRAY
	$data[] = ['type' => 'main',
		'name' => 'Dummy',
		'color' => '#1e1e1e'];
	// CONTAINER ARRAYS
	for ($i = 0; $i < 2; ++$i) {
		$container_name = rand_str(random_int(5, 9));
		$container_id = 'C_'.rand_id();
		$data[] = [
			'id' => $container_id,
			'type' => 'container',
			'name' => $container_name,
			'position' => 1,
			'color' => ''];
		// ITEM ARRAYS
		for ($j = 0; $j < 3; ++$j) {
			$data[] = [
				'id' =>  'I_'.rand_id(),
				'type' => 'item',
				'c_id' => $container_id,
				'name' => rand_str(random_int(10, 19)),
				'url' => rand_str(11),
				'user' => rand_str(5),
				'pw' => rand_str(5),
				'post_data' => rand_str(5),
				'notes' => rand_str(5)];
		}
	}
	$db_file = '';
	$debug_array['$_SESSION[logged_in]'] = $_SESSION['logged_in'];

	// pprint($_POST);
	// pprint($data);
}
/*_________________________________________________ DUMMY_DATA _________________________________________________*/


// SORT ITEMS BY NAME
$data = array_orderby($data,   'name', SORT_ASC);




// 		pprint($data_array);

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DATA_ARRAY ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
$data_array = [];
$config_array = [];
foreach ($data as $item => $value) {
	// build the main (config) information 
	if (isset($value['type']) && 'main' == $value['type']) {
		foreach ($value as $ke => $va) {
			$config_array[$ke] = $va;
		}
	}
	
	// build the container
	if (isset($value['type']) && 'container' == $value['type']) {
		foreach ($value as $ke => $va) {
			$data_array[$value['id']][$ke] = $va;
		}
		// add missing color
		if ('' == $data_array[$value['id']]['color']) {
			$data_array[$value['id']]['color'] = color('rand', 'hex');
		}

 
		// add missing positon
		if (!isset($data_array[$value['id']]['position'])) {
			$data_array[$value['id']]['position'] = $data_array[$value['id']]['name'];
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
function array_orderby() {
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
function reset_positions(&$item2, $key, &$i) {
	$item2['position'] = $i;
	$i = $i + 10;
	// pprint($item2['position']);
}
$i = 10;
array_walk($data_array, 'reset_positions', $i);
 
		

// SORT ITEMS BY NAME
// foreach ($data_array as $key => $value) {
// 	foreach ($value as $container => $item) {
// 	if(is_array($item)){
		
// 		// pprint($data_array[$key][$container]['name']);
// 		// pprint($item);
// 		$data_array[$key][$item] = array_orderby($data_array[$key][$item], 'name', SORT_ASC);
// 	}
// 	}
// }

$debug_array['$config_array'] = $config_array;
$debug_array['$data_array'] = $data_array;

/*_________________________________________________ DATA_ARRAY _________________________________________________*/




/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ EDIT ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
if (isset($_POST['edit']) && !empty($_POST['edit'])) {
	$form_case = 'edit';
	// $contim = explode('_{§}_', $_POST['edit']) ?? ''
	// $container_key = $contim[0] ?? '';
	// $item_key = $contim[1] ?? '';
	
	// pprint($data[$_POST['edit']]['c_id']);
	$container_key = $data[$_POST['edit']]['c_id'] ?? '';
	$item_key = $_POST['edit'] ?? '';
	
	$c_id = $data_array[$container_key]['id'] ?? '';
	$c_name = $data_array[$container_key]['name'] ?? '';
	$c_pos = $data_array[$container_key]['position'] ?? '';
	$c_color = $data_array[$container_key]['color'] ?? '';
	
	$i_id = $data_array[$container_key][$item_key]['id'] ?? '';
	$i_name = $data_array[$container_key][$item_key]['name'] ?? '';
	$i_url = $data_array[$container_key][$item_key]['url'] ?? '';
	$i_user = $data_array[$container_key][$item_key]['user'] ?? '';
	$i_pw = $data_array[$container_key][$item_key]['pw'] ?? '';
	$i_post_data = $data_array[$container_key][$item_key]['post_data'] ?? '';
	$i_notes = $data_array[$container_key][$item_key]['notes'] ?? '';
	$checked = 'checked';
	$edit_css = ".change_color{color: $c_color ;}  \n\t#container{color: $c_color ;} \n\t#new_item fieldset{border-color:$c_color }\n";
} else {
	$form_case = 'new';
	$c_id = '';
	$c_name = '';
	$c_pos = '';
	$c_color = '';
	$i_id = '';
	$i_name = '';
	$i_url = '';
	$i_user = '';
	$i_pw = '';
	$i_post_data = '';
	$i_notes = '';
}
/*_________________________________________________ EDIT _________________________________________________*/



/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ DEBUG ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/

// alle verfügbaren Schlüssel der Arrays von Variablen ausgeben
// pprint(array_keys(get_defined_vars()));
//    <a class="" href="<?= $db_file ? >"><?= $db_file ? ></a>
//
// <?= $config_array['name']? >
/*_________________________________________________ DEBUG _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ TEMPLATE ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
require 'template.php';
/*_________________________________________________ TEMPLATE _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ HELPER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function br($nr = 1) {
	for ($i = 0; $i < $nr; ++$i) {
		$br .= '<br >';
	}
	return $br;
}

// MAKE RANDOM STRINGS FOR DUMMYS
function rand_str($nr = 7) {
	$str = str_split('qwrertzuiopasdgfhjklmnbvcxy');
	shuffle($str);
	$str = array_slice($str, 0, $nr);
	$str = implode('', $str);
	$str = ucfirst($str);
	return $str;
}
function rand_id(){ 
    return str_pad(rand(0,999999),6,0,STR_PAD_LEFT);
}
/*_________________________________________________ HELPER _________________________________________________*/

/*‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ PRETTY_PRINT ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
function pprint_css() {
echo <<<EOL
 #pretty_print {font-family: Consolas, monaco, monospace;font-size: 1em;background-color: #b1b1b1;border: 1px solid #949494;border-radius: 5px;width: max-content; height: max-content; margin: 20px;}
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
	echo "<label for='hide_$id'>$".print_var_name($arr). " <span class='linenumber'>&nbsp; ".basename($caller['file']).':'.$caller['line']."</span></label>\n\t";
	echo "<input type='checkbox' id='hide_$id' class='hidden' ".(($hide) ? ' checked' : '')." >\n\t";
	echo "<pre>\n";
	pprint_array($arr, '', $printable, $type);
	echo ($printable) ? ';' : '';
	echo "\t</pre>\n";
	echo "</div>\n";
	echo "<!-- PRETTY_PRINT -->\n\n";
}

function pprint_array($arr, $p, $printable, $type) {
	if (1 == $printable) {
		$arround = ['array_1' => 'array(',
			'array_2' => ')',
			'key_1' => '[',
			'key_2' => ']',
			'value_1' => '"',
			'value_2' => '"',
			'type_1' => '[',
			'type_2' => ']',
			'sep' => ','];
	} else {
		$arround = ['array_1' => '',
			'array_2' => '',
			'key_1' => '',
			'key_2' => '',
			'value_1' => '',
			'value_2' => '',
			'type_1' => '',
			'type_2' => '',
			'sep' => ''];
	}
	$t = gettype($arr);
	switch ($t) {
		case 'NULL':
			echo '<span class="null"><b>NULL</b></span>'.$arround['sep'];
			break;

		case 'boolean':
			echo '<span class="boolean">'.(0 == $arr ? 'false' : 'true').'</span>'.$arround['sep'].(($type) ? ' <span class="type">boolean</span>' : '');
			break;

		case 'double':
			echo '<span class="double">'.$arr.'</span>'.$arround['sep'].(($type) ? ' <span class="type">double</span>' : '');
			break;

		case 'integer':
			echo '<span class="integer">'.$arr.'</span>'.$arround['sep'].(($type) ? ' <span class="type">integer</span>' : '');
			break;

		case 'string':
			echo $arround['value_1'].'<span class="string">'.$arr.'</span>'.$arround['value_2'].$arround['sep'].(($type) ? ' <span class="type">string('.strlen($arr).')</span>' : '');
			break;

		case 'array':
			echo $arround['array_1'].(($type) ? ' <span class="type">('.count($arr).')</span>' : '')."\r\n";

			foreach ($arr as $k => $v) {
				if ('string' == gettype($k)) {
					echo $p."\t".$arround['key_1'].$k.$arround['key_2'].' => ';
				} else {
					echo $p."\t".''.$k.' => ';
				}
				pprint_array($v, $p."\t", $printable, $type);
				echo "\r\n";
			} // foreach $arr
			echo $p.$arround['array_2'].$arround['sep'];
			break;

		case 'object':
			$class = get_class($arr);
			$super = get_parent_class($arr);
			echo "<span class='object'>Object</span>(".$class.(false != $super ? ' exdends '.$super : '').')';
			echo (($printable) ? '{' : '')."\r\n";
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
				} elseif (false != $super && substr($k, 1, strlen($super)) == $super) {
					$o_type = $super.' private';
					$name = substr($k, strlen($super) + 1);
				} else {
					$o_type = 'public';
					$name = $k;
				}
				if ($printable) {
					echo $p."\t".$arround['type_1']."<span class='$o_type'>".$o_type.': '.$name.'</span>'.$arround['type_2'].' => ';
				} else {
					echo $p."\t"."<span class='$o_type'>".$name.'</span> => ';
				}

				pprint_array($v, $p."\t", $printable, $type);
				echo "\r\n";
			}
			echo $p.($printable) ? '}' : '';
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