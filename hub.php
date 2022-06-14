<?php
/**
 * Page with links to functions of this platform
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once 'bin/inc.php';
if (isset($_SESSION['name']) == false) {
    Redirect("index.php", false);
}
checkLoginDeny($LOGIN);
$destination = array(
    array(
        "href" => "usermgmt.php",
        "title" => "Nutzerverwaltung",
        "image" => "images/users-solid-white.svg",
        "needeRoleValue" => config::$ROLE_EMPLOYEE,
        "text" => "Hier können alle Nutzer eingesehen und die Rolle der Nutzer geändert werden. Auch können Nutzer aktiviert oder deaktiviert werden.",
    ),
    array(
        "href" => "myUser.php",
        "title" => "Eigene Nutzerdaten",
        "image" => "images/user-white.svg",
        "needeRoleValue" => config::$ROLE_UNAUTH_USER,
        "text" => "Hier können Sie Ihre eigenen hinterlegten Daten wie Vor-, Nachname oder E-Mail-Addresse ändern.",
    ),
    array(
        "href" => "validations.php",
        "title" => $_SESSION['role'] >= config::$ROLE_EMPLOYEE ? "Punkteübersicht" : "Eigene Punkteübersicht",
        "image" => "images/table-solid-white.svg",
        "needeRoleValue" => config::$ROLE_UNAUTH_USER,
        "text" => "Hier können Sie Ihre erhaltenen Punkte einsehen.",
    ),
    array(
        "href" => "apimgmt.php",
        "title" => "API-Verwaltung",
        "image" => "images/network-wired-solid-white.svg",
        "needeRoleValue" => config::$ROLE_ADMIN,
        "text" => "Hier können die Zugriffe auf die Schnittstelle für Module verwaltet werden und neue Module zu COSP hinzugefügt werden.",
    ),
    array(
        "href" => "statistics.php",
        "title" => "Statistiken",
        "image" => "images/chart-line-solid-white.svg",
        "needeRoleValue" => config::$ROLE_EMPLOYEE,
        "text" => "Hier werden die relevanten Daten in diversen Zeitabschnitten mittels anschaulicher Diagramme visualisiert.",
    )
);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Hub</title>
    <?php
    generateHeaderTags();
    ?>
</head>
<body class="hub">
<?php
generateHeader(true);
?>
    <div class="flex-container hub-flex pt-5">
        <div class="flex-container flex-item">
            <div class="flex-container mr-4 ml-4">
                <?php
                $count = count($destination);
                foreach ($destination as $dest) {
                    $count--;
                    if ($dest['needeRoleValue'] <= $_SESSION['role']) {
                        ?>
                        <div class="flex-item">
                            <a href="<?php echo $dest['href']; ?>" style="text-decoration: none">
                                <div class="card card-hub mr-4 ml-4">
                                    <img src="<?php echo $dest['image']; ?>" class="card-img-top-hub" style="width: 14rem; height: 14rem">
                                    <div class="card-body">
                                        <h5 class="card-title weiß2"><?php echo $dest['title']; ?></h5>
                                        <p class="card-text" style="color: white"><?php echo $dest['text']; ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>