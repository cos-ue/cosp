<?php
/**
 * This file contains all functions called by user's api
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

/**
 * returns preview picture of given unique picture identification
 * @param string $token token of requested picture
 * @return string base64 encoded preview picture
 */
function getPreviewPictureAPI($token)
{
    return getPreviewPicture($token)["preview"];
}

/**
 * returns preview picture of given unique picture identification
 * @param string $token token of requested picture
 */
function getPictureFullsizeAPI($token)
{
    $filename = getFullsizePicture($token)['picurl'];
    $filepath = config::$UPLOAD_DIR . '/' . $filename;
    $fileinfo = mime_content_type($filepath);
    header('Content-Type: ' . $fileinfo);
    echo readfile($filepath);
}

/**
 * generates error for uapi.php
 * @return array structured error state as array
 */
function generateErrorUAPI()
{
    return array(
        "result" => "Wrong Request!",
        "code" => 1
    );
}

/**
 * generates success message with optional definable message
 * @param string $message optional message text as payload
 * @return array structures success state as array
 */
function generateSuccessUAPI($message = "")
{
    if ($message !== "") {
        return array(
            "message" => $message,
            "result" => "ack",
            "code" => 0
        );
    }
    return array(
        "result" => "ack",
        "code" => 0
    );
}

/**
 * generates json for uapi
 * @param array $array structured data, which will be send
 */
function generateJsonUAPI($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
}

/**
 * gets data for single story
 * @param string $token token of requested story
 * @return array structured result as array
 */
function getStoryDataUAPI($token)
{
    $token = explode(";", $token);
    $userStory = buildUserStoryArray($token[0], $token[1], $token[2], $token[3]);
    return array_merge(array('data' => $userStory), generateSuccessUAPI());
}

/**
 * gets all stories in on request
 * @param array $tokens tokens for all requested stories
 * @return array structured result data
 */
function getStoriesDataUAPI($tokens)
{
    $tokens = explode(',', $tokens);
    $stories = array();
    foreach ($tokens as $token) {
        $elements = explode(';', $token);
        $userStory = buildUserStoryArray($elements[0], $elements[1], $elements[2], $elements[3]);
        $stories[] = $userStory;
    }
    return $stories;
}

/**
 * builds array for given story token
 * @param string $token token of story
 * @param string $username username of requesting user
 * @param string $ValidationValue value of validation which user could add
 * @param string $apphashtoken hash of api-token of requesting application
 * @return array result of request as array
 */
function buildUserStoryArray($token, $username, $ValidationValue, $apphashtoken)
{
    $Story = getUserStory($token)[0];
    $result = array(
        'token' => $token,
        'story' => $Story['story'],
        'title' => $Story['title'],
        'name' => $Story['name'],
        'date' => $Story['date'],
        'validatedByUser' => in_array($username, getUservalidationsStory($Story['StoryId'])),
        'validate' => getValidateSumStories($Story['StoryId']) >= 400,
        'valLink' => ($username !== "" && $ValidationValue !== "") ? generateValidatableDataMaterial($token . ";" . $username . ";" . $ValidationValue . ";" . $apphashtoken) : "",
        'approval' => $Story['approved'] === '1',
        'deleted' => $Story['deleted'] == '1',
    );
    if ($username !== 'gast') {
        $result['editable'] = (($username == $Story['name']) || (getRoleByID(getUserData($username)['role'])['value']) >= config::$ROLE_EMPLOYEE);
    } else {
        $result['editable'] = false;
    }
    return $result;
}

/**
 * validates a story
 * @param string $data transmitted data from user as semi-colon separated string
 * @return array if possible success-state as array
 */
function validateStory($data)
{
    $elements = explode(';', $data);
    $storyID = getStoryIdByToken($elements[0]);
    $validated = getUservalidationsStory($storyID);
    if (in_array($elements[1], $validated)) {
        return generateSuccessUAPI("Already validated by this user.");
    }
    $result = insertValidateStories($storyID, $elements[2], $elements[1]);
    $apitokens = getAllApiTokens();
    foreach ($apitokens as $api) {
        $hash = hashString($api['token']);
        if (hash_equals($hash, $elements[3])) {
            addRankPoints($elements[1], $api['token'], "Validated a Story.", 2);
            addRankPoints(getUserStory($elements[0])[0]['name'], $api['token'], "Story was validated.", 10);
        }
    }
    dump($result, 9);
    return generateSuccessUAPI();
}

/**
 * validates a picture
 * @param string $data transmitted data from user as semi-colon separated string
 * @return array possible success-state as array
 */
function validatePicture($data)
{
    $elements = explode(';', $data);
    $storyID = getPictureIdByToken($elements[0]);
    $validated = getUservalidationsPictures($storyID);
    if (in_array($elements[1], $validated)) {
        return generateSuccessUAPI("Already validated by this user.");
    }
    $result = insertValidatePictures($storyID, $elements[2], $elements[1]);
    dump($result, 9);
    $apitokens = getAllApiTokens();
    foreach ($apitokens as $api) {
        $hash = hashString($api['token']);
        if (hash_equals($hash, $elements[3])) {
            addRankPoints($elements[1], $api['token'], "Validated a Picture.", 2);
            addRankPoints(getPictureUploadingUserByToken($elements[0]), $api['token'], "Picture was validated.", 10);
        }
    }
    return generateSuccessUAPI();
}