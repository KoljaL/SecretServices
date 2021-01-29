// CHANGE COLOR
document.getElementById('color_select').onchange = function () {
	var e = document.getElementById("color_select");
	var strAtt = e.options[e.selectedIndex].getAttribute('id'); // will return the value
	var strVal = e.options[e.selectedIndex].value; // will return the value
	var strText = e.options[e.selectedIndex].text; // will return the text
	console.log(strAtt);
	console.log(strVal);
	console.log(strText);
	var list = document.getElementsByClassName("change_color");
	document.getElementById("hr").style.background = strAtt;
	for (var i = 0; i < list.length; i++) {
		list[i].style.color = strAtt;
		list[i].style.borderColor = strAtt;
	}
}
// CHANGE COLOR



// DROPDOWN CONTAINERNAME TO INPUT TEXT
document.getElementById("container_select").onchange = function () {
	// get selected value from pulldown
	var select_value = document.getElementById("container_select").value;
	// set input value with pulldown value
	document.getElementById("container_input").value = select_value;
	console.log("container_select " + select_value);
}
// DROPDOWN CONTAINERNAME TO INPUT TEXT



// set color by clicking the containername dropdown
function setColor(name, color, c_id) {
	document.getElementById("color_select").value = color;
	document.getElementById("c_id").value = c_id;
	var list = document.getElementsByClassName("change_color");
	document.getElementById("hr").style.background = color;
	for (var i = 0; i < list.length; i++) {
		list[i].style.color = color;
		list[i].style.borderColor = color;
	}
}
// set color by clicking the containername dropdown



// ALLOW LINEBREAKS IN TEXTAREAS PLACEHOLDERS
var textAreas = document.getElementsByTagName("textarea");
Array.prototype.forEach.call(textAreas, function (elem) {
	elem.placeholder = elem.placeholder.replace(/\\n/g, "\n");
});


// PAGE REFRESH ON DOUBLECLICK
var the_div = document.getElementsByTagName("body")[0];
var clickCount = 0;
the_div.addEventListener(
	"click",
	function () {
		clickCount++;
		if (clickCount === 1) {
			singleClickTimer = setTimeout(function () {
				clickCount = 0;
			}, 400);
		} else if (clickCount === 3) {
			clearTimeout(singleClickTimer);
			clickCount = 0;
			window.location.reload(true);
			console.log({
				the_div
			});
		}
	},
	false
);






function validateForm() {
	var url_input = document.getElementById('url_input');
	var name_input = document.getElementById('name_input');
	var container_del = document.getElementById('container_del');
	var container_input = document.getElementById('container_input');
	var item_del = document.getElementById('item_del');
	// console.log("validateForm:");
	// console.log("container_del.value"+container_del.checked +"");
	// console.log("item_del.value"+item_del.checked +"");
	if (name_input.value.length < 2 && url_input.value !== '') {
		name_input.value = "";
		name_input.placeholder = "bitte eintragen";
		return false;
	} else {
		if (container_del.checked == true) {
			return confirm("Are you sure you want to delete the Container " + container_input.value + " with all items?");
		}
		if (item_del.checked == true) {
			return confirm("Are you sure you want to delete the item: " + name_input.value);
		}
		// return true;
	}
}


// SESSION TIMER
var minutes, seconds, counter, timer;
// count = <?= $session_lenght ?>; //seconds
counter = setInterval(timer, 10000);
function timer() {
	"use strict";
	count = count - 10;
	minutes = Math.floor(count / 60);
	seconds = count - minutes * 60;
	document.getElementById("timer").innerHTML = "Session: " + minutes + ":" + seconds;
	if (count < 0) {
		clearInterval(counter);
		return;
	}
	if (count === 0) {
		document.getElementById('logout').submit();
		// location.reload();
	}
}


// EDIT_JSON_IN_TEXTAREA
function edit_json() {
	// var myJsObj = <?= json_encode($data) ?>;
	var str = JSON.stringify(myJsObj, undefined, 4);
	document.getElementById('edit_json').innerHTML = str;
}
// SEARCH_JSON_IN_TEXTAREA
function search_in_json() {
	var SearchTerm = document.getElementById("searchin_json").value;
	var textArea = document.getElementById("edit_json");
	var lines = textArea.value.split("\n");
	for (var j = 0; j < lines.length; j++) {
		if (SearchTerm.length > 0 && lines[j].indexOf(SearchTerm) > -1) {
			var lineHeight = textArea.clientHeight / textArea.rows;
			var jump = (j - 1) * lineHeight;
			textArea.scrollTop = jump;
		}
	}
}





