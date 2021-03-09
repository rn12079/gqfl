/*
Ajax block to get items for search tab
*/
function pop_products2() {
  console.log("pop products");
  $(".product2").select2({
    width: "200px",
    placeholder: "select a product",
    ajax: {
      url: "retrieve_rows_jq.php",
      dataType: "json",
      cache: true,
      data: function (params) {
        return {
          q: params.term,
          s: $("#supplier2").val(),
        };
      },

      processResults: function (data) {
        return {
          results: $.map(data, function (obj) {
            console.log(obj);
            return {
              id: obj.id,
              text: obj.text + " || " + obj.hint,
            };
          }),
        };
      },
    },
  });
}

function ajaxsearch(myitem, filter, subfield) {
  //var prod_code = document.getElementById("product1").value;
  var xhr;
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  }

  var data = "retrieve=" + myitem + "&filter=" + filter;
  if (myitem == "product_name") {
    var supplier = document.getElementById("slist").value;
    data = data + "&supplier=" + escape(supplier);
    //alert(data);
  }

  xhr.open("POST", "retrieve_product.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  var update;
  if (myitem == "product_type") update = "tlist";
  if (myitem == "product_name") update = "plist";
  if (myitem == "supplier") update = "slist";
  if (myitem == "product_sub_type") {
    update = "product_sub_type";
    var prod = document.getElementById("product").value;
    if (subfield !== undefined) {
      // check row index from <tr> tag .
      var row = subfield.parentNode.parentNode.rowIndex;
      //alert(subfield.parentNode.parentNode.nodeName);

      var table = document.getElementById("rec_items");
      prod = table.rows[row].cells[1].children[0].value;
    }

    var type = document.getElementById("tlist").value;
    var supplier = document.getElementById("slist").value;
    data =
      "retrieve=" +
      escape(myitem) +
      "&filter=" +
      escape(prod) +
      "&supplier=" +
      escape(supplier) +
      "&type=" +
      escape(type);
  }

  xhr.send(data);
  xhr.onreadystatechange = display_data;

  function display_data() {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        if (myitem == "product_sub_type") subfield.value = xhr.responseText;
        else document.getElementById(update).innerHTML = xhr.responseText;
      } else {
        alert("There was a problem with the request");
      }
    }
  }
  /*end Ajax block*/
}

function ret_inv_items() {
  var supplier = document.getElementById("slist").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = "";

  var myjson = {
    supplier: escape(supplier),
    receiver: escape(receiver),
    invoice_ref: escape(invoice_ref),
    id: escape(id),
  };
  data_params = JSON.stringify(myjson);

  // document.getElementById("stats").innerHTML = "ret_field"+ myjson.ret_field+" ptype: " + myjson.ptype + " pname: " + myjson.pname + " supp: " + myjson.supplier + " stype: " + myjson.stype;

  var xhr;
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xhr.open("POST", "ret_inv_items.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("x=" + data_params);
  xhr.onreadystatechange = display_data;
  function display_data() {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        document.getElementById("rp").innerHTML = xhr.responseText;
      } else {
        alert("There was a problem with the request");
      }
    }
  }
  /*end Ajax block*/
}

function inits() {
  console.log("init initiated");
  pop_products2();
  document.getElementById("count").innerHTML = 1;
}

function validate_form() {
  var t_date = document.getElementById("t_date").value;
  var t_prod = $(product2).val();
  console.log(t_prod);
  return false;
  var t_cases = document.getElementById("num_cases").value;
  var t_amount = document.getElementById("amount").value;
  var t_inv_num = document.getElementById("inv_num").value;

  if (
    t_date == "" ||
    t_prod == "" ||
    t_cases == "" ||
    t_amount == "" ||
    t_inv_num == ""
  ) {
    document.getElementById("show_status").innerHTML = "Some fields are empty";
    return false;
  }
}

function useexisting() {
  if (document.getElementById("existing").checked) {
    document.getElementById("file").disabled = true;
    ret_inv_items("inv_ref");
  } else document.getElementById("file").disabled = false;
}

function ret_inv_item() {
  console.log("ret_inv_items");
  ret_inv_items("all_fields");
}

