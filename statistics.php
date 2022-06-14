<?php
/**
 * Page for showing statistics
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
if (isset($_SESSION["name"]) == false) {
    Redirect("/index.php");
    permissionDenied();
}
checkPermission(config::$ROLE_EMPLOYEE);
$allowedTypes = array(
    'login' => "Nutzungsdaten",
    'user' => "Neue Nutzer",
    "pics" => "Neue oder Bearbeitete Bilder",
    "story" => "Neue oder Bearbeitete Geschichten",
    "contact" => "Nutzung des Kontaktformulars"
);
$type = 'login';
if (isset($_GET['type'])) {
    $newType = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
    if (key_exists($newType, $allowedTypes)) {
        $type = $newType;
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
    <title>COSP - Statistik</title>
    <?php
    generateHeaderTags(
        array(
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "jse/moment.min.js"
            ),
            array(
                "type" => "link",
                "rel" => "stylesheet",
                "href" => "css/statistics.css",
                "hrefmin" => "css/statistics.min.css"
            ),
            array(
                "type" => "link",
                "rel" => "stylesheet",
                "href" => "csse/Chart.min.css"
            ),
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "jse/Chart.min.js"
            ),
            array(
                "type" => "script",
                "typeval" => "text/javascript",
                "href" => "js/statistics.js",
                "hrefmin" => "js/statistics.min.js",
            )
        )
    );
    ?>
</head>

<body style="height: auto">
<?php
generateHeader(true);
$diagramms = array(
    array(
        "type" => 'D',
        "Amount" => 3,
        "ID" => 0
    ),
    array(
        "type" => 'D',
        "Amount" => 5,
        "ID" => 1
    ),
    array(
        "type" => 'W',
        "Amount" => 2,
        "ID" => 2
    ),
    array(
        "type" => 'W',
        "Amount" => 4,
        "ID" => 3
    ),
    array(
        "type" => 'M',
        "Amount" => 3,
        "ID" => 4
    ),
    array(
        "type" => 'M',
        "Amount" => 6,
        "ID" => 5
    ),
    array(
        "type" => 'M',
        "Amount" => 9,
        "ID" => 6
    ),
    array(
        "type" => 'Y',
        "Amount" => 1,
        "ID" => 7
    )
)
?>
<div class="container text-light pt-3">
    <div style="width: 100%;">
        <div class="container p-0" style="display: flow-root">
            <h1 class="float-left">Statistik</h1>
            <div class="float-left">
                <div class="dropdown">
                    <button class="btn btn-secondary ml-5 dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $allowedTypes[$type]; ?>
                    </button>
                    <div class="dropdown-menu role-dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        foreach (array_keys($allowedTypes) as $key) {
                            ?>
                            <a class="dropdown-item role-dropdown-item"
                               href="statistics.php?<?php echo http_build_query(array('type' => $key), '', '&amp;'); ?>"><?php echo $allowedTypes[$key]; ?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var loading = <?php generateJson(array('src' => $type, 'data' => $diagramms)) ?>;
            loadStatisticalData();
        </script>
        <?php
        foreach ($diagramms as $diagramm){
            $title = "Letzte " . $diagramm['Amount'] . ' ';
            switch ($diagramm['type']){
                case 'D':
                    $title .= "Tage";
                    break;
                case 'W':
                    $title .= "Wochen";
                    break;
                case 'M':
                    $title .= "Monate";
                    break;
                case 'Y':
                    $title .= "Jahre";
                    break;
            }
            ?>
            <div class="mb-3">
                <h2><?php echo $title ?></h2>
                <div class="card card-statistics align-content-center">
                    <canvas id="myChart_<?php echo $diagramm['ID'] ?>"></canvas>
                    <script type="text/javascript">
                        var ctx_<?php echo $diagramm['ID'] ?> = document.getElementById('myChart_<?php echo $diagramm['ID'] ?>').getContext('2d');
                        var myChart = new Chart(ctx_<?php echo $diagramm['ID'] ?>, generateConfig(<?php echo $diagramm['ID'] ?>, '<?php echo $diagramm['type']?>'));
                    </script>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</body>
</html>