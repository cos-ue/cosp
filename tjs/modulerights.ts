declare var currentlyHidden: number;
declare var currentlySelectedName: string;
var MassAddUsersTable: Array<any>;

/**
 * cahnges displayed table body for module based rights table
 * @param {int} apiid identifier of module
 * @param {string} name name of module
 */
function changeModuleRightTableBody(apiid: number, name: string): void {
    document.getElementById('usertableRights' + currentlyHidden).hidden = true;
    document.getElementById('usertableRights' + apiid).hidden = false;
    document.getElementById('dropdownMenuButtonModuleRights').innerHTML = name;
    currentlyHidden = apiid;
}

/**
 * updates a certain role
 * @param {int} rightId identifier of right
 * @param {int} roleId identifier of role
 */
function updateModulright(rightId: number, roleId: number): void {
    var json = {
        type: 'umr',
        roleid: roleId,
        rightid: rightId,
    }
    var result = sendApiRequest(json, false);
    if (result.success) {
        window.location.href = result.payload;
    }
}

/**
 * sets the state of disable property for user on this module
 * @param {int} rightID identifier of right
 * @param {boolean} state state of disable property
 */
function SetDisableStateUserForModule(rightID: number, state: boolean): void {
    var json = {
        state: state,
        rightid: rightID,
        type: 'dsr'
    }
    var result = sendApiRequest(json, false);
    if (result.success) {
        window.location.href = result.payload;
    }
}

/**
 * deletes a module based right
 * @param {int} rightID
 */
function deleteModuleBasedRightMgmt(rightID: number) {
    deleteModuleBasedRole(rightID);
    window.location.href = "moduleRights.php?" + encodeURIComponent('aid') + "=" + encodeURIComponent(currentlyHidden);
}

/**
 * opens Module to mass add users with a certain role to a module
 */
function OpenMassAddRightsToModule() {
    var ApiName = document.getElementById('ApiNameModalRightsMassAdd') as HTMLInputElement;
    ApiName.value = currentlySelectedName;
    var json = {
        type: "gum",
        module: currentlyHidden
    };
    var users = sendApiRequest(json, false).payload;
    MassAddUsersTable = users;
    var table = "";
    for (var i = 0; i < users.length; i++) {
        table += '<tr class="" id="MassAddUser' + i + 'Row" onclick="SelectMassAddRoleUserRow(' + i + ')"><td class="pl-1">';
        table += '<input type="checkbox" id="MassAddUser' + i + 'Checkbox" class="form-check-input ml-2" onclick="SelectMassAddRoleUserRow(' + i + ')">';
        table += '</td><td>';
        table += users[i].id;
        table += '</td><td>';
        table += users[i].name;
        table += '</td><td>';
        table += users[i].firstname;
        table += '</td><td>';
        table += users[i].lastname;
        table += '</td></tr>';
    }
    document.getElementById('MassAddModulRightsUsersSelect').innerHTML = table;
    $('#ModuleRightsMassAddModal').modal();
}

/**
 * marks a row as selected
 * @param {int} RowCounter Identifier of selected row
 */
function SelectMassAddRoleUserRow(RowCounter: number) {
    var row = document.getElementById('MassAddUser' + RowCounter + 'Row');
    var checkbox = document.getElementById('MassAddUser' + RowCounter + 'Checkbox') as HTMLInputElement;
    var checked = checkbox.checked;
    if (checked) {
        row.setAttribute('class', '');
        checkbox.checked = false;
    } else {
        row.setAttribute('class', 'table-row-dark-selected');
        checkbox.checked = true;
    }
}

/**
 * saves all added users the selected module rights
 */
function saveAddMassUsers() {
    var module = currentlyHidden;
    var roledropdown = document.getElementById('MassAddModuleRightsRoleSelect') as HTMLInputElement;
    var role = roledropdown.value;
    for (var i = 0; i < MassAddUsersTable.length; i++) {
        var checkbox = document.getElementById('MassAddUser' + i + 'Checkbox') as HTMLInputElement;
        if (checkbox.checked) {
            var userid = MassAddUsersTable[i].id;
            var json = {
                type: "smr",
                module: module,
                role: role,
                user: userid,
            };
            sendApiRequest(json, false);
        }
    }
    window.location.reload();
}

