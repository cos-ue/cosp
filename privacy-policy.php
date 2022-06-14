<?php
/**
 * Page with privacy policy
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
    } else if (isset($_SESSION['username']) && $_SESSION['username'] == 'gast') {
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
    <title>Datenschutz</title>
    <?php
    generateHeaderTags();
    ?>
</head>
<body style="height: auto">
<?php
generateHeader(isset($_SESSION['name']), false, true);
?>
<div class="container">
    <div class="mx-auto mt-4 text-light pt-5 pb-5 mb-3">
        <h2>Datenschutzerklärung</h2>
        <p>Diese Datenschutzerklärung gilt für die Citizen-Science-Plattform "COSP", erreichbar unter "Weblink" .</p>
        <h3>Name und Anschrift des Verantwortlichen</h3>
        <p><?php echo config::$PRIVACY_COMPANY_NAME ?>
            <br><?php echo config::$PRIVACY_COMPANY_STREET ?>
            <br><?php echo config::$PRIVACY_COMPANY_CITY ?>
        </p>
        <p>Telefon: <?php echo config::$PRIVACY_COMPANY_FON ?>
            <?php if (config::$PRIVACY_COMPANY_FAX !== "") { ?>
                <br>Telefax: <?php echo config::$PRIVACY_COMPANY_FAX ?>
            <?php } ?>
            <br>E-Mail: <?php echo config::$PRIVACY_COMPANY_MAIL ?></p>
        <h3>Name und Anschrift des Datenschutzbeauftragten</h3>
        <p><?php echo config::$PRIVACY_REP_NAME ?>
            <br><?php echo config::$PRIVACY_REP_POS ?>
            <br><?php echo config::$PRIVACY_REP_STREET ?>
            <br><?php echo config::$PRIVACY_REP_CITY ?>
        </p>
        <p>Telefon: <?php echo config::$PRIVACY_REP_FON ?>
            <?php if (config::$PRIVACY_REP_FAX !== "") { ?>
                <br>Telefax: <?php echo config::$PRIVACY_REP_FAX ?>
            <?php } ?>
            <br>E-Mail: <?php echo config::$PRIVACY_REP_MAIL ?>
        </p>
        <h3>Allgemeines zur Datenverarbeitung</h3>
        <h4>Umfang der Verarbeitung</h4>
        <p>
            Wir verarbeiten personenbezogene Daten unserer Nutzer grundsätzlich nur, soweit dies zur Bereitstellung
            einer funktionsfähigen Website sowie unserer Inhalte und Leistungen erforderlich ist. Die Verarbeitung
            personenbezogener Daten unserer Nutzer erfolgt regelmäßig nur nach Einwilligung des Nutzers. Eine Ausnahme
            gilt in solchen Fällen, in denen eine vorherige Einholung einer Einwilligung aus tatsächlichen Gründen nicht
            möglich ist und die Verarbeitung der Daten durch gesetzliche Vorschriften gestattet ist.
        </p>
        <h4>Rechtsgrundlage</h4>
        <p>
            Hier bitte weitere Daten zum Datenschutz angeben!
        </p>
        <h4>Datenlöschung und Speicherdauer</h4>
        <p>
            Die personenbezogenen Daten der betroffenen Person werden gelöscht oder gesperrt, sobald der Zweck der
            Speicherung entfällt. Eine Speicherung kann darüber hinaus erfolgen, wenn dies durch den europäischen oder
            nationalen Gesetzgeber in unionsrechtlichen Verordnungen, Gesetzen oder sonstigen Vorschriften, denen der
            Verantwortliche unterliegt, vorgesehen wurde. Eine Sperrung oder Löschung der Daten erfolgt auch dann, wenn
            eine durch die genannten Normen vorgeschriebene Speicherfrist abläuft, es sei denn, dass eine
            Erforderlichkeit zur weiteren Speicherung der Daten für einen Vertragsabschluss oder eine Vertragserfüllung
            besteht.
        </p>
        <h3>Gespeicherte Informationen </h3>
        <p>
            Die Citizen-Science-Plattform erhebt und speichert automatisch in ihren Server-Logfiles Informationen, die
            Ihre Browserdaten an die Server übermittelt. Dies sind:
        </p>
        <ul>
            <li>Browsertyp/ -version</li>
            <li>verwendetes Betriebssystem</li>
            <li>Webseite von der aus der Zugriff erfolgt (Referrer)</li>
            <li>Hostname des anfragenden Rechners (IP-Adresse)</li>
            <li>Datum und Uhrzeit der Serveranfrage</li>
            <li>Name und URL der abgerufenen Daten</li>
            <li>Übertragene Datenmenge</li>
            <li>Meldung, ob der Aufruf erfolgreich war (http-Statuscode)</li>
        </ul>
        <p>
            Hier bitte weitere Daten zum Datenschutz angeben!
        </p>
        <h3>
            Verschlüsselung
        </h3>
        <p>
            Diese Seite nutzt aus Gründen der Sicherheit und zum Schutz der Übertragung aller Inhalte eine
            SSL-Verschlüsselung.
        </p>
        <p>
            Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://“ auf
            „https://“ wechselt sowie am Schloss-Symbol in Ihrer Browserzeile.
        </p>
        <h3>Cookies</h3>
        <p>
            Cookies sind kleine Textdateien, die vom Webserver erzeugt und an Ihren Internetbrowser geschickt und dort
            gespeichert bzw. auf Ihrem Browser abgelegt werden. Cookies dienen dazu, die Funktionalität und den Komfort
            der Webseite für Sie zu erhöhen.
        </p>
        <p>
            Die Nutzung unserer Seiten ist nicht ohne Cookies möglich. Wenn Sie sich auf einem zugriffsgeschützten
            Bereich der Internetseiten einloggen, wird ein personalisierter Datenbankeintrag bestehend aus Session-ID,
            IP-Adresse, Zeitstempel, Benutzer-ID und Sessiondaten erzeugt. Zudem wird ein personalisiertes Cookie
            angelegt, das in der Regel beim vollständigen Schließen des Browsers gelöscht wird.
        </p>
        <p>
            Zur Reichweitenmessung wird die IP-Adresse des Nutzers und der Login-Status gespeichert.
        </p>
        <h3>Kontaktformular und E-Mail-Kontakt </h3>
        <p>
            Hier Datenschutzangaben zum Kontaktformular angeben!
        </p>
    </div>
</div>
</body>
</html>