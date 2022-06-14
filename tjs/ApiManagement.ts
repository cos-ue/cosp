$(document).ready(function () {
    if(testCookie('newapitoken')){
        document.getElementById('CreateFinishApiModalBody').innerHTML = "Das Modul wurde erfolgreich angelegt. <br /> Der Authentifizierungstoken lautet: " + getCookie('newapitoken');
        $('#CreateFinishApiModal').modal();
        deleteCookie('newapitoken');
    }
});

/**
 * opens and fills in data to edit api
 * @param {int} id identifier of api
 */
function openEditApiModal(id) {
    var json = {
        type: "gap",
        id: id
    };
    var api = sendApiRequest(json, false).payload;
    (<HTMLInputElement>document.getElementById('ApiEditName')).value = api.name;
    (<HTMLInputElement>document.getElementById('ApiEditUrl')).value = api.url;
    (<HTMLInputElement>document.getElementById('ApiEditID')).value = id;
    $('#EditApiModal').modal();
}

/**
 * saves edited api data
 */
function saveEditedApi(){
    var name = (<HTMLInputElement>document.getElementById('ApiEditName')).value;
    var uri = (<HTMLInputElement>document.getElementById('ApiEditUrl')).value;
    var id = (<HTMLInputElement>document.getElementById('ApiEditID')).value;
    var json = {
        type: "sap",
        name: name,
        url: uri,
        id: id
    };
    sendApiRequest(json, true);
}

/**
 * saves new api data and creates new module
 */
function CreateNewApi(){
    var Name = (<HTMLInputElement>document.getElementById('ApiCreateName')).value;
    var url = (<HTMLInputElement>document.getElementById('ApiCreateUrl')).value;
    var json = {
        type: 'cna',
        name: Name,
        url: url
    };
    var result = sendApiRequest(json, false).payload;
    setCookie('newapitoken', result, 3);
    window.location.reload();
}