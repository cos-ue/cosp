// make nice tooltips visible
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

/**
 * Adds new role and sends api-request to mapi.php
 */
function AddNewRole() {
    var name = document.getElementById('RoleAddName').value;
    var rvalue = document.getElementById('RoleAddValue').value;
    var json = {
        type: "anr",
        name: name,
        value: rvalue,
    };
    sendApiRequest(json, true);
}

/**
 * sends API-Request to mapi
 * @param {json} json data to transmit
 * @param {boolean} reload enables or disables page reload
 * @returns {array} is in json form and is already parsed
 */
function sendApiRequest(json, reload) {
    var csrfToken = document.getElementById('TokenScriptCSRF').value;
    json.csrf = csrfToken;
    reload = reload || false;
    var otherReq = new XMLHttpRequest();
    otherReq.open("POST", "mapi.php", false);
    otherReq.withCredentials = true;
    otherReq.setRequestHeader("Content-Type", "application/json");
    otherReq.send(JSON.stringify(json));
    var resp = otherReq.responseText;
    var result = JSON.parse(resp);

    if (result.code > 0) {
        throw new Error("Something went badly wrong!");
    }
    if (reload) {
        location.reload();
    }
    return result;
}

/**
 * opens modal to edit roles
 * @param {string} name name of role
 * @param {int} value role worth
 * @param {int} id id of role, not changeable
 */
function openEditRoleModal(name, value, id) {
    document.getElementById('RoleEditID').value = id;
    document.getElementById('RoleEditValue').value = value;
    document.getElementById('RoleEditName').value = name;
    $('#EditRole').modal();
}

/**
 * saves changed stuff of editRole Modal
 */
function saveEditRoleModal() {
    var id = document.getElementById('RoleEditID').value;
    var val = document.getElementById('RoleEditValue').value;
    var name = document.getElementById('RoleEditName').value;
    var json = {
        type: "eer",
        name: name,
        value: val,
        id: id,
    };
    $('#EditRole').modal('hide');
    sendApiRequest(json, true);
}

/**
 * deletes a role with an api-request
 * @param {int} id id of role which is going to be deleted
 */
function deleteRole(id) {
    var json = {
        type: "der",
        id: id,
    };
    sendApiRequest(json, true);
}

/**
 * fills in already known data and opens modal
 * @param {string} username of user whose password will be changed
 * @param {int} id id  of user whose password will be changed
 */
function resetPasswordModalShow(username, id) {
    document.getElementById('ChgPwdUsername').value = username;
    document.getElementById('ChgPwdUserID').value = id;
    document.getElementById('ChgPwdFd1').value = "";
    document.getElementById('ChgPwdFd2').value = "";
    $('#PasswordChange').modal();
}

/**
 * saves password to cosp database via mapi
 */
function resetPasswordModalSave() {
    var id = document.getElementById('ChgPwdUserID').value;
    var pwd1 = document.getElementById('ChgPwdFd1').value;
    var pwd2 = document.getElementById('ChgPwdFd2').value;
    var json = {
        type: "cup",
        id: id,
        pwd1: pwd1,
        pwd2: pwd2
    };
    sendApiRequest(json, true);
    $('#PasswordChange').modal('hide');
}

/**
 * enables or disables user via mapi
 * @param {int} id userid of user which should be disabled or enabled
 */
function enableDisableUser(id) {
    var json = {
        type: "teu",
        id: id,
    };
    sendApiRequest(json, true);
}

/**
 * request to send a password reset mail to given user
 * @param {int} id userid of user which should get the password reset mail
 */
function sendPasswordResetMail(id) {
    var json = {
        type: "rup",
        id: id,
    };
    sendApiRequest(json, false);
}

/**
 * Adds new rank and sends api-request to mapi.php
 */
function AddNewRank() {
    var name = document.getElementById('RankAddName').value;
    var rvalue = document.getElementById('RankAddValue').value;
    var json = {
        type: "adr",
        name: name,
        value: rvalue,
    };
    sendApiRequest(json, true);
}

/**
 * deletes a rank with an api-request
 * @param {int} id id of rank which is going to be deleted
 */
function deleteRank(id) {
    var json = {
        type: "dra",
        id: id,
    };
    sendApiRequest(json, true);
}

/**
 * opens modal to edit rank
 * @param {string} name name of rank
 * @param {int} value rank worth
 * @param {int} id id of rank, not changeable
 */
function openEditRankModal(name, value, id) {
    document.getElementById('RankEditID').value = id;
    document.getElementById('RankEditValue').value = value;
    document.getElementById('RankEditName').value = name;
    $('#EditRank').modal();
}

