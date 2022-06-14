var rolenames = sendApiRequest({type: "gar"}, false).data;

/**
 * checks if Rolename Exists
 * @param {bool} modify true if exisitng role is modified; using other Elements
 */
function checkRoleName(modify){
    var toCheck = "";
    if (modify){
        toCheck = document.getElementById('RoleEditName').value;
    } else {
        toCheck = document.getElementById('RoleAddName').value;
    }
    if (rolenames.includes(toCheck)){
        if (modify) {
            document.getElementById('RoleEditName').setAttribute("class", "form-control textinput border-danger bg-danger");
            document.getElementById('RoleEditName').setAttribute("data-toggle", "tooltip");
            document.getElementById('RoleEditName').setAttribute("title", "Rolenname bereits vergeben.");
            document.getElementById('editRoleButton').disabled = true;
        } else {
            document.getElementById('RoleAddName').setAttribute("class", "form-control textinput border-danger bg-danger");
            document.getElementById('RoleAddName').setAttribute("data-toggle", "tooltip");
            document.getElementById('RoleAddName').setAttribute("title", "Rolenname bereits vergeben.");
            document.getElementById('addNewRoleButton').disabled = true;
        }
    } else {
        if (modify) {
            document.getElementById('RoleEditName').setAttribute("class", "form-control textinput");
            document.getElementById('RoleEditName').removeAttribute("title");
            document.getElementById('RoleEditName').removeAttribute("data-toggle");
            document.getElementById('editRoleButton').disabled = false;
        } else {
            document.getElementById('RoleAddName').setAttribute("class", "form-control textinput");
            document.getElementById('RoleAddName').removeAttribute("title");
            document.getElementById('RoleAddName').removeAttribute("data-toggle");
            document.getElementById('addNewRoleButton').disabled = false;
        }
    }
}