<?php
/**
 * This file contains a class with all mail-templates
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * Class MailTemplates
 * used for templating standardized mails
 */
class MailTemplates
{
    /**
     * template for mail send to user, if user registered newly
     * @param string $Link link for mail validation
     * @param string $Username username of user which is newly registered
     * @return string returns completed mail content
     */
    public static function RegisterMail($Link, $Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>E-Mail Validation " . config::$MAIN_CAPTION . "</h2><div>Hallo " . $Username . " ,<br />Ihre Registrierung ist fast abgeschlossen. Bitte folgen Sie diesem <a href =\"" . $Link . "\">Link</a>, um Ihre E-Mail-Adresse zu bestätigen.</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to admin, if user registered newly
     * @param string $Username username of user which is newly registered
     * @return string returns completed mail content
     */
    public static function ZentralRegisterMail($Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>Neuer Nutzeranmeldung wurde angelegt in " . config::$MAIN_CAPTION . "</h2><div>Sehr geehrte Damen und Herren,<br />" . $Username . " hat sich einen Nutzeraccount angelegt und möchte in der Nutzervewaltung von <a href='https://" . config::$DOMAIN . "'>" . config::$MAIN_CAPTION . "</a> freigeschalten werden.</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to user if user want's to reset password
     * @param string $Link link for reset Password via Web-UI
     * @param string $Username Username of requesting user
     * @return string Mail-Template
     */
    public static function ResetPasswordByMail($Link, $Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>Passwort zurücksetzen " . config::$MAIN_CAPTION . "</h2><div>Hallo " . $Username . " ,<br />Bitte folgen Sie diesem <a href =\"" . $Link . "\">Link</a> um Ihr Passwort zurückzusetzen.</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to users old mailadress, if users mail has changed
     * @param string $newmail neue mailaddresse des Nutzers
     * @param string $Username username of user which is newly registered
     * @return string returns completed mail content
     */
    public static function MailChangedOldAddress($newmail, $Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>E-Mail Validation " . config::$MAIN_CAPTION . "</h2><div>Hallo " . $Username . " ,<br />Die Änderung Ihrer E-Mail-Addresse ist fast abgeschlossen. Bitte folgen Sie dem Link in der E-Mail an Ihre neue E-Mail-Adresse. Sollten Sie diese Änderung nicht vorgenommen haben, melden Sie sich bitte bei " . config::$ZENTRAL_MAIL . ".</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to users new mailadress, if users mail has changed
     * @param string $Link link for mail validation
     * @param string $Username username of user which is newly registered
     * @return string returns completed mail content
     */
    public static function MailChangedNewAddress($Link, $Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>E-Mail Validation " . config::$MAIN_CAPTION . "</h2><div>Hallo " . $Username . " ,<br />Die Änderung Ihrer E-Mail-Addresse ist fast abgeschlossen. Bitte folgen Sie diesem <a href =\"" . $Link . "\">Link</a>, um Ihre neue Adresse zu bestätigen.</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to admin, if user registered newly
     * @param string $Username username of user which is newly registered
     * @return string returns completed mail content
     */
    public static function ZentralMailChanged($Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>E-Mail-Änderung " . $Username . "</h2><div>Sehr geehrte Damen und Herren,<br />" . $Username . " hat seine E-Mail-Adresse geändert.</div><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to admin, if user want's to contact admins
     * @param string $Username username of user which has the wish to contact the admins
     * @param string $msg Message of the User
     * @return string returns completed mail content
     */
    public static function ZentralMailContact($Username, $msg)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>Nachricht von " . $Username . "</h2><div>Sehr geehrte Damen und Herren,<br />" . $Username . " hat folgende Nachricht für Sie hinterlassen:</div><hr>" . $msg . "<hr><br /><div>Mit freundlichen Grüßen <br />das " . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }

    /**
     * template for mail send to user if user want's to reset password
     * @param string $Link link for reset Password via Web-UI
     * @param string $Username Username of requesting user
     * @return string Mail-Template
     */
    public static function SendUsernameByMail($Username)
    {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional //EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/></head><body><h2>Anforderung des Nutzernamens " . config::$MAIN_CAPTION . '</h2><div>Hallo Nutzer,<br />Ihr Nutzername lautet: "' . $Username . '" . Falls Sie ihr Passwort vergessen haben, so fordern Sie ein neues Passwort mittels unserer Websitte an.</div><br /><div>Mit freundlichen Grüßen <br />das ' . config::$MAIN_CAPTION . "-Team</div></body></html>";
    }
}