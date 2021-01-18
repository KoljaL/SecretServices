<!DOCTYPE html>
<html lang="de">

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SecretServices</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" type="image/svg+xml" href="umuk.svg">
	<link rel="alternate icon" href="favicon.ico">
	<link rel="stylesheet" href="style.css">
	<style>
		<?php echo pprint_css() ?>
		<?php color('css') ?>
		#grey {
			color: grey;
		}

		/* < ?=$edit_css ?> */
	</style>
</head>

<body id="body" style="background-color: <?php echo $config_array['color'] ?>;">
	<div class="content">
		<div class="message">
			<?//pprint($debug_array, 0, 0, 0) ?>
			<?//pprint($data_array, 0, 0, 0) ?>
			<?//pprint($data, 0, 0, 0) ?>
			<?//pprint($_POST, 0, 0, 0) ?>
		</div>
		<h1><a href="<?php echo $url ?>">SecretServices</a></h1>



		<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ USER_LINKS ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
		<div class="user_links">
			<?php function sel_col($name) {	return ($name == $_SESSION['colors']) ? 'selected="selected"' : ''; } ?>
			<div class="colorset">
				<form method="post">
					<select name="colors" onchange="this.form.submit();">
						<option style='color: grey' value='Colors' disabled>Colors</option>
						<option style='color: grey' value='ONEDARK' <?php echo sel_col('ONEDARK') ?>>OneDark </option>
						<option style='color: grey' value='HTML' <?php echo sel_col('HTML') ?>>HTML </option>
						<option style='color: grey' value='MA_BO' <?php echo sel_col('MA_BO') ?>>MA </option>
					</select>
				</form>
			</div>
			<?php if (!isset($_SESSION['logged_in']) || false == $_SESSION['logged_in']) { ?>
				<button class="login"><label class="login_form font_brighter" for="login_form">&#9998;</label></button>
				<input type="checkbox" id="login_form" class="hidden">
				<div class="login_form hidden">
					<form action="<?php echo $url ?>" method="POST">
						<input type="text" placeholder="name" name="u_name" value="">
						<input type="password" placeholder="password" name="u_password" value="">
						<input type="hidden" name="login" value="true">
						<button for="login" class="login">&#10004; </button>
					</form>
				</div>
				<?php } if (isset($_SESSION['logged_in']) && true == $_SESSION['logged_in']) { ?>
				<div class="login_form">
					<form action="<?php echo $url ?>" method="post" id="logout">
						<input type="hidden" name="logout" value="true">
						<button form="logout" class="logout">&#10008; </button>
					</form>
				</div>
				<button class="logout"><label for='new_entry'>&#9998;</label></button> 
				<?php } ?>
		</div>
		<!-- _________________________________________________ USER_LINKS _________________________________________________ -->



		<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ CONTAINER ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
		<?php foreach ($data_array as $container => $value) { ?>
			<div style="color:<?php echo $data_array[$container]['color'] ?>" class="container">
				<fieldset style="border-color: <?php echo $data_array[$container]['color'] ?>">
					<legend> <?php echo $data_array[$container]['name'] ?><?php echo $data_array[$container]['position'] ?></legend>
					<ul>
						<?php foreach ($value as $item => $param): ?>
						<?php if(substr($item,0,2) != 'I_'){continue;} ?>
						<!-- ITEMS  --> 
						    <div class="item">
						        <span class="font_brighter">
						            <?php if (isset($param['post_data'])) { ?>
					                <a href="#" onclick="URL2post('<?php echo $param['url'] ?>', {<?php echo $param['post_data'] ?>});"><?php echo $param['name'] ?></a>
					                <?php } else { ?>
					                <a href="<?php echo $param['url'] ?>" > <?php echo $param['name'] ?> </a>
					                <?php } ?>
						        </span>
						        <?php if (isset($_SESSION['logged_in']) && true == $_SESSION['logged_in']) { ?>
						        <div class="icons">
						            <form action='' method='post'>
						                <button type='submit' name='edit' value='<?php echo $param['id'] ?>' class='icon icon_cal font_brighter'>✎</button>
						            </form>
						        </div>
						        <?php } ?>
						    </div> 
						<!-- ITEMS  --> 
						<?php endforeach; ?>
					</ul>
				</fieldset>
			</div>
			<?php } ?>
		<!-- _________________________________________________ CONTAINER _________________________________________________ -->





		<!-- ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ NEW_ENTRY_FORM ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾ -->
		
		<?php if (isset($_SESSION['logged_in']) && true == $_SESSION['logged_in']) { ?>
		<input type="checkbox" id="new_entry" class="hidden" <?php echo $checked ?> >
		<section class="new_item_form hidden">
			<fieldset class="change_color">
				<legend class="change_color">New Entry</legend>
				<form method="post" action="<?php echo $url ?>" onsubmit="return validateForm()" class="new_entry">
					<input type="checkbox" id="container_extra" class="hidden">
					<input type="checkbox" id="item_extra" class="hidden">
					
					<input type="hidden"  id="c_id" name="container[id]" value="<?php echo $c_id ?>" class="hidden">
					<input type="hidden"  id="i_id" name="item[id]" value="<?php echo $i_id ?>" class="hidden">
					<input type="hidden"  id="form_case" name="form_case" value="<?= $form_case ?>" class="hidden">
					
					<div tabindex="0" id="container">
						<label for="container_select" class="change_color">Container</label>
						<label class="icon icon_more" for="container_extra">[...]</label>
						<label class="checkbox_del">
							<input type="checkbox" id="del_C" name="icon_del" class="icon_del font_brighter" value="1">
						</label>
						<input type="text" name="container[name]" id="container_input" value="<?php echo $c_name ?>" required />
						<span class="dropdown"></span>
						<select class="container_select" name="container[id2]" id="container_select">
							<!-- name="container_select" -->
							<option disabled selected>select Container</option>
							<?php foreach ($data_array as $container => $item) {
								$name = $item['name'];
								$selected = ($name == $c_name) ? 'selected="selected"' : '';
								echo "\t\t\t\t\t\t<option  id='{$item['color']}' style='color: {$item['color']}' value='$name' onclick=\"setColor('$name','{$item['color']}','{$item['id']}')\" $selected>$name</option>\n";
							} ?>
						</select>
					</div>
					<div tabindex="4" id="submit">
						<!--<input type="hidden" name="new_entry" value="true">-->
						<button>Send</button>
					</div>
					<div id="position">
						<label for="position_select" class="change_color">Position</label>
						<select name="container[position]" id="position_select">
							<option style="color: grey" value="1">first</option> 
							<?php foreach ($data_array as $container => $item) {
								$selected = ($c_pos == $item['position']) ? 'selected="selected"' : '';
								$next_position = $item['position'] + 1;
								echo "\t\t\t\t\t\t<option style='color: {$item['color']}' value='$next_position' $selected>{$item['name']} &#8680;</option>\n";
							} ?>
						</select>
					</div>
					<div tabindex="1" id="color">
						<label for="color_select" class="change_color">Color</label>
						<select name="container[color]" id="color_select">
							<option id='grey' value='grey'>grey</option>
							<?php foreach ($colors as $name => $hex) {
								$selected = ($c_color == $hex[1]) ? 'selected="selected"' : '';
								echo "\t\t\t\t\t\t<option id='$name' value='$hex[1]' $selected>$hex[0]</option>\n";
							} ?>
						</select>
					</div>
					<div id="hr" class="change_color"></div>
					<div id="name">
						<label for="name_input" class="change_color">Name</label>
						<label class="icon icon_more" for="item_extra">[...]</label>
						<label class="checkbox_del">
							<input type="checkbox" id="del_C" name="icon_del" class="icon_del font_brighter" value="1">
						</label>
						<input type="text" name="item[name]" id="name_input" value="<?php echo $i_name ?> " />
					</div>
					<div id="url">
						<label for="url_input" class="change_color">Link</label>
						<input type="text" name="item[url]" id="url_input" value="<?php echo $i_url ?> " />
					</div>
					<div id="user">
						<label for="user_input" class="change_color">User</label>
						<input type="text" name="item[user]" id="user_input" value="<?php echo $i_user ?> " />
					</div>
					<div id="password">
						<label for="password_input" class="change_color">Password</label>
						<input type="text" name="item[password]" id="password_input" value="<?php echo $i_pw ?> " />
					</div>
					<div id="post_data">
						<label for="post_data_text" class="change_color">POST Data</label>
						<textarea id="post_data_textarea" name="item[post_data_text]" placeholder="key: 'value',\nkey: 'value'"><?php echo $i_post_data ?></textarea>
					</div>
					<div id="notes">
						<label for="notes_text" class="change_color">Notes</label>
						<textarea id="notes_textarea" name="item[notes_text]"><?php echo $i_notes ?></textarea>
					</div>
				</form>
			</fieldset>
		</section>
		<?php } ?>
		<!-- _________________________________________________ NEW_ENTRY_FORM _________________________________________________ -->



		<script type="text/javascript" src="functions.js"></script>
		<script>
		</script>
	</div>
</body>

</html>