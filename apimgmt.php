<?php
/**
 * Page to manage all modules
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
checkLoginDeny($LOGIN);
checkPermission(config::$ROLE_ADMIN);
$ApiKey = getAllApiTokens();
dump($ApiKey, 8);
$redirect = false;
if (count($_GET) > 0) {
    if (isset($_GET['token'], $_GET['type']) === false) {
        die("wrong keys in array");
    }
    dump($_GET, 1);
    $GetData = array(
        "type" => filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING),
        "token" => filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING)
    );
    dump($GetData, 3);
    if (checkApiTokenExists($GetData['token'])) {
        if ($GetData['type'] == 'del') {
            deleteApiToken($GetData['token']);
            Redirect('apimgmt.php');
        }
    }

}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
        <?php
        echo config::$MAIN_CAPTION . " - " . config::$TAGLINE_CAPTION;
        ?>
    </title>
    <?php
    generateHeaderTags(
        array(
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "tjs/ApiManagement.js",
                "hrefmin" => "tjs/ApiManagement.min.js",
            )
        ));
    ?>
</head>

<body style="height: auto">
<?php
generateHeader($LOGIN);
?>
<div class="container mx-auto text-light pt-3">
    <div class="justify-content-center">
        <h1>API-Verwaltung</h1>
        <table class="table table-dark">
            <tr>
                <th scope="col">
                    ID
                </th>
                <th scope="col">
                    Token
                </th>
                <th scope="col">
                    Name
                </th>

                <th scope="col">
                    Reverse-API
                </th>
                <th scope="col">
                    <button onclick="$('#CreateApiModal').modal();"
                            class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                            title="Berechtigungen Hinzufügen">
                        <img src="images/plus-solid.svg" width="15px" style="margin-top: -2px">
                    </button>
                </th>
            </tr>
            <?php
            foreach ($ApiKey as $API) {
                ?>
                <tr>
                    <th scope="row" class="align-middle">
                        <?php
                        echo $API["id"];
                        ?>
                    </th>
                    <td class="align-middle">
                        <?php
                        echo $API["token"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $API["name"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $API["apiuri"];
                        ?>
                    </td>
                    <td>
                        <button onclick="if (confirm('API wirklich löschen?')){location.href='apimgmt.php?<?php echo http_build_query(array('type' => "del", "token" => $API["token"]), '', '&amp;'); ?>';}"
                                class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                                title="Löschen">
                            <img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                        <button onclick="openEditApiModal(<?php echo $API["id"]; ?>);"
                                class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                title="Bearbeiten">
                            <img src="images/pencil-alt-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<div class="modal fade" id="EditApiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">API bearbeiten</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <label class="weiß2">Name</label>
                    <input class="form-control textinput" name="ApiEditName" id="ApiEditName"
                           onkeyup="" required>
                    <label class="weiß2 mt-2">Reverse-API</label>
                    <input class="form-control textinput" name="ApiEditUrl" id="ApiEditUrl"
                           onkeyup="" required>
                    <input class="hidden disabled form-control textinput" name="ApiEditID" id="ApiEditID">
                    <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button"
                           id="editApiButton" value="Speichern" onclick="saveEditedApi();">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CreateApiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">API anlegen</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <label class="weiß2">Name</label>
                    <input class="form-control textinput" name="ApiCreateName" id="ApiCreateName"
                           onkeyup="" required>
                    <label class="weiß2 mt-2">Reverse-API</label>
                    <input class="form-control textinput" name="ApiCreateUrl" id="ApiCreateUrl"
                           onkeyup="" required>
                    <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button"
                           id="CreateApiButton" value="Speichern" onclick="CreateNewApi();">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="CreateFinishApiModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-inline-flex align-items-baseline">
                <h5 class="modal-title" style="color: #ffffff" id="AddPOI">API anlegen</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" onclick="window.location.reload()">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body weiß2" id="CreateFinishApiModalBody" style="overflow: auto;">

            </div>
        </div>
    </div>
</div>
</body>
</html>