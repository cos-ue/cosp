/**
 * Set role of User
 *
 * @param {int} roleId id of role to assign
 * @param {string} roleName name of role to asign, changes caption of Button
 * @param {int} userId ID of User which role should change
 * @param {string} userName name of user whose role should be changed
 **/
function changeRole(roleId, roleName, userId, userName) {
    var json = {
        type: "cur",
        role: roleId,
        username: userName
    };
    var result = sendApiRequest(json, false);
    if (result.success == false) {
        location.reload();
    }

    document.getElementById("dropdownMenuButton" + userId).innerHTML = roleName;
}