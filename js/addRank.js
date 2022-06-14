var ranknames = sendApiRequest({type: "grn"}, false).data;

/**
 * checks if Rolename Exists
 * @param {bool} modify true if exisitng role is modified; using other Elements
 */
function checkRankName(modify) {
    var toCheck = "";
    if (modify) {
        toCheck = document.getElementById('RankEditName').value;
    } else {
        toCheck = document.getElementById('RankAddName').value;
    }
    if (ranknames.includes(toCheck)) {
        if (modify) {
            document.getElementById('RankEditName').setAttribute("class", "form-control textinput border-danger bg-danger");
            document.getElementById('RankEditName').setAttribute("data-toggle", "tooltip");
            document.getElementById('RankEditName').setAttribute("title", "Rolenname bereits vergeben.");
            document.getElementById('editRankButton').disabled = true;
        } else {
            document.getElementById('RankAddName').setAttribute("class", "form-control textinput border-danger bg-danger");
            document.getElementById('RankAddName').setAttribute("data-toggle", "tooltip");
            document.getElementById('RankAddName').setAttribute("title", "Rolenname bereits vergeben.");
            document.getElementById('addNewRankButton').disabled = true;
        }
    } else {
        if (modify) {
            document.getElementById('RankEditName').setAttribute("class", "form-control textinput");
            document.getElementById('RankEditName').removeAttribute("title");
            document.getElementById('RankEditName').removeAttribute("data-toggle");
            document.getElementById('editRankButton').disabled = false;
        } else {
            document.getElementById('RankAddName').setAttribute("class", "form-control textinput");
            document.getElementById('RankAddName').removeAttribute("title");
            document.getElementById('RankAddName').removeAttribute("data-toggle");
            document.getElementById('addNewRankButton').disabled = false;
        }
    }
}

/**
 * open modal to edit module based ranks
 * @param {int} id Identifier of Rank
 * @param {string} rname name of rank
 */
function openModuleBasedRankName(id, rname) {
    document.getElementById('TitleModuleBasedName').innerHTML = "Modulbasierte Namen für " + rname;
    var apiSelect = sendApiRequest({type: "rns", id: id}, false).payload;
    var selection = "";
    for (var i = 0; i < apiSelect.length; i++) {
        selection += '<option value="' + apiSelect[i].id + '">' + apiSelect[i].name + '</option>';
    }
    document.getElementById('editModuleSelect').innerHTML = selection;
    var names = sendApiRequest({type: "rna", id: id}, false).payload;
    var table = "";
    for (var i = 0; i < names.length; i++) {
        table += '<tr>';
        table += '<td scope="row">' + names[i].modulename + '</td><td scope="row">' + names[i].rankname + '</td>';
        table += '<td>';
        table += '<button onclick="deleteModuleBasedRankName(' + names[i].id + ')" ' +
            'class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top" ' +
            'title="Löschen">' +
            '<img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">' +
            '</button>';
        table += '</td>';
        table += '</tr>';
    }
    document.getElementById('ModuleNameTableBody').innerHTML = table;
    document.getElementById('RankModulID').value = id;
    document.getElementById('RankModulOrgName').value = rname;
    $('#ModuleNameRank').modal();
}

/**
 * saves new module based rank name
 */
function saveModulBasedRankName() {
    var rid = document.getElementById('RankModulID').value;
    var aid = document.getElementById('editModuleSelect').value;
    var name = document.getElementById('RankModulName').value;
    var orgName = document.getElementById('RankModulOrgName').value;
    sendApiRequest({type: "imr", rid: rid, aid: aid, name: name}, false);
    document.getElementById('RankModulName').value =  "";
    openModuleBasedRankName(rid, orgName);
}

/**
 * deletes a certain module based rank name
 * @param id identifier of module based rank name
 */
function deleteModuleBasedRankName(id) {
    var rid = document.getElementById('RankModulID').value;
    var orgName = document.getElementById('RankModulOrgName').value;
    sendApiRequest({type: "dmr", id: id}, false);
    openModuleBasedRankName(rid, orgName);
}