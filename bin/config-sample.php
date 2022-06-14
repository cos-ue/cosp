<?php
/**
 * This File contains an example of the configuration file
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Class configsample
 * rename the class to config, rename file to 'config.php' and change values below
 */
class configsample
{
    /**
     * @var string sets sql server
     */
    public static $SQL_SERVER = "localhost";

    /**
     * @var string sets sql user
     */
    public static $SQL_USER = "root";

    /**
     * @var string sets password for sql user
     */
    public static $SQL_PASSWORD = "";

    /**
     * @var string sets schema which should be used
     */
    public static $SQL_SCHEMA = "SchemaNameHere";

    /**
     * @var string sets prefix of tables which will be used
     */
    public static $SQL_PREFIX = "dload__";

    /**
     * currently only pdo is available
     * @var string sets type of connector
     */
    public static $SQL_Connector = "pdo";

    /**
     * @var string sets base domain
     */
    public static $BASE_DMN = ".konfuzzyus.de";

    /**
     * @var bool enables self registration
     */
    public static $SELF_REGISTRATION = true;

    /**
     * @var string Shortname of plattform
     */
    public static $MAIN_CAPTION = "COSP";

    /**
     * @var string longname of plattform
     */
    public static $TAGLINE_CAPTION = "Citizen Open Science Plattform";

    /**
     * @var bool indicates if system is in debug mode
     */
    public static $DEBUG = false;

    /**
     * @var int sets level of debug; if above zero there could be impacts on apis
     */
    public static $DEBUG_LEVEL = 3;

    /**
     * @var int sets minimal password length
     */
    public static $PWD_LENGTH = 8;

    /**
     * @var string selects used password algorithm
     */
    public static $PWD_ALGORITHM = PASSWORD_ARGON2ID;

    /**
     * @var int set length of random string for multiple functions
     */
    public static $RANDOM_STRING_LENGTH = 170;

    /**
     * @var string domain of this plattform
     */
    public static $DOMAIN = "test.de";

    /**
     * @var string address from which mails are sent
     */
    public static $SENDER_ADDRESS = "test@test.de";

    /**
     * @var string secret for multiple security functions
     */
    public static $HMAC_SECRET = "Secret";

    /**
     * @var string subpath to folder for uploads
     */
    public static $UPLOAD_DIR = 'images/uploadMat';

    /**
     * @var string admins mail address
     */
    public static $ZENTRAL_MAIL = "admin@test.de";

    /**
     * @var int required rolevalue for guests; do not Change
     */
    public static $ROLE_GUEST = 0;

    /**
     * @var int required rolevalue for unauthenticated users; do not Change
     */
    public static $ROLE_UNAUTH_USER = 1;

    /**
     * @var int required rolevalue for authenticated; do not Change
     */
    public static $ROLE_AUTH_USER = 2;

    /**
     * @var int required rolevalue for employee; do not Change
     */
    public static $ROLE_EMPLOYEE = 10;

    /**
     * @var int required rolevalue for admins; do not Change
     */
    public static $ROLE_ADMIN = 20;

    /**
     * @var bool enabeles Beta-Mode
     */
    public static $BETA = false;

    /**
     * @var bool enables Maintenance mode
     */
    public static $MAINTENANCE = false;

    /**
     * @var string name of content responsible for Impressum
     */
    public static $IMPRESSUM_NAME = "Max Muster";

    /**
     * @var string street and Housenumber of content responsible for Impressum
     */
    public static $IMPRESSUM_STREET = "Musterstraße 21";

    /**
     * @var string City and Postalcode of content responsible for Impressum
     */
    public static $IMPRESSUM_CITY = "00000 Musterstadt";

    /**
     * @var bool enables some special chats in captcha codes
     */
    public static $SPECIAL_CHARS_CAPTCHA = true;

    /**
     * @var string Name of your Company
     */
    public static $PRIVACY_COMPANY_NAME = "Musterfirma/Musterunternehmer";

    /**
     * @var string Street and Housenumber of the company address
     */
    public static $PRIVACY_COMPANY_STREET = "Musterstraße 1";

    /**
     * @var string postalcode and city of the company adress
     */
    public static $PRIVACY_COMPANY_CITY = "12345 Musterstadt";

    /**
     * @var string phone contact of company
     */
    public static $PRIVACY_COMPANY_FON = "Telefonnummer";

    /**
     * @var string fax contact of company
     */
    public static $PRIVACY_COMPANY_FAX = "Faxnummer";

    /**
     * @var string contactmailadress of company
     */
    public static $PRIVACY_COMPANY_MAIL = "muster@mustermail.xy";

    /**
     * @var string name of privacy representative
     */
    public static $PRIVACY_REP_NAME = "Maxie Musterfrau";

    /**
     * @var string position of privacy representative
     */
    public static $PRIVACY_REP_POS = "Datenschutzbeauftragte";

    /**
     * @var string street and housenumber of privacy representative address
     */
    public static $PRIVACY_REP_STREET = "Musterstraße 1";

    /**
     * @var string postalcode and city of privacy representative address
     */
    public static $PRIVACY_REP_CITY = "12345 Musterstadt";

    /**
     * @var string phone contacr of privacy representative
     */
    public static $PRIVACY_REP_FON = "Telefonnummer";

    /**
     * @var string fax contacr of privacy representative
     */
    public static $PRIVACY_REP_FAX = "Faxnummer";

    /**
     * @var string mail contacr of privacy representative
     */
    public static $PRIVACY_REP_MAIL = "datenschutz@mustermail.xy";

    /**
     * @var bool determines if data is directly deleted (true) or only marked as deleted (false)
     */
    public static $DIRECT_DELETE = false;

    /**
     * @var array makes additional mail parameter available
     */
    public static $ADDITIONAL_MAIL_PARAM = array();

    /**
     * @var string defines Logo of website, relational from root directory
     */
    public static $LOGO = "images/cosp_logo.svg";

    /**
     * @var string defines Brand Logo in navbar of website, relational from root directory
     */
    public static $LOGO_BRAND = "images/cosp_logo_brand.svg";
}
