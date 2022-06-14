<?php
/**
 * This file contains function wrapper around delete functions, to first only mark things as deleted
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * sets art of deletion depending of config for link between poi and pic
 * @param int $apiToken identifier of api
 * @param string $token identifier of picture
 * @param bool $overwrite force direct delete
 */
function deletePicByTokenDBWrap($apiToken, $token, $overwrite = false){
    $LinkID = getSinglePictureFromDB($apiToken, $token)['id'];
    deletePictureReferences($token, $overwrite);
    if (config::$DIRECT_DELETE || $overwrite){
        $filename = getFullsizePicture($token)['picurl'];
        $filepath = config::$UPLOAD_DIR . '/' . $filename;
        unlink($filepath);
        deleteValidatePictures($LinkID);
        deletePicture($LinkID);
        return;
    }
    updateDeletionStatePictureByToken($LinkID, true);
}

/**
 * sets art of deletion of story
 * @param string $story_token identifier of story
 * @param bool $overwrite sets overwrite to standard place
 */
function deleteStoryByTokenDBWrap($story_token, $overwrite = false){
    deleteStoryReference($story_token, $overwrite);
    if (config::$DIRECT_DELETE || $overwrite){
        $id = getStoryIdByStoryToken($story_token);
        deleteStoryValidates($id);
        deleteStory($id);
        return;
    }
    updateDeletionStateStoryByToken($story_token, true);
}