<?php
/**
 * Page to manage roles
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
$Roles = getAllRolles();
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
                "href" => "js/addRole.js",
                "hrefmin" => "js/addRole.min.js"
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
        <h1>Rollenverwaltung</h1>
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
                    <button onclick="$('#AddRole').modal();"
                            class="btn btn-secondary btn-sq offset-6" data-toggle="tooltip" data-placement="top"
                            title="Neu">
                        <img src="images/plus-solid.svg" width="15px" style="margin-top: -2px">
                    </button>
                </th>
            </tr>
            <?php
            dump($Roles, 3);
            $sortedRoles = array();
            foreach ($Roles as $Role) {
                ?>
                <tr>
                    <th scope="row" class="align-middle">
                        <?php
                        echo $Role["id"];
                        ?>
                    </th>
                    <td class="align-middle">
                        <?php
                        echo $Role["value"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $Role["name"];
                        $sortedRoles[$Role["id"]] = array(
                            "value" => $Role["value"],
                            "name" => $Role["name"]
                        );
                        ?>
                    </td>
                    <td>
                        <button onclick="if(confirm('Rolle wirklich löschen?')){deleteRole('<?php echo $Role["id"]; ?>')}"
                                class="btn btn-secondary btn-sq offset-6" data-toggle="tooltip" data-placement="top"
                                title="Löschen">
                            <img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">
                        </button>
                        <button onclick="openEditRoleModal('<?php echo $Role["name"]; ?>', '<?php echo $Role["value"]; ?>' , '<?php echo $Role["id"]; ?>');"
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
</div>
<div class="modal fade" id="AddRole" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Rolle hinzufügen</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form action="roleMgmt.php" method="post" name="addrole">
                        <label class="weiß2">Name</label>
                        <input class="form-control textinput" name="RoleAddName" id="RoleAddName" onkeyup="checkRoleName(false)" required>
                        <label class="weiß2 mt-3">Rollenwert</label>
                        <input class="form-control textinput" type="number" name="RoleAddValue" id="RoleAddValue" required>
                        <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button" value="Anlegen" id="addNewRoleButton" onclick="AddNewRole();">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="EditRole" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Rolle bearbeiten</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form action="roleMgmt.php" method="post" name="addrole">
                        <label class="weiß2">Name</label>
                        <input class="form-control textinput" name="RoleEditName" id="RoleEditName" onkeyup="checkRoleName(true)" required>
                        <label class="weiß2 mt-3">Rollenwert</label>
                        <input class="form-control textinput" type="number" name="RoleEditValue" id="RoleEditValue" required>
                        <input class="hidden disabled form-control textinput" name="RoleEditID" id="RoleEditID">
                        <input class="btn btn-success col-3 offset-9 mt-3" name="submitrequest" type="button" id="editRoleButton" value="Speichern" onclick="saveEditRoleModal()">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>