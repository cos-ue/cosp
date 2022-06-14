<?php
/**
 * Management page for module right administration
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
checkLoginDeny($LOGIN);
checkModulModuleRights(config::$ROLE_EMPLOYEE);
$allModules = false;
if (isStaff($_SESSION['name'])) {
    $allModules = true;
}
$selectedModule = -1;
if (key_exists('aid', $_GET)) {
    $selectedModule = filter_input(INPUT_GET, 'aid', FILTER_SANITIZE_STRING);
}
$allowedModules = array();
$username = $_SESSION['name'];
$username = 'root2';
if ($allModules) {
    $allowedModules = getAllApiTokens();
} else {
    $rights = getAllModuleBasedRightsByUserID(getUserData($username)['id']);
    $allowed = array();
    $apis = getAllApiTokens();
    foreach ($rights as $right) {
        if ($right['value'] >= config::$ROLE_EMPLOYEE) {
            $allowed[] = $right['apiid'];
        }
    }
    foreach ($apis as $api) {
        if (in_array($api['id'], $allowed)) {
            $allowedModules[] = $api;
        }
    }
}
$rolesDB = getAllRolles();
$roles = array();
foreach ($rolesDB as $role) {
    $roles[$role['value']] = $role;
}
ksort($roles);
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
                "href" => "tjs/modulerights.js",
                "hrefmin" => "tjs/modulerights.min.js"
            )
        )
    );
    $hit = false;
    $unhideID = $allowedModules[0]['id'];
    $unhideName = $allowedModules[0]['name'];
    if ($selectedModule != -1) {
        foreach ($allowedModules as $module) {
            if ($module['id'] == $selectedModule) {
                $hit = true;
                $unhideID = $selectedModule;
                $unhideName = $module['name'];
            }
        }
    }
    ?>
    <script type="text/javascript">
        var currentlyHidden = <?php echo $unhideID; ?>;
        var currentlySelectedName = "<?php echo $unhideName; ?>";
    </script>
</head>

<body style="height: auto">
<?php
generateHeader($LOGIN);
?>
<div class="container mx-auto text-light pt-3">
    <div class="justify-content-center">
        <?php
        if (countRightsOnModules() > 1 || $allModules) {
            ?>
            <div class="container p-0" style="display: flow-root">
                <h1 class="float-left">Modulberechtigungen</h1>
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButtonModuleRights"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            if ($selectedModule == -1 || $hit == false) {
                                echo $allowedModules[0]['name'];
                            } else {
                                foreach ($allowedModules as $module) {
                                    if ($module['id'] == $selectedModule) {
                                        echo $module['name'];
                                    }
                                }
                            }
                            ?>
                        </button>
                        <div class="dropdown-menu role-dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <?php
                            foreach ($allowedModules as $module) {
                                ?>
                                <button class="dropdown-item role-dropdown-item"
                                        onclick="changeModuleRightTableBody(<?php echo $module['id']; ?>, '<?php echo $module['name']; ?>')"><?php echo $module['name']; ?></button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <h1>Modulberechtigungen</h1>
            <?php
        }
        ?>
        <table class="table table-dark">
            <thead>
            <tr>
                <th scope="col">
                    ID
                </th>
                <th scope="col">
                    Username
                </th>
                <th scope="col">
                    Vorname
                </th>
                <th scope="col">
                    Nachname
                </th>
                <th scope="col">
                    Rolle
                </th>
                <th scope="col">
                    <button onclick="OpenMassAddRightsToModule();"
                            class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                            title="Berechtigungen Hinzufügen">
                        <img src="images/plus-solid.svg" width="15px" style="margin-top: -2px">
                    </button>
                </th>
            </tr>
            </thead>
            <?php
            $moduleNumber = 0;
            foreach ($allowedModules as $module) {
                $ModuleRights = getAllModuleBasedRightsByModulID($module['id']);
                $hidden = false;
                if ($hit == false || $selectedModule == -1) {
                    $hidden = $moduleNumber != 0;
                } else {
                    $hidden = $module['id'] !== $selectedModule;
                }
                ?>
                <tbody id="<?php echo 'usertableRights' . $module['id'] ?>" <?php echo $hidden ? "hidden" : "" ?>>
                <?php
                foreach ($ModuleRights as $right) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $right['id']; ?>
                        </td>
                        <td>
                            <?php echo $right['username']; ?>
                        </td>
                        <td>
                            <?php echo ($right['firstname'] !== null && $right['firstname'] !== "") ? $right['firstname'] : ""; ?>
                        </td>
                        <td>
                            <?php echo ($right['lastname'] !== null && $right['lastname'] !== "") ? $right['lastname'] : ""; ?>
                        </td>
                        <td>
                            <?php
                            $disableDropDown = $right['username'] == $_SESSION['name'] || $right['value'] > $_SESSION['role'];
                            ?>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle col-11 text-left" type="button"
                                        id="dropdownMenuButton<?php echo $right['id']; ?>"
                                        data-toggle="dropdown" <?php echo $disableDropDown ? "disabled" : ""; ?>>
                                    <?php
                                    echo $right['rolename'];
                                    ?>
                                </button>
                                <?php
                                if ($disableDropDown == false) {
                                    ?>
                                    <div class="dropdown-menu role-dropdown-menu">
                                        <?php
                                        foreach ($roles as $role) {
                                            if ($role['value'] <= $_SESSION['role']) {
                                                ?>
                                                <a class="dropdown-item role-dropdown-item"
                                                   onclick="updateModulright(<?php echo $right['id']; ?>, <?php echo $role['id']; ?>);">
                                                    <?php
                                                    echo $role['name'];
                                                    ?>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            if ($right['username'] !== $_SESSION['name']) {
                                ?>
                                <button class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                                    <?php
                                    if ($right['disabled'] == 0) {
                                    ?>
                                        onclick="SetDisableStateUserForModule(<?php echo $right['id']; ?>, false)"
                                        title="Disable"><img src="images/check-solid-dark-green.svg" width="20px"
                                                             style="margin-top: -2px">
                                    <?php
                                    } else {
                                        ?>
                                        onclick="SetDisableStateUserForModule(<?php echo $right['id']; ?>, true)"
                                        title="Enable"><img src="images/check-solid.svg" width="20px"
                                                            style="margin-top: -2px">
                                        <?php
                                    }
                                    ?>
                                </button>
                                <?php
                                $onclick = "deleteModuleBasedRightMgmt(" . $right['id'] . ");";
                                ?>
                                <button onclick="<?php echo $disableDropDown ? "" : $onclick ?>"
                                        class="btn btn-secondary btn-sq ml-3" data-toggle="tooltip" data-placement="top"
                                        title="Löschen" <?php echo $disableDropDown ? "disabled" : "" ?>>
                                    <img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">
                                </button>
                                <?php
                            } else {
                                ?>
                                <button onclick=""
                                        class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                                    <?php
                                    if ($right['disabled'] == 0) {
                                    ?>
                                        title="Disable" disabled><img src="images/check-solid-dark-green.svg"
                                                                      width="20px"
                                                                      style="margin-top: -2px">
                                    <?php
                                    } else {
                                        ?>
                                        title="Enable" disabled><img src="images/check-solid.svg" width="20px"
                                                                     style="margin-top: -2px">
                                        <?php
                                    }
                                    ?>
                                </button>
                                <button onclick=""
                                        class="btn btn-secondary btn-sq ml-3" data-toggle="tooltip" data-placement="top"
                                        title="Löschen" disabled>
                                    <img src="images/trash-alt-solid.svg" width="15px" style="margin-top: -2px">
                                </button>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $moduleNumber++;
                }
                ?>
                </tbody>
                <?php
            }
            ?>
            <tfoot>
            </tfoot>
        </table>
        <?php
        if (config::$DEBUG) {
            dump($allowedModules, 2);
            foreach ($allowedModules as $module) {
                $ModuleRights = getAllModuleBasedRightsByModulID($module['id']);
                dump($ModuleRights, 2);
            }
            dump($roles, 2);
        }
        ?>
    </div>
</div>
<div class="modal fade" id="ModuleRightsMassAddModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white" id="ModuleAddRightsModal">Modulberechtigungen
                    hinzufügen</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form>
                        <label class="weiß2">Modul</label>
                        <input class="form-control textinput" name="ApiNameModalRightsMassAdd"
                               id="ApiNameModalRightsMassAdd"
                               disabled required>
                        <label class="weiß2 mt-3">Rolle</label>
                        <select id="MassAddModuleRightsRoleSelect" name="MassAddModuleRightsRoleSelect" class="form-control dropdown-list">
                            <?php
                            foreach ($roles as $role) {
                                if ($role['value'] <= $_SESSION['role']) {
                                    ?>
                                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <label class="weiß2 mt-3">Nutzerauswahl</label>
                        <table class="table table-dark">
                            <thead>
                            <th>

                            </th>
                            <th>
                                ID
                            </th>
                            <th>
                                Nutzername
                            </th>
                            <th>
                                Vorname
                            </th>
                            <th>
                                Nachname
                            </th>
                            </thead>
                            <tbody id="MassAddModulRightsUsersSelect">

                            </tbody>
                        </table>
                        <input class="btn btn-secondary col-3 offset-9" name="submitrequest" type="button" id="editRoleButton" value="Speichern" onclick="saveAddMassUsers();">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>