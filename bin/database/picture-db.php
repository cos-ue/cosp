<?php
/**
 * This File includes all needed functions for pictures-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * insert new picture
 * @param string $title title of picture
 * @param string $description description of picture
 * @param string $picurl pictures file name
 * @param string $preview base64 encoded preview
 * @param string $token token for picture
 * @param int $uid id of user
 * @param int $aid id of application
 * @return bool|null result of request
 */
function insertPic($title, $description, $picurl, $preview, $token, $uid, $aid)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'pictures` (token, `title`, description, picurl, preview, uid, aid) values ( :token , :title , :description , :picurl , :preview , :uid , :aid );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $title;
    $params[1]['nam'] = ":title";
    $params[2] = array();
    $params[2]['typ'] = 'd';
    $params[2]['val'] = $description;
    $params[2]['nam'] = ":description";
    $params[3] = array();
    $params[3]['typ'] = 'd';
    $params[3]['val'] = $picurl;
    $params[3]['nam'] = ":picurl";
    $params[4] = array();
    $params[4]['typ'] = 's';
    $params[4]['val'] = $preview;
    $params[4]['nam'] = ":preview";
    $params[5] = array();
    $params[5]['typ'] = 's';
    $params[5]['val'] = $uid;
    $params[5]['nam'] = ":uid";
    $params[6] = array();
    $params[6]['typ'] = 's';
    $params[6]['val'] = $aid;
    $params[6]['nam'] = ":aid";

    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * deletes a picture
 * @param int $pid picture id
 * @return bool|null state of request
 */
function deletePicture($pid)
{
    $prep_stmt = "DELETE FROM `" . config::$SQL_PREFIX . "pictures` WHERE id = :pic_id";
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $pid;
    $params[0]['nam'] = ":pic_id";
    dump($params, 8);
    $x = ExecuteStatementWR($prep_stmt, $params, false);
    return $x;
}

/**
 * gets complete list of pictures for certain application
 * @param string $apptoken token of application
 * @return array structured result data
 */
function getPictureListDB($apptoken)
{
    $prep_stmt = 'select P.id, token, title, description, uid, deleted, `source` , D.name as sourcename, D.id as sourceid from `' . config::$SQL_PREFIX . 'pictures` as P left join `' . config::$SQL_PREFIX . 'source_type`  as D on P.sourcetype = D.id where aid = :aid ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = getAllApiTokensOrderedByToken()[$apptoken]['id'];
    $params[0]['nam'] = ":aid";
    $result = ExecuteStatementWR($prep_stmt, $params);
    return $result;
}

/**
 * gets certain picture for application
 * @param string $apptoken unique identifier of application
 * @param string $pictureToken unique identifier of picture
 * @return array structured result data
 */
function getSinglePictureFromDB($apptoken, $pictureToken)
{
    $prep_stmt = 'select P.id, token, title, description, uid, deleted, `source` , D.name as sourcename, D.id as sourceid from `' . config::$SQL_PREFIX . 'pictures` as P left join `' . config::$SQL_PREFIX . 'source_type`  as D on P.sourcetype = D.id where aid = :aid and token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = getAllApiTokensOrderedByToken()[$apptoken]['id'];
    $params[0]['nam'] = ":aid";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $pictureToken;
    $params[1]['nam'] = ":token";
    $result = ExecuteStatementWR($prep_stmt, $params)[0];
    return $result;
}

/**
 * gets Previewpicture from database
 * @param string $token unique identifier of picture
 * @return array structured result data
 */
function getPreviewPicture($token)
{
    $prep_stmt = 'select preview from `' . config::$SQL_PREFIX . 'pictures` where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * gets path to picture on harddrive
 * @param string $token unique identifier of picture
 * @return array structured result data
 */
function getFullsizePicture($token)
{
    $prep_stmt = 'select picurl from `' . config::$SQL_PREFIX . 'pictures` where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    if (count($result) > 0) {
        $result = $result[0];
    }
    dump($result, 7);
    return $result;
}

/**
 * gets id of picture by its token
 * @param string $token unique identifier of picture
 * @return array structured result data
 */
function getPictureIdByToken($token) {
    $prep_stmt = 'select id from `' . config::$SQL_PREFIX . 'pictures` where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['id'];
}

/**
 * gets username for picture
 * @param string $token unique identifier of picture
 * @return string result is username
 */
function getPictureUploadingUserByToken($token) {
    $prep_stmt = 'SELECT name from ' . config::$SQL_PREFIX . 'pictures join `' . config::$SQL_PREFIX . 'user-login` on `' . config::$SQL_PREFIX . 'pictures`.uid = `' . config::$SQL_PREFIX . 'user-login`.id where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['name'];
}

/**
 * updates data of picture
 * @param string $token unique identifier of picture
 * @param string $title title of picture
 * @param string $description description of picture
 * @return bool|null state of request
 */
function updateMaterialByToken($token, $title, $description) {
    $prep_stmt = 'update ' . config::$SQL_PREFIX . 'pictures SET title = :title , description = :description where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $title;
    $params[1]['nam'] = ":title";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $description;
    $params[2]['nam'] = ":description";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}

/**
 * updates deletion state of picture by token
 * @param int $id Identifier of Picture
 * @param bool $state true if it should be marked as deleted
 * @return array|bool|null result
 */
function updateDeletionStatePictureByToken($id, $state)
{
    $val = $state ? 1 : 0;
    $prep_stmt = 'Update `' . config::$SQL_PREFIX . 'pictures` SET `deleted`  = :deleted where `id` = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $val;
    $params[0]['nam'] = ":deleted";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $id;
    $params[1]['nam'] = ":id";
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    return $result;
}

/**
 * updates source of picture
 * @param string $token alphanumeric identifier of picture
 * @param string $source source of picture
 * @param int $sourceType identifier of type of source
 * @return array|void|null structured result
 */
function updateMaterialSourceByToken($token, $source, $sourceType) {
    $prep_stmt = 'update ' . config::$SQL_PREFIX . 'pictures SET source = :source , sourcetype = :sourcetype where token = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":token";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $source;
    $params[1]['nam'] = ":source";
    $params[2] = array();
    $params[2]['typ'] = 's';
    $params[2]['val'] = $sourceType;
    $params[2]['nam'] = ":sourcetype";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}