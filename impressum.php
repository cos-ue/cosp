<?php
/**
 * Page with impressum
 *
 * @package default
 */

/**
 * @const enables loading of other files without dying to improve security
 */
define('NICE_PROJECT', true);
require_once "bin/inc.php";
if (config::$DEBUG) {
    if (isset($_SESSION['name']) == false) {
        Redirect('index.php');
    }
}
if (config::$BETA || config::$MAINTENANCE) {
    if (isset($_SESSION['name']) == false) {
        Redirect('index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Impressum</title>
    <?php
    generateHeaderTags();
    ?>
</head>
<body style="height: auto">
<?php
generateHeader(isset($_SESSION['name']), false, true);
?>
<div class="container">
    <div class="mx-auto text-light pt-3 pb-3 mb-3">
        <h1>Impressum</h1>
        <p>
            Hier bitte das Impressum einfügen.
        </p>
        <h2>Haftung für Inhalte </h2>
        <p>
            Hier bitte das Impressum einfügen.
        </p>
        <h2>Haftungsausschluss</h2>
        <h3>Haftung für Links</h3>
        <p>
            Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben.
            Deshalb
            können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist
            stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum
            Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt
            der
            Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne
            konkrete
            Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir
            derartige Links umgehend entfernen.
        </p>
        <h3>Urheberrecht</h3>
        <p>
            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen
            Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der
            Grenzen
            des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads
            und
            Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte
            auf
            dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere
            werden
            Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam
            werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir
            derartige Inhalte umgehend entfernen.
        </p>
        <h2>Erwähnungen</h2>
        <h3>Icons</h3>
        <p>
            Die Icons stammen von <a class="text-light" href="https://fontawesome.com/">Fontawsome</a> mit der
            entsprechenden <a class="text-light" href="https://fontawesome.com/license">Lizenz</a> und wurden
            Teilweise geändert.
        </p>
    </div>
</div>
</body>
</html>