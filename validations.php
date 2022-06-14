<?php
/**
 * Page to list all points for user or global
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
checkLoginDeny($LOGIN);
checkPermission(config::$ROLE_UNAUTH_USER);
$own = false;
if ($_SESSION['role'] >= config::$ROLE_EMPLOYEE){
    if (isset($_GET['own'])) {
        $own = filter_input(INPUT_GET, 'own', FILTER_VALIDATE_BOOLEAN);
    }
}
if(!isset($_SESSION['csrf'])) {
    Redirect('index.php', false);
}
if ($own) {
    $csrfCheck = filter_input(INPUT_GET, 'csrf', FILTER_SANITIZE_STRING);
    if (!checkCSRFtoken($csrfCheck)) {
        Redirect('index.php', false);
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
                "href" => "js/DropDownMenuApi.js",
                "hrefmin" => "js/DropDownMenuApi.min.js"
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
        <?php
        $rankpoints = getRankDataForAllUsers();
        dump($rankpoints, 8, true);
        if ($_SESSION['role'] < config::$ROLE_EMPLOYEE) {
            ?>
            <h1>Liste der Punkte</h1>
            <?php
        } else {
            ?>
            <div class="container p-0" style="display: flow-root">
                <h1 class="float-left">Liste aller Rang-Punkte</h1>
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $own? "Nur eigene" : "Alle"; ?>
                        </button>
                        <div class="dropdown-menu role-dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item role-dropdown-item"
                               href="validations.php?<?php echo http_build_query(array('own' => true, 'csrf' => createCSRFtokenClient()), '', '&amp;'); ?>">Nur eigene</a>
                            <a class="dropdown-item role-dropdown-item"
                               href="validations.php?<?php echo http_build_query(array('own' => false, 'csrf' => createCSRFtokenClient()), '', '&amp;'); ?>">Alle</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <table class="table table-dark">
            <tr>
                <th scope="col">
                    Name
                </th>
                <th scope="col">
                    Modul
                </th>
                <th scope="col">
                    Wert
                </th>
                <th scope="col">
                    Grund
                </th>
                <th scope="col">
                    Datum
                </th>
            </tr>
            <?php
            foreach ($rankpoints as $rankpoint) {
                if ($rankpoint["user"] !== $_SESSION['name'] && (($_SESSION['role'] < config::$ROLE_EMPLOYEE) || ($own  && $_SESSION['role'] >= config::$ROLE_EMPLOYEE))) {
                    continue;
                }
                ?>
                <tr>
                    <th scope="row" class="align-middle">
                        <?php
                        echo $rankpoint["user"];
                        ?>
                    </th>
                    <td class="align-middle">
                        <?php
                        echo $rankpoint["modul"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $rankpoint["value"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $rankpoint["reason"];
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php
                        echo $rankpoint["date"];
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>