/**
 * saves changed stuff of edit rank Modal
 */
function saveEditRankModal() {
    var id = document.getElementById('RankEditID').value;
    var val = document.getElementById('RankEditValue').value;
    var name = document.getElementById('RankEditName').value;
    var json = {
        type: "era",
        name: name,
        value: val,
        id: id,
    };
    $('#EditRank').modal('hide');
    sendApiRequest(json, true);
}

/**
 * loads a captcha code
 */
function loadCaptchaContact() {
    var json = {
        type: "cpa"
    };
    var image = sendApiRequest(json, false).data;
    document.getElementById('Captcha').src = image;
}

/**
 * sends a user defined contact message to the api
 */
function submitContact() {
    var captcha = document.getElementById('captchaReturn').value;
    var title = document.getElementById('ContactTitle').value;
    var message = document.getElementById('ContactMessage').value;
    var json = {
        type: "cmg",
        cap: captcha,
        title: title,
        msg: message
    };
    var result = sendApiRequest(json, false);
    if (result.payload.cap){
        var cssclass = document.getElementById('captchaReturn').getAttribute("class");
        document.getElementById('captchaReturn').setAttribute("class", cssclass + " border-danger bg-danger");
        document.getElementById('captchaReturn').setAttribute('data-toggle', 'tooltip');
        document.getElementById('captchaReturn').setAttribute('data-placement', 'top');
        document.getElementById('captchaReturn').setAttribute('title', 'Captcha ist falsch');
    } else {
        document.getElementById('captchaReturn').setAttribute("class", "form-control textinput float-left ml-2");
        document.getElementById('captchaReturn').removeAttribute('data-toggle');
        document.getElementById('captchaReturn').removeAttribute('data-placement');
        document.getElementById('captchaReturn').removeAttribute('title');
    }
    setErrorOnInputContact("ContactTitle", result.payload.title, "Titel bitte ausfüllen");
    setErrorOnInputContact("ContactMessage", result.payload.msg, "Nachrichteninhalt bitte ausfüllen");
    if (result.success) {
        document.getElementById('contactSuccessMessage').removeAttribute("class");
        var CssClassMain = "form-control textinput border-success";
        var CssClassCap = "form-control textinput float-left ml-2 border-success";
        document.getElementById('ContactTitle').setAttribute("class", CssClassMain);
        document.getElementById('ContactMessage').setAttribute("class", CssClassMain);
        document.getElementById('captchaReturn').setAttribute("class", CssClassCap);
        document.getElementById('captchaReturn').value = "";
        document.getElementById('ContactTitle').value = "";
        document.getElementById('ContactMessage').value = "";
        loadCaptchaContact();
    }
}

/**
 *
 * @param {string} elementid identifier of html-element
 * @param {boolean} state state of error
 * @param {string} tootip text of tooltip
 */
function setErrorOnInputContact(elementid, state, tootip){
    if(state) {
        var cssclass = document.getElementById(elementid).getAttribute("class");
        document.getElementById(elementid).setAttribute("class", cssclass + " border-danger bg-danger");
        document.getElementById(elementid).setAttribute('data-toggle', 'tooltip');
        document.getElementById(elementid).setAttribute('data-placement', 'top');
        document.getElementById(elementid).setAttribute('title', tootip);
    } else {
        document.getElementById(elementid).setAttribute("class", "form-control textinput");
        document.getElementById(elementid).removeAttribute('data-toggle');
        document.getElementById(elementid).removeAttribute('data-placement');
        document.getElementById(elementid).removeAttribute('title');
    }
}

/**
 * gets the value of a cookie
 * @param {string} name name of the cookie
 * @return {string} value of the cookie
 */
function getCookie(name) {
    var cookies = document.cookie;
    var cookies_ar = cookies.split(";");
    for (var i = 0; i < cookies_ar.length; i++) {
        if (cookies_ar[i].startsWith(" ")) {
            cookies_ar[i] = cookies_ar[i].substring(1);
        }
        if (cookies_ar[i].startsWith(name)) {
            var index = cookies_ar[i].indexOf("=")
            return cookies_ar[i].substring(index + 1);
        }
    }
}

/**
 * checks if a certain cookie is set
 * @param {string} name name of cookie to check
 * @return {boolean} true if cookie is availabel
 */
function testCookie(name) {
    return document.cookie.includes(name);
}

/**
 * sets a cookie
 * @param {string} name name of cookie
 * @param {int|string} value data of cookie
 * @param {int} exdays days till expiration
 */
function setCookie(name, value, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

/**
 * deletes a cookie
 * @param {string} name name of cookie
 */
function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}