function ret_inv_items(retfields) {
  var supplier = document.getElementById("supplier2").value;
  var d_date = document.getElementById("t_date").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = "";

  var myjson = {
    retfield: escape(retfields),
    supplier: escape(supplier),
    receiver: escape(receiver),
    invoice_ref: escape(invoice_ref),
    id: escape(id),
    d_date: escape(d_date),
  };
  data_params = JSON.stringify(myjson);

  // document.getElementById("stats").innerHTML = "ret_field"+ myjson.ret_field+" ptype: " + myjson.ptype + " pname: " + myjson.pname + " supp: " + myjson.supplier + " stype: " + myjson.stype;

  var xhr;
  if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  }

  if (retfields == "all_fields") update = "imglist";
  console.log("data_params = " + data_params);

  xhr.open("POST", "ret_inv_items.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("x=" + data_params);
  xhr.onreadystatechange = display_data;
  function display_data() {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        console.log("ajax response = " + xhr.responseText);

        document.getElementById(update).innerHTML = xhr.responseText;
      } else {
        alert("There was a problem with the request");
      }
    }
  }
  /*end Ajax block*/
}

function newrow() {
  var table = document.getElementById("rec_items");
  var rowcount = table.rows.length;

  //alert(rowcount);

  var row = table.insertRow(rowcount);
  var colcount = table.rows[0].cells.length;

  //alert(colcount);

  for (var i = 0; i < colcount; i++) {
    var newcell = row.insertCell(i);
    if (i === 0) newcell.innerHTML = rowcount;
    else if (i === 1)
      newcell.innerHTML =
        "<select name='product2[]' class='product2'></select>";
    else if (i === 10)
      newcell.innerHTML =
        "<span class='glyphicon glyphicon-remove text-danger' onclick='remove_row(this)'></span>";
    else if (i === 4)
      newcell.innerHTML =
        "<input type='checkbox' name='tad[" +
        (rowcount - 1) +
        "]' id='tad[" +
        (rowcount - 1) +
        "]'>";
    else newcell.innerHTML = table.rows[1].cells[i].innerHTML;
  }
  pop_products2();
  compute_totals();
}

function remove_row(field) {
  var x = field.parentNode.parentNode.remove();
  compute_totals();
}

function compute_val(t_field) {
  var row = t_field.parentNode.parentNode.rowIndex;
  var res;
  var fld = $(t_field).attr("name");
  console.log(row);

  var table = document.getElementById("rec_items");

  var chk = table.rows[row].cells[4].children[0].checked;
  var nam = table.rows[row].cells[3].children[0].value;
  var disc = table.rows[row].cells[5].children[0].value;
  var tr = table.rows[row].cells[6].children[0].value;
  var tam = table.rows[row].cells[7].children[0].value;

  if (fld == "tax[]") {
    if (chk == true) res = parseInt((nam - disc) * tr);
    else res = parseInt(nam * tr);
  } else {
    res = parseInt(nam - disc + parseInt(tam));
  }

  console.log(res);

  t_field.value = res;
}

function compute_rows() {
  console.log("focus out");
  var qty = document.getElementsByName("qty[]");
  var nam = document.getElementsByName("namount[]");
  var disc = document.getElementsByName("discount[]");
  var tax = document.getElementsByName("taxrate[]");
  var tam = document.getElementsByName("tax[]");
  var am = document.getElementsByName("amount[]");
  var tad = document.getElementsByName("tad[]");
  var up = document.getElementsByName("unitprice[]");

  console.log(nam.length);
  //console.log(nam[0].value);

  for (i = 0; i < nam.length; i++) {
    var d = "tad[" + i + "]";
    console.log(d);
    if (document.getElementById(d).checked == false)
      tam[i].value =
        Math.round(parseFloat(nam[i].value * tax[i].value) * 100) / 100;
    else
      tam[i].value =
        Math.round(
          parseFloat((nam[i].value - disc[i].value) * tax[i].value) * 100
        ) / 100;

    am[i].value =
      Math.round(
        (parseFloat(nam[i].value - disc[i].value) + parseFloat(tam[i].value)) *
          100
      ) / 100;
    up[i].value =
      Math.round(
        (parseFloat(nam[i].value - disc[i].value) / parseFloat(qty[i].value)) *
          100
      ) / 100;
  }

  compute_totals();
}

function compute_totals() {
  var nam = document.getElementsByName("namount[]");
  var disc = document.getElementsByName("discount[]");
  var tax = document.getElementsByName("tax[]");
  var am = document.getElementsByName("amount[]");

  var nettotal = 0;
  var netdisc = 0;
  var nettax = 0;
  var invtotal = 0;

  for (i = 0; i < nam.length; i++) {
    nettotal = parseInt(nettotal) + parseInt(nam[i].value || 0);
    netdisc = parseInt(netdisc) + parseInt(disc[i].value || 0);
    nettax = parseInt(nettax) + parseInt(tax[i].value || 0);
    invtotal = parseInt(invtotal) + parseInt(am[i].value || 0);
  }

  document.getElementById("sub_total").value = nettotal;
  document.getElementById("sub_total_disc").value = netdisc;
  document.getElementById("sub_total_tax").value = nettax;
  document.getElementById("inv_total").value = invtotal;
}
