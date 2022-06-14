<?php
/**
 * Page to manage ranks
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
$ranks = getRanks();
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
                "href" => "js/addRank.js",
                "hrefmin" => "js/addRank.min.js",
            )
        )
    );
    ?>
</head>

<body style="height: auto">
<?php
generateHeader($LOGIN);
?>
<div class="container mx-auto text-light pt-3">
    <div class="justify-content-center">
        <h1>Rangverwaltung</h1>
        <table class="table table-dark">
            <tr>
                <th scope="col">
                    ID
                </th>
                <th scope="col">
                    Wert
                </th>
                <th scope="col">
                    Name
                </th>
                <th scope="col">
                    <button onclick="$('#AddRank').modal();"
                            class="btn btn-secondary btn-sq offset-6" data-toggle="tooltip" data-placement="top"
                            title="Rang anlegen">
                        <img src="images/plus-solid.svg" width="15px" style="margin-top: -2px">
                    </button>
                </th>
            </tr>
            <?php
            dump($ranks, 3);
            $sortedRoles = array();
            foreach ($ranks as $rank) {
                ?>
                <tr>
                    <th scope="row" class="align-middle">
                        <?php
                        echo $rank["id"];
                        ?>
                    </th>
                    <td scope="row" class="align-middle">
                        <?php
                        echo $rank["value"];
                        ?>
                    </td>
                    <td scope="row" class="align-middle">
                        <?php
                        echo $rank["name"];
                        $sortedRoles[$rank["id"]] = array(
                            "value" => $rank["value"],
                            "name" => $rank["name"]
                        );
                        ?>
                    </td>
                    <td scope="row">
                        <button onclick="if(confirm('Rang wirklich löschen?')){deleteRank('<?php echo $rank["id"]; ?>')}"
                                class="btn btn-secondary btn-sq offset-6" data-toggle="tooltip" data-placement="top"
                                title="Löschen">
                            <img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                        <button onclick="openEditRankModal('<?php echo $rank["name"]; ?>', '<?php echo $rank["value"]; ?>' , '<?php echo $rank["id"]; ?>');"
                                class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                title="Bearbeiten">
                            <img src="images/pencil-alt-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                        <button onclick="openModuleBasedRankName(<?php echo $rank["id"]; ?>, '<?php echo $rank["name"]; ?>' )"
                                class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                title="Modulbasierte Namen">
                            <img src="images/link-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
</div>
<div class="modal fade" id="AddRank" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Rang hinzufügen</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form action="roleMgmt.php" method="post" name="addrole">
                        <label class="weiß2">Name</label>
                        <input class="form-control textinput" name="RankAddName" id="RankAddName"
                               onkeyup="checkRankName(false)" required>
                        <label class="weiß2 mt-3">Wert <img src="images/info-circle-solid-white.svg" width="18px"
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom" data-trigger="click"
                                                            title="Dieser Wert muss durch Validierungen auf durch Nutzer hochgeladene Daten erreicht werden. Der Nutzer kann mit diesem Wert validieren."></label>
                        <input class="form-control textinput" type="number" name="RankAddValue" id="RankAddValue"
                               required>
                        <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button"
                               id="addNewRankButton" value="Anlegen" onclick="AddNewRank();">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="EditRank" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Rang bearbeiten</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form action="roleMgmt.php" method="post" name="addrole">
                        <label class="weiß2">Name</label>
                        <input class="form-control textinput" name="RankEditName" id="RankEditName"
                               onkeyup="checkRankName(true)" required>
                        <label class="weiß2 mt-3">Wert <img src="images/info-circle-solid-white.svg" width="18px"
                                                            data-toggle="tooltip" data-html="true"
                                                            data-placement="bottom" data-trigger="click"
                                                            title="Dieser Wert muss durch Validierungen auf durch Nutzer hochgeladene Daten erreicht werden. Der Nutzer kann mit diesem Wert validieren."></label>
                        <input class="form-control textinput" type="number" name="RankEditValue" id="RankEditValue"
                               required>
                        <input class="hidden disabled form-control textinput" name="RankEditID" id="RankEditID">
                        <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button"
                               id="editRankButton" value="Speichern" onclick="saveEditRankModal()">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModuleNameRank" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white" id="TitleModuleBasedName"></h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <table class="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col">
                                Modulname
                            </th>
                            <th scope="col">
                                Rangname
                            </th>
                            <th scope="col">

                            </th>
                        </tr>
                        </thead>
                        <tbody id="ModuleNameTableBody">

                        </tbody>
                        <tfoot>
                            <td scope="row">
                                <select name="editModuleSelect" id="editModuleSelect" class="form-control dropdown-list">

                                </select>
                            </td>
                            <td scope="row">
                                <input class="form-control textinput" name="RankModulName" id="RankModulName">
                                <input class="hidden" name="RankModulID" id="RankModulID">
                                <input class="hidden" name="RankModulOrgName" id="RankModulOrgName">
                            </td>
                            <td scope="row">
                                <button onclick="saveModulBasedRankName();"
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="speichern">
                                    <img src="images/save-solid-white.svg" width="15px" style="margin-top: -2px">
                                </button>
                            </td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>