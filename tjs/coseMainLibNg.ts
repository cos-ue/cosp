/**
 * deletes module based rights
 * @param {int} rightId identifier of right
 */
function deleteModuleBasedRole(rightId:number) {
    if (confirm('Modulbasiertes Recht wirklich löschen?') == false) {
        return;
    }
    var json = {
        type: 'drm',
        id: rightId
    }
    sendApiRequest(json, false);
}