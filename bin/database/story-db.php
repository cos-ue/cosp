<?php
/**
 * This File includes all needed functions for stories-table
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * adds new story to database
 * @param array $data structured array with all needed information
 * @return bool|null state of request
 */
function insertStory($data)
{
    $stmt = 'INSERT INTO `' . config::$SQL_PREFIX . 'stories` (`user_id`, `storie_token`, `title`, `story`, `aid`) values ( :user_id , :storie_token , :title , :story , :aid );';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $data['uid'];
    $params[0]['nam'] = ":user_id";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $data['hash'];
    $params[1]['nam'] = ":storie_token";
    $params[2] = array();
    $params[2]['typ'] = 'd';
    $params[2]['val'] = $data['title'];
    $params[2]['nam'] = ":title";
    $params[3] = array();
    $params[3]['typ'] = 'd';
    $params[3]['val'] = $data['story'];
    $params[3]['nam'] = ":story";
    $params[4] = array();
    $params[4]['typ'] = 'd';
    $params[4]['val'] = $data['aid'];
    $params[4]['nam'] = ":aid";
    $result = ExecuteStatementWR($stmt, $params, false);
    return $result;
}

/**
 * selects single user story from database
 * @param string $token unique identifier of story
 * @return array structured result data
 */
function getUserStory($token)
{
    $stmt = 'Select ' . config::$SQL_PREFIX . 'stories.id as StoryId, title, story, `name`, `date` , `approved`, deleted from `' . config::$SQL_PREFIX . 'stories` join `' . config::$SQL_PREFIX . 'user-login` on user_id = `' . config::$SQL_PREFIX . 'user-login`.id where `storie_token` = :storie_token';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    $result = ExecuteStatementWR($stmt, $params);
    return $result;
}

/**
 * gets id of story with unique identifier of story
 * @param string $token unique identifier of story
 * @return int result is id
 */
function getStoryIdByToken($token)
{
    $stmt = 'Select id from `' . config::$SQL_PREFIX . 'stories` where `storie_token` = :storie_token';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    $result = ExecuteStatementWR($stmt, $params);
    return $result[0]['id'];
}

/**
 * gets all stories of a given application
 * @param int $aid id of application
 * @return array structured result of request
 */
function getStoriesByApp($aid)
{
    $stmt = 'Select storie_token, user_id, title, approved, deleted from `' . config::$SQL_PREFIX . 'stories` where `aid` = :aid';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $aid;
    $params[0]['nam'] = ":aid";
    $result = ExecuteStatementWR($stmt, $params);
    return $result;
}

/**
 * updates story based on unique story identifier
 * @param string $token unique story identifier
 * @param string $title title of story
 * @param string $story narration of story
 * @return bool|null state of request
 */
function updateStorieByToken($token, $title, $story) {
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'stories` SET `title` = :title , `story` = :story , `date` = CURRENT_TIME where `storie_token` = :token ;';
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
    $params[2]['val'] = $story;
    $params[2]['nam'] = ":story";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}

/**
 * gets api token from owning api
 * @param string $token token of selected story
 * @return string result api token
 */
function getModuleTokenByStoryToken($token) {
    $prep_stmt = 'select A.token from `' . config::$SQL_PREFIX . 'stories` as S join `' . config::$SQL_PREFIX . 'api-token` as A on S.aid = A.id where S.storie_token = :storie_token;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['token'];
}

/**
 * gets story id from story with token
 * @param string $token token of selected story
 * @return string result api token
 */
function getStoryIdByStoryToken($token) {
    $prep_stmt = 'select id from `' . config::$SQL_PREFIX . 'stories` where storie_token = :storie_token;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['id'];
}

/**
 * gets creator of story for security check
 * @param string $token token of selected story
 * @return string creator of story
 */
function getCreatorByStoryToken($token) {
    $prep_stmt = 'SELECT U.name from `' . config::$SQL_PREFIX . 'stories` as S join `' . config::$SQL_PREFIX . 'user-login` as U on S.user_id = U.id where storie_token = :storie_token;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['name'];
}

/**
 * deletes a story
 * @param int $sid story id
 * @return bool|null state of request
 */
function deleteStory($sid) {
    $stmt = 'Delete FROM  `' . config::$SQL_PREFIX . 'stories` where id = :id ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 'i';
    $params[0]['val'] = $sid;
    $params[0]['nam'] = ":id";
    $x = ExecuteStatementWR($stmt, $params, false);
    return $x;
}

/**
 * sets approval value for a certain story
 * @param string $token unique identifier of story
 * @param bool $value value to set for approval
 * @return bool|null return od result
 */
function changeStoryApproval($token, $value){
    $prep_stmt = 'update `' . config::$SQL_PREFIX . 'stories` SET `approved` = :approved where `storie_token` = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $value;
    $params[0]['nam'] = ":approved";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $token;
    $params[1]['nam'] = ":token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    dump($result, 9);
    return $result;
}

/**
 * gets story approval-state from story with token
 * @param string $token token of selected story
 * @return bool true if story is approved
 */
function getStoryApprovalByStoryToken($token) {
    $prep_stmt = 'select approved from `' . config::$SQL_PREFIX . 'stories` where storie_token = :storie_token;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $token;
    $params[0]['nam'] = ":storie_token";
    dump($params, 8);
    $result = ExecuteStatementWR($prep_stmt, $params);
    dump($result, 9);
    return $result[0]['approved'];
}

/**
 * updates deletion state of story by token
 * @param int $token Identifier of story
 * @param bool $state true if it should be marked as deleted
 * @return array|bool|null result
 */
function updateDeletionStateStoryByToken($token, $state)
{
    $val = $state ? 1 : 0;
    $prep_stmt = 'Update `' . config::$SQL_PREFIX . 'stories` SET `deleted`  = :deleted where `storie_token` = :token ;';
    $params = array();
    $params[0] = array();
    $params[0]['typ'] = 's';
    $params[0]['val'] = $val;
    $params[0]['nam'] = ":deleted";
    $params[1] = array();
    $params[1]['typ'] = 's';
    $params[1]['val'] = $token;
    $params[1]['nam'] = ":token";
    $result = ExecuteStatementWR($prep_stmt, $params, false);
    return $result;
}