function RGBToHex(rgb) {
	let sep = rgb.indexOf(",") > -1 ? "," : " ";
	rgb = rgb.substr(4).split(")")[0].split(sep);
	let r = (+rgb[0]).toString(16),
		g = (+rgb[1]).toString(16),
		b = (+rgb[2]).toString(16);
	if (r.length == 1)
		r = "0" + r;
	if (g.length == 1)
		g = "0" + g;
	if (b.length == 1)
		b = "0" + b;
	return "#" + r + g + b;
}


function item_search() {
	document.getElementById('search_results').innerHTML = '';
	var name = document.getElementById("item_search").value;
	var pattern = name.toLowerCase();
	var item = document.getElementsByClassName("item");
	if (pattern.length > 0) {
		var link = '';
		var tooltip = '';
		var item_edit = '';
		// console.table(item);
		for (var i = 0; i < item.length; i++) {
			if (item[i].innerText.toLowerCase().indexOf(pattern) > -1) {
				var color = RGBToHex(window.getComputedStyle(item[i], null).getPropertyValue("color"));
				var link = item[i].innerHTML;
				// console.table(item[i].innerHTML);
				document.getElementById('search_results').innerHTML += ' <div class="item_search" style="color:' + color + '">' + link + '</div>';
				document.getElementById('search_frame').style.display = 'block';
				if (i > 10) { break; }
			}
		}
		if (link == '') { document.getElementById('search_results').innerHTML = 'no results<br>'; }
	}
	else { document.getElementById('search_frame').style.display = 'none'; }
}






				// var links = item[i].getElementsByTagName("span");
				// var link = links[0].innerHTML;
				// var imgs = item[i].getElementsByTagName("img");
				// var img = imgs[0].outerHTML;
				// var tooltips = item[i].getElementsByClassName("tooltip");
				// if (tooltips.length > 0) { var tooltip = tooltips[0].outerHTML; }
				// var item_edits = item[i].getElementsByClassName("item_edit");
				// if (item_edits.length > 0) { var item_edit = item_edits[0].outerHTML; }
				// document.getElementById('search_results').innerHTML += img + ' <span style="color:' + color + '">' + link + tooltip + item_edit + '</span><br>';
				// document.getElementById('search_results').style.display = 'block';












// (function () {
// 	var old = console.log;
// 	var logger = document.getElementById('log');
// 	console.log = function () {
// 		for (var i = 0; i < arguments.length; i++) {
// 			if (typeof arguments[i] == 'object') {
// 				logger.innerHTML += (JSON && JSON.stringify ? JSON.stringify(arguments[i], undefined, 2) : arguments[i]) + '<br />';
// 			} else {
// 				logger.innerHTML += arguments[i] + '<br />';
// 			}
// 		}
// 	}
// })();



// PAGE REFRESH ON REACTIVE THE WINDOW
// console.log("JS-Console:");
// (function () {
//   var hasFocus = document.hasFocus();
//   setInterval(function () {
//     var hasFocusNow = document.hasFocus();
//     if (hasFocus !== hasFocusNow && hasFocus) {
//       console.log("out");
//     } else if (hasFocus !== hasFocusNow) {
//       setTimeout(() => window.location.reload(true), 200);
//       console.log("in2");
//     }
//     hasFocus = hasFocusNow;
//   }, 500);
// })();
// PAGE REFRESH ON REACTIVE THE WINDOW




// RUN EXTERNAL URL WITH POST
// function URL2post(path, params, method = "post") {
// 	const form = document.createElement("form");
// 	form.method = method;
// 	form.action = path;
// 	form.target = "_blank";
// 	for (const key in params) {
// 		if (params.hasOwnProperty(key)) {
// 			const hiddenField = document.createElement("input");
// 			hiddenField.type = "hidden";
// 			hiddenField.name = key;
// 			hiddenField.value = params[key];
// 			form.appendChild(hiddenField);
// 			console.log(hiddenField);
// 		}
// 	}
// 	console.log(form);
// 	document.body.appendChild(form);
// 	form.submit();
// }


// TOGGLE LAYOUT
// function toggle_mansory() {
// 	var element = document.getElementById("mansory_button");
// 	element.classList.toggle("mansory_button");
// 	var element = document.getElementById("mansory");
// 	element.classList.toggle("mansory");
// }