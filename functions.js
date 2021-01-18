// CHANGE COLOR
document.getElementById("color_select").addEventListener("change", changeColor);
function changeColor() {
  var color = document.getElementById("color_select").value;
  var list = document.getElementsByClassName("change_color");
  console.log("color_select " + color);
  console.table("change_color ", { list });
  document.getElementById("hr").style.background = color;
  for (var i = 0; i < list.length; i++) {
    list[i].style.color = color;
    list[i].style.borderColor = color;
  }
}
// CHANGE COLOR

// DROPDOWN CONTAINERNAME TO INPUT TEXT
document
  .getElementById("container_select")
  .addEventListener("change", fill_input);
function fill_input() {
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

// RUN EXTERNAL URL WITH POST
function URL2post(path, params, method = "post") {
  const form = document.createElement("form");
  form.method = method;
  form.action = path;
  form.target = "_blank";
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
      console.log({ the_div });
    }
  },
  false
);
// PAGE REFRESH ON DOUBLECLICK

// PAGE REFRESH ON REACTIVE THE WINDOW
// console.log("JS-Console:");
// (function () {
//   var hasFocus = document.hasFocus();
//   setInterval(function () {
//     var hasFocusNow = document.hasFocus();
//     if (hasFocus !== hasFocusNow && hasFocus) {
//       // $("#output").append("<p>focus lost</p>");
//       console.log("out");
//     } else if (hasFocus !== hasFocusNow) {
//       // setTimeout(window.location.reload(true), 2000);
//       setTimeout(() => window.location.reload(true), 500);
//       console.log("in2");
//       // $("#output").append("<p>focus gained</p>");
//     }
//     hasFocus = hasFocusNow;
//   }, 500);
// })();
// PAGE REFRESH ON REACTIVE THE WINDOW
