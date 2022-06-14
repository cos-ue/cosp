/**
 * opens modules list for users permissions
 * @param {int} id identifier of user
 * @param {int} id identifier of user
 */
function OpenModuleRightsAdd(id: number): void {
    var json = {
        type: "gmr",
        id: id
    };
    var result = sendApiRequest(json, false).payload;
    var table = "";
    var apis: Array<string> = [];
    if (result.length > 0) {
        for (var i = 0; i < result.length; i++) {
            var enabled = 'Nein';
            if (result[i].enabled) {
                enabled = 'Ja';
            }
            table += '<tr>';
            table += '<td>' + result[i].api + '</td>';
            table += '<td>' + result[i].name + '</td>';
            table += '<td>' + enabled + '</td>';
            table += '<td>';
            table += '<button onclick="deleteModuleBasedRoleUserMgmt(' + result[i].id + ',' + id + ')" ' +
                'class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top" ' +
                'title="Löschen">' +
                '<img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">' +
                '</button>';
            table += '</td>';
            table += '</tr>';
            apis.push(result[i].apiid)
        }
    }
    document.getElementById('ModuleRightTableBody').innerHTML = table;
    var moduleOptions = "";
    var json2 = {
        type: "gam",
    }
    var modules = sendApiRequest(json2, false).payload;
    for (var j = 0; j < modules.length; j++) {
        if (apis.indexOf(modules[j].id) < 0) {
            moduleOptions += '<option value="' + modules[j].id + '">' + modules[j].name + '</option>';
        }
    }
    document.getElementById('ModuleRightsAddSelect').innerHTML = moduleOptions;
    var rolesOptions = "";
    var json3 = {
        type: 'sar',
    }
    var roles = sendApiRequest(json3, false).payload;
    for (var k = 0; k < roles.length; k++) {
        rolesOptions += '<option value="' + roles[k].id + '">' + roles[k].name + '</option>';
    }
    document.getElementById('ModuleRolesAddSelect').innerHTML = rolesOptions;
    (document.getElementById('UserIdModuleRoleAdd') as HTMLInputElement).value = id.toString();
    $('#AddUserAuthToModuleModal').modal();
}

/**
 * saves new module based role of user
 */
function saveModuleRoleUsermgmt(): void {
    var moduleId = document.getElementById('ModuleRightsAddSelect') as HTMLInputElement;
    var roleId = document.getElementById('ModuleRolesAddSelect') as HTMLInputElement;
    var userId = document.getElementById('UserIdModuleRoleAdd') as HTMLInputElement;
    var json = {
        type: "smr",
        module: moduleId.value,
        role: roleId.value,
        user: userId.value,
    }
    sendApiRequest(json, false);
    OpenModuleRightsAdd(parseInt(userId.value))
}

/**
 * delete module based rights
 * @param {int} rid identifier of modulebased role
 * @param {int} uid identifier of user
 */
function deleteModuleBasedRoleUserMgmt(rid: number, uid : number) {
    deleteModuleBasedRole(rid);
    OpenModuleRightsAdd(uid);
}

/**
 * updates mailvalidationstate
 * @param {int} uname string identifier of user (nickname)
 * @param {boolean} value state of mailvalidation
 */
function setMailValidation(uname: string, value: boolean): void {
    var json = {
        name: uname,
        state: value,
        type: 'smv',
    }
    sendApiRequest(json,true);
}