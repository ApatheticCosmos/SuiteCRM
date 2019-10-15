/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2019 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
var addrSelectDialog;

function insertAddress(addrString) {
    var locationField = document.getElementById("location");
    locationField.value = addrString.trim();
}

function parseAddresses(addressJson) {
    // Create some HTML from the data
    var record = 0;
    var html = "";
    $.each(addressJson, function(key, value) {
        record++;
        html += '<a id="addr' + record + '" href="#" onclick="insertAddress(\'' + value.trim() + '\'); $(this).closest(\'.ui-dialog-content\').dialog(\'close\');  return false;">' + key.trim() + ' (' + value.trim().substring(0, 10) + '...)</a><br>';
    });
    return html;
}

function getAddressJson() {
    console.log("Running getAddressJson()");
    module_str = document.getElementById("parent_type").value.toString();
    id_str = document.querySelector("input[id='parent_id']").value.toString(); // this uses querySelector because there are elements with dupicate id
    console.log("Module: " + module_str);
    console.log("ID: " + id_str);
    json_url = window.location.pathname + "?entryPoint=getAddressToAutofill&module=" + module_str + "&record_id=" + id_str;
    console.log("json_url: " + json_url);
    json = $.getJSON(json_url);
    console.log("returns: " + json.responseText);

    // We create the modal div here.
    var modalDiv = document.createElement("div");
    modalDiv.id = 'modalDiv';
    //modalDiv.innerHTML = parseAddresses(json.responseJSON);
    if(document.body != null){
        document.body.prepend(modalDiv);
    }
    addrSelectDialog = $("#modalDiv").dialog({
        autoOpen  : true,
        modal     : true,
        title     : "Choose an Address",
        buttons   : {
                  'Cancel' : function() {
                      console.log('Cancel clicked');
                      $(this).dialog('close');
                        }
                    }
    }).attr('id', 'addrSelectDialog');
    modalDiv.innerHTML = parseAddresses(json.responseJSON);
    return addrSelectDialog;
}
var locationField = document.getElementById("location");
curwidth = locationField.offsetWidth;

var span = document.createElement("span");
span.classList.add('id-ff');

var button = document.createElement("button");
button.type = 'button';
button.name = 'btn_insert_address';
button.id = 'btn_insert_address';
button.title = 'Insert from Related Record';
button.classList.add('button');
button.innerHTML = '<span class="suitepicon suitepicon-action-select"></span>';
button.setAttribute("onclick", "var addrSelectDialog=getAddressJson()");
span.appendChild(button);

var locationFieldDiv = locationField.parentNode;
locationFieldDiv.appendChild(span);

newwidth = curwidth - span.offsetWidth;
console.log("curwith: " + curwidth);
console.log("newwith: " + newwidth);
locationField.style.width = newwidth.toString() + "px";