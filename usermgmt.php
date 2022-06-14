<?php
/**
 * Management page for users administration
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
checkLoginDeny($LOGIN);
checkPermission(config::$ROLE_EMPLOYEE);
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
                "href" => "js/DropDownMenuApi.js",
                "hrefmin" => "js/DropDownMenuApi.min.js"
            ),
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "tjs/usermanagement.js",
                "hrefmin" => "tjs/usermanagement.min.js"
            )
        ));
    ?>
</head>

<body style="height: auto">
<?php
generateHeader($LOGIN);
?>
<div class="container mx-auto text-light pt-3 pl-0 pr-0">
    <div class="justify-content-center">
        <h1>Nutzerverwaltung</h1>
        <?php
        $allUsers = getAllUsers();
        dump($allUsers, 8, true);
        $rolesDB = getAllRolles();
        $roles = array();
        dump($rolesDB, 4, true);
        foreach ($rolesDB as $role) {
            $roles[$role['value']] = array(
                "value" => $role['value'],
                "name" => $role['name'],
                "id" => $role['id']
            );
        }
        ksort($roles);
        dump($roles, 8, true);
        ?>
        <table class="table table-dark">
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
                    E-Mail
                </th>
                <th scope="col">
                    Rollen
                </th>
                <th scope="col">

                </th>
            </tr>
            <?php
            foreach ($allUsers

                     as $Users) {
                ?>
                <tr>
                    <th scope="row" class="align-middle">
                        <?php
                        echo $Users["id"];
                        ?>
                    </th>
                    <td class="align-middle">
                        <?php
                        echo $Users["name"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $Users["firstname"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $Users["lastname"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $Users["email"];
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($Users["name"] !== $_SESSION['name']) {
                            $rolevalue = 50;
                            $rolename = "";
                            $disabledRoleSelect = false;
                            foreach ($roles as $role) {
                                if ($role['id'] == $Users['role']) {
                                    $rolename = $role['name'];
                                    $rolevalue = $role['value'];
                                    $disabledRoleSelect = $rolevalue > $_SESSION['role'];
                                }
                            }
                            ?>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle col-11 text-left" type="button"
                                        id="dropdownMenuButton<?php echo $Users['id'] ?>" data-toggle="dropdown" <?php echo $disabledRoleSelect ? "disabled" : "" ;?>>
                                    <?php
                                    echo $rolename;
                                    ?>
                                </button>
                                <?php
                                if (!$disabledRoleSelect) {
                                    ?>
                                    <div class="dropdown-menu role-dropdown-menu">
                                        <?php
                                        foreach ($roles as $role) {
                                            if ($role['value'] <= $_SESSION['role']) {
                                                ?>
                                                <a class="dropdown-item role-dropdown-item"
                                                   onclick="changeRole('<?php echo $role['id'] . "', '" . $role['name'] . "', '" . $Users['id'] . "', '" . $Users['name']; ?>')">
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
                            <?php
                        } else {
                            ?>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle col-11 text-left" type="button"
                                        id="dropdownMenuButton<?php echo $Users['id'] ?>" data-toggle="dropdown"
                                        disabled>
                                    <?php
                                    foreach ($roles as $role) {
                                        if ($role['id'] == $Users['role']) {
                                            echo $role['name'];
                                        }
                                    }
                                    ?>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($Users["name"] !== $_SESSION['name']) {
                            ?>
                            <button onclick="enableDisableUser('<?php echo $Users["id"]; ?>')"
                                    class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                                <?php
                                if ($Users['enabled'] == 1) {
                                ?>
                                    title="Disable"><img src="images/check-solid-dark-green.svg" width="20px"
                                                         style="margin-top: -2px">
                                <?php
                                } else {
                                    ?>
                                    title="Enable"><img src="images/check-solid.svg" width="20px"
                                                        style="margin-top: -2px">
                                    <?php
                                }
                                ?>
                            </button>
                            <?php
                        } else {
                            ?>
                            <button onclick=""
                                    class="btn btn-secondary btn-sq" data-toggle="tooltip" data-placement="top"
                                <?php
                                if ($Users['enabled'] == 1) {
                                ?>
                                    title="Disable" disabled><img src="images/check-solid-dark-green.svg" width="20px"
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
                            <?php
                        }
                        ?>
                        <?php
                        if ($Users["name"] !== $_SESSION['name']) {
                            ?>
                            <button onclick="resetPasswordModalShow('<?php echo $Users['name']; ?>', '<?php echo $Users['id']; ?>');"
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Passwort ändern">
                                <img src="images/key_white.svg" width="15px" style="margin-top: -2px">
                            </button>
                            <?php
                        } else {
                            ?>
                            <button onclick=""
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Passwort ändern" disabled>
                                <img src="images/key_white.svg" width="15px" style="margin-top: -2px">
                            </button>
                            <?php
                        }
                        if ($Users["name"] !== $_SESSION['name']) {
                            ?>
                            <button onclick="sendPasswordResetMail('<?php echo $Users['id']; ?>');"
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Passwort ändern per E-Mail">
                                <img src="images/key_envelope.svg" width="17px" style="margin-top: -2px">
                            </button>
                            <?php
                        } else {
                            ?>
                            <button onclick=""
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Passwort ändern per E-Mail" disabled>
                                <img src="images/key_envelope.svg" width="17px" style="margin-top: -2px">
                            </button>
                            <?php
                        }
                        if ($Users["name"] !== $_SESSION['name']) {
                            ?>
                            <button onclick="OpenModuleRightsAdd('<?php echo $Users['id']; ?>');"
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Modulbasierte Rechte ändern">
                                <img src="images/balance-scale-right-solid-white.svg" width="20px"
                                     style="margin-top: -2px">
                            </button>
                            <?php
                        } else {
                            ?>
                            <button onclick=""
                                    class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                    title="Modulbasierte Rechte ändern" disabled>
                                <img src="images/balance-scale-right-solid-white.svg" width="20px"
                                     style="margin-top: -2px">
                            </button>
                            <?php
                        }
                        ?>
                        <?php
                        if ($Users['mailvalidated'] == 0) {
                            if ($Users["name"] !== $_SESSION['name']) {
                                ?>
                                <button onclick="setMailValidation(<?php echo "'" . $Users["name"] . "',true"; ?>);"
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="Mailadresse unvalidiert">
                                    <img src="images/envelope-solid-white.svg" width="17px" style="margin-top: -2px">
                                </button>
                                <?php
                            } else {
                                ?>
                                <button onclick=""
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="Mailadresse unvalidiert" disabled>
                                    <img src="images/envelope-solid-white.svg" width="17px" style="margin-top: -2px">
                                </button>
                                <?php
                            }
                        } else {
                            if ($Users["name"] !== $_SESSION['name']) {
                                ?>
                                <button onclick="setMailValidation(<?php echo "'" . $Users["name"] . "',false"; ?>);"
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="Mailadresse validiert">
                                    <img src="images/envelope-open-solid-green.svg" width="17px"
                                         style="margin-top: -2px">
                                </button>
                                <?php
                            } else {
                                ?>
                                <button onclick=""
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="Mailadresse validiert" disabled>
                                    <img src="images/envelope-open-solid-green.svg" width="17px"
                                         style="margin-top: -2px">
                                </button>
                                <?php
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<div class="modal fade" id="PasswordChange" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Passwort ändern</h5>
                <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-top:-5px">
                    <img src="images/times-solid.svg" width="14px">
                </button>
            </div>
            <div class="modal-body">
                <div id="wichtig">
                    <form name="changeUserdata">
                        <label class="weiß2">Nutzername</label>
                        <input class="form-control textinput" type="text" name="username" id="ChgPwdUsername" readonly>
                        <label class="weiß2 mt-3">neues Passwort</label>
                        <input class="form-control textinput" type="password" name="pwd1" id="ChgPwdFd1">
                        <label class="weiß2 mt-3">neues Passwort wiederholen</label>
                        <input class="form-control textinput" type="password" name="pwd2" id="ChgPwdFd2">
                        <input class="hidden form-control textinput" id="ChgPwdUserID" readonly>
                        <input class="btn btn-success mt-3 col-3 offset-9" name="submitrequest"
                               value="Ändern" onclick="resetPasswordModalSave()" type="button">
                    </form>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="MeineKommentareTb" role="tabpanel">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="AddUserAuthToModuleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 60px">
                <h5 class="modal-title" style="color: white">Berechtigung ändern</h5>
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
                                Rolle
                            </th>
                            <th scope="col">
                                Freigeschaltet
                            </th>
                            <th scope="col">

                            </th>
                        </tr>
                        </thead>
                        <tbody id="ModuleRightTableBody">

                        </tbody>
                        <tfoot>
                        <tfoot>
                        <tr>
                            <td>
                                <select id="ModuleRightsAddSelect" name="ModuleRightsAddSelect"
                                        class="form-control dropdown-list">

                                </select>
                            </td>
                            <td>
                                <select id="ModuleRolesAddSelect" name="ModuleRolesAddSelect"
                                        class="form-control dropdown-list">

                                </select>
                            </td>
                            <td></td>
                            <td>
                                <input class="hidden" id="UserIdModuleRoleAdd" name="UserIdModuleRoleAdd" required>
                                <button onclick="saveModuleRoleUsermgmt()"
                                        class="btn btn-secondary btn-sq ml-2" data-toggle="tooltip" data-placement="top"
                                        title="speichern">
                                    <img src="images/save-solid-white.svg" width="15px" style="margin-top: -2px">
                                </button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>