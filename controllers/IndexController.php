 <?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;

        /*
         * API No. 1
         * API Name : 회원가입 API
         * 마지막 수정 날짜 : 20.12.20
         */
        case "createUser":
            http_response_code(200);

            $userID = isset($req->userID) ? $req->userID : null;
            $userName = isset($req->userName) ? $req->userName : null;
            $profileImageUrl = isset($req->profileImageUrl) ? $req->profileImageUrl : null;
            $bio = isset($req->bio) ? $req->bio : null;
            $isPublic = isset($req->isPublic) ? $req->isPublic : 'Y';
            $userType = isset($req->userType) ? $req->userType : 'I';
            $personalContact = isset($req->personalContact) ? $req->personalContact : null;
            $email = isset($req->email) ? $req->email : null;
            $password = isset($req->password) ? $req->password : null;
            $pwd_hash = isset($req->password) ? password_hash($req->password,PASSWORD_DEFAULT) : null;
            $gender = isset($req->gender) ? $req->gender : null;
            $birthday = isset($req->birthday) ? $req->birthday : null;

            // 사용자 아이디 Validation
            if(is_null($userID)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userID가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!preg_match('/[0-9a-zA-Z-]+(._[_0-9a-zA-Z]+)*[0-9a-zA-Z-]+/', $userID)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 userID입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isValidUserID($userID)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "이미 존재하는 userID입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 연락처 Validation

            if(is_null($personalContact) and is_null($email)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "personalContact가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!preg_match("/^01([016789]?)-?([0-9]{3,4})-?([0-9]{4})$/",$personalContact)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 personalContact 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!preg_match("/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/",$personalContact)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 email 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isValidContact($personalContact)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "이미 존재하는 personalContact입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isValidEmail($email)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "이미 존재하는 email입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 비밀번호 Validation
            if(is_null($password)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(strlen($password) < 6)
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password는 6자리 이상이어야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(preg_match("/\s/u", $password) == true)
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password는 공백없이 입력해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if($gender != null and $gender != 'C' and $gender != 'F' and $gender != 'M'and $gender != 'N')
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 gender입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 생일 Validation
            if(is_null($birthday)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "birthday가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(preg_match('/^(\d{4})-(\d{2})-(\d{2})$/',$birthday,$match) && checkdate($match[2],$match[3],$match[1]) == true)
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 birthday입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $today = (date('Y-m-d'));
            $dateDifference = strtotime($today) - strtotime($birthday);
            $years  = floor($dateDifference / (365 * 60 * 60 * 24));

            if($years < 14){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "만 14세 미만은 계정을 만들 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createUser($userID, $userName, $profileImageUrl, $bio, $isPublic, $userType, $personalContact, $pwd_hash, $gender, $birthday);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "사용자 회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 2
         * API Name : 사용자 정보 수정 API
         * 마지막 수정 날짜 : 20.11.13
         */
        case "updateUserInfo":
            http_response_code(200);

            $userID = isset($req->userID) ? $req->userID : null;
            $userName = isset($req->userName) ? $req->userName : null;
            $profileImageUrl = isset($req->profileImageUrl) ? $req->profileImageUrl : null;
            $bio = isset($req->bio) ? $req->bio : null;
            $isPublic = isset($req->isPublic) ? $req->isPublic : 'Y';
            $userType = isset($req->userType) ? $req->userType : 'I';
            $personalContact = isset($req->personalContact) ? $req->personalContact : null;
            $password = isset($req->password) ? $req->password : null;
            $pwd_hash = isset($req->password) ? password_hash($req->password,PASSWORD_DEFAULT) : null;
            $gender = isset($req->gender) ? $req->gender : null;
            $birthday = isset($req->birthday) ? $req->birthday : null;

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT Validation
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 아이디 Validation
            if(is_null($userID)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userID가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!preg_match('/[0-9a-zA-Z-]+(._[_0-9a-zA-Z]+)*[0-9a-zA-Z-]+/', $userID)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 userID 형식입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isDuplicateUserID($userIdx, $userID)){
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "중복된 userID 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 연락처 Validation

            if(is_null($personalContact)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "personalContact가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!preg_match("/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/",$personalContact)
                AND !preg_match("/^01([016789]?)-?([0-9]{3,4})-?([0-9]{4})$/",$personalContact)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 personalContact 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isDuplicateContact($userIdx, $personalContact)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "이미 존재하는 personalContact입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            // 사용자 비밀번호 Validation
            if(is_null($password)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(strlen($password) < 6)
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password는 6자리 이상이어야 합니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(preg_match("/\s/u", $password) == true)
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password는 공백없이 입력해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if($gender != null and $gender != 'C' and $gender != 'F' and $gender != 'M'and $gender != 'N')
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 gender입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 생일 Validation
            if(is_null($birthday)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "birthday가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!(preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/',$birthday,$match) && checkdate($match[2],$match[3],$match[1])))
            {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 birthday입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $today = (date('Y-m-d'));
            $dateDifference = strtotime($today) - strtotime($birthday);
            $years  = floor($dateDifference / (365 * 60 * 60 * 24));

            if($years < 14){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "만 14세 미만은 계정을 가질 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            updateUserInfo($userID, $userName, $profileImageUrl, $bio, $isPublic, $userType,$personalContact, $pwd_hash, $gender, $birthday, $userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "사용자 정보 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 3
         * API Name : 회원 탈퇴 API
         * 마지막 수정 날짜 : 20.11.02
         */
        case "deleteUser":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT Validation
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteUser($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "회원 탈퇴 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
     * API No. 1
     * API Name : 로그인 API
     * 마지막 수정 날짜 : 20.11.15
     */
        case "login":
            http_response_code(200);

            $userID = isset($req->userID) ? $req->userID : null;
            $personalContact = isset($req->personalContact) ? $req->personalContact : null;
            $password = isset($req->password) ? $req->password : null;

            // 사용자 아이디 Validation
            if(is_null($userID)) {
                if(is_null($personalContact)){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "userID, personalContact가 null입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }

            if(!is_null($userID) and !is_null($personalContact)){
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userID, personalContact 중 하나만 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자 비밀번호 Validation
            if(is_null($password)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "password가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!login($userID, $personalContact, $password)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 아이디입니다. 회원가입을 해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            };
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 1
        * API Name : 로그아웃 API
        * 마지막 수정 날짜 : 20.12.22
        */
        case "logout":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 아이디 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidUserIdx($userIdx)) {
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "로그아웃 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 5
         * API Name : 사용자 프로필 조희
         * 마지막 수정 날짜 : 20.10.14
         */
        case "getUserProfile":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $targetUserIdx = $vars['targetUserIdx'];
            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(userBlocked($userIdx, $targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "접근 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($userIdx == $targetUserIdx){
                $res->result = getMyProfile($userIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "내 프로필 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getUserProfile($userIdx,$targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "사용자 프로필 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
     * API No.
     * API Name : 게시글 등록
     * 마지막 수정 날짜 : 20.12.20
     */
        case "createPost":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postCaption = isset($req->postCaption) ? $req->postCaption : null;
            $location = isset($req->location) ? $req->location : null;
            $imageUrl = isset($req->imageUrl) ? $req->imageUrl : null;
            $videoUrl = isset($req->videoUrl) ? $req->videoUrl : null;
            $length = isset($req->length) ? $req->length : null;
            $isCommentEnabled = isset($req->isCommentEnabled) ? $req->isCommentEnabled : 'Y';

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 이미지, 동영상 Validation
            if(is_null($imageUrl)){
                // Url이 없을때
                if(is_null($videoUrl)){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "post Source가 null입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                // 동영상만 있는 게시글
                for ($x=0; $x < sizeof($videoUrl); $x++){
                    if(is_null($length[$x])){
                        $res->isSuccess = False;
                        $res->code = 400;
                        $res->message = "video length가 null입니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break 2;
                    }
                    if(sizeof($videoUrl)>10){
                        $res->isSuccess = False;
                        $res->code = 409;
                        $res->message = "video는 최대 9개까지 업로드 할 수 있습니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break 2;
                    }
                    if(sizeof($videoUrl)!=sizeof($length)){
                        $res->isSuccess = False;
                        $res->code = 400;
                        $res->message = "video 개수와  video length 개수가 맞지 않습니다";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break 2;
                    }
                    if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$videoUrl[$x])) {
                        $res->isSuccess = False;
                        $res->code = 400;
                        $res->message = "잘못된 형식의 videoUrl입니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break 2;
                    }

                    if(!preg_match("/^(([0-9]{2})\:([0-9]{2})\:([0-9]{2}))$/",$length[$x])) {
                        $res->isSuccess = False;
                        $res->code = 400;
                        $res->message = "잘못된 형식의 video length입니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break 2;
                    }
                }
                $postIdx = createPost($userIdx,$postCaption, $location, $isCommentEnabled);

                for ($x=0; $x < sizeof($videoUrl); $x++){
                    insertPostVideo($postIdx,$videoUrl[$x],$length[$x]);
                }

                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "게시글 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

            // 사진만 있는 게시글 Validation
            if(is_null($videoUrl)){
                for ($x=0; $x < sizeof($imageUrl); $x++)
                {
                if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$imageUrl[$x])) {
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "잘못된 형식의 imageUrl입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                    }
                }

                $postIdx = createPost($userIdx,$postCaption, $location, $isCommentEnabled);

                for ($x=0; $x < sizeof($imageUrl); $x++){
                    insertPostImage($postIdx,$imageUrl[$x]);
                }
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "게시글 등록 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

            // 사진, 동영상이 모두 있는 게시글
            for ($x=0; $x < sizeof($imageUrl); $x++){
                if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$imageUrl[$x])) {
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "잘못된 형식의 imageUrl입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
            }

            for ($x=0; $x < sizeof($videoUrl); $x++){
                if(is_null($length[$x])){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "video length가 null입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
                if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$videoUrl[$x])) {
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "잘못된 형식의 videoUrl입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }

                if(!preg_match("/^(([0-9]{2})\:([0-9]{2})\:([0-9]{2}))$/",$length[$x])){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "잘못된 형식의 video length입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }

                if(sizeof($videoUrl)!=sizeof($length)){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "video 개수와  video length 개수가 맞지 않습니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
             }

            $postIdx = createPost($userIdx,$postCaption, $location, $isCommentEnabled);
            for ($x=0; $x < sizeof($imageUrl); $x++){
                insertPostImage($postIdx,$imageUrl[$x]);
            }
            for ($x=0; $x < sizeof($videoUrl); $x++){
                insertPostVideo($postIdx,$videoUrl[$x],$length[$x]);
            }

            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "게시글 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No.
         * API Name : 게시글 수정
         * 마지막 수정 날짜 : 20.12.19
         */
        case "updatePost":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = $vars['postIdx'];
            $postCaption = isset($req->postCaption) ? $req->postCaption : null;
            $location = isset($req->location) ? $req->location : null;
            $isCommentEnabled = isset($req->isCommentEnabled) ? $req->isCommentEnabled : 'Y';

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 게시글 인덱스 Validation
            if(is_null($postIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "postIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidPostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 postIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isPostOwner($userIdx, $postIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "수정 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            updatePost($postCaption, $location, $isCommentEnabled, $postIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "게시글 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
       * API No.
       * API Name : 게시글 삭제
       * 마지막 수정 날짜 : 20.12.19
       */
        case "deletePost":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = $vars['postIdx'];
            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 게시글 인덱스 Validation
            if(is_null($postIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "postIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidPostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 postIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isPostOwner($userIdx, $postIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "삭제 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deletePost($userIdx, $postIdx);
            deletePostImage($postIdx);
            deletePostVideo($postIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "게시글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No.
         * API Name : 팔로우 신청 / 취소
         * 마지막 수정 날짜 : 20.11.05
         */
        case "createFollow":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $targetUserIdx = isset($req->targetUserIdx) ? $req->targetUserIdx : null;
            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 팔로우 신청한 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 팔로우 신청 받은 사용자 인덱스 Validation
            if(is_null($targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "targetUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(isNotAccepted($userIdx, $targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "이미 팔로우 신청한 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(userBlocked($userIdx, $targetUserIdx) or $userIdx == $targetUserIdx) {
                $res->isSuccess = False;
                $res->code = 405;
                $res->message = "팔로우 신청 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(isFollowedExists($userIdx, $targetUserIdx)){
                if(isFollowed($userIdx, $targetUserIdx)){
                    deleteFollow($userIdx, $targetUserIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "팔로우 취소 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }

                if(!isFollowed($userIdx, $targetUserIdx)){
                    followAgain($userIdx, $targetUserIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "팔로우 신청 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }
            }

            if(!isFollowedExists($userIdx, $targetUserIdx)){
                createFollow($userIdx, $targetUserIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "팔로우 신청 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

        /*
    * API No.
    * API Name : 팔로워 목록 조회
    * 마지막 수정 날짜 : 20.11.1
    */

        case "getFollowerList":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 타겟 사용자 인덱스 Validation
            $targetUserIdx = $vars['targetUserIdx'];
            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 차단 여부
            if(userBlocked($userIdx, $targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 405;
                $res->message = "접근 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            $res->result = getFollowerList($targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "팔로워 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No.
        * API Name : 팔로잉 목록 조회
        * 마지막 수정 날짜 : 20.11.1
        */

        case "getFollowingList":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 타겟 사용자 인덱스 Validation
            $targetUserIdx = $vars['targetUserIdx'];
            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 차단 여부
            if(userBlocked($userIdx, $targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 405;
                $res->message = "접근 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getFollowingList($targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "팔로잉 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 7
         * API Name : 사용자 게시글 목록 조회
         * 마지막 수정 날짜 : 20.12.19
         */

        case "getUserPosts":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $targetUserIdx = $vars['targetUserIdx'];
            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(userBlocked($userIdx, $targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "접근 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isPublicAccount($targetUserIdx) AND !isFollowed($userIdx,$targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "비공개 계정입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getUserPosts($targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "사용자 게시글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No.
         * API Name : 게시글 좋아요 및 좋아요 취소
         * 마지막 수정 날짜 : 20.12.19
         */
        case "likePost":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = isset($req->postIdx) ? $req->postIdx : null;

            // 게시글 좋아한 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 좋아한 게시글 인덱스 Validation

            if(is_null($postIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "postIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidpostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 postIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(isLikedPostExists($userIdx, $postIdx)){
                if(isLikedPost($userIdx, $postIdx)){
                    deletePostLike($userIdx, $postIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "게시글 좋아요 취소 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }

                if(!isLikedPost($userIdx, $postIdx)){
                    likePostAgain($userIdx, $postIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "게시글 좋아요 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }
            }

            if(!isLikedPostExists($userIdx, $postIdx)){
                likePost($userIdx, $postIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "게시글 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

        /*
        * API No.
        * API Name : 좋아한 게시글 목록 조회
        * 마지막 수정 날짜 : 20.12.19
        */

        case "getLikedPosts":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 200;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getLikedPosts($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "좋아한 게시글 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 댓글 좋아요 / 좋아요 취소
        * 마지막 수정 날짜 : 20.12.19
        */
        case "likeComment":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $commentIdx = isset($req->commentIdx) ? $req->commentIdx : null;

            // 게시글 좋아한 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 좋아한 댓글 인덱스 Validation

            if(is_null($commentIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "commentIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidCommentIdx($commentIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 commentIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if(isLikedCommentExists($userIdx, $commentIdx)){
                if(isLikedComment($userIdx, $commentIdx)){
                    deleteCommentLike($userIdx, $commentIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "댓글 좋아요 취소 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }

                if(!isLikedComment($userIdx, $commentIdx)){
                    likeCommentAgain($userIdx, $commentIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "댓글 좋아요 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }
            }

            if(!isLikedCommentExists($userIdx, $commentIdx)){
                likeComment($userIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "댓글 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
        /*
    * API No.
    * API Name : 스토리 등록
    * 마지막 수정 날짜 : 20.12.20
    */
        case "createStory":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $imageUrl = isset($req->imageUrl) ? $req->imageUrl : null;
            $videoUrl = isset($req->videoUrl) ? $req->videoUrl : null;
            $closeFriendsOnly = isset($req->closeFriendsOnly) ? $req->closeFriendsOnly : "N";

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // closeFriendsOnly
            if($closeFriendsOnly != 'N' and $closeFriendsOnly != 'Y'){
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 closeFriendsOnly입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(is_null($imageUrl)){
                if(is_null($videoUrl)){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "storySource가 null입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$videoUrl)) {
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "잘못된 형식의 url 입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                    $storyIdx = createStory($userIdx,$closeFriendsOnly);
                    insertStoryVideo($storyIdx,$videoUrl);
                    $res->isSuccess = TRUE;
                    $res->code = 200;
                    $res->message = "스토리 등록 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

            }
            if (!is_null($imageUrl)){
                if(is_null($videoUrl)){
                    if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$imageUrl)) {
                        $res->isSuccess = False;
                        $res->code = 400;
                        $res->message = "잘못된 형식의 url 입니다.";
                        echo json_encode($res, JSON_NUMERIC_CHECK);
                        break;
                    }
                    $storyIdx = createStory($userIdx,$closeFriendsOnly);
                    insertStoryImage($storyIdx,$imageUrl);
                    $res->isSuccess = TRUE;
                    $res->code = 200;
                    $res->message = "스토리 등록 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }

                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "사진과 동영상을 동시에 게시할 수 없습니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;


            }


        /*
        * API No.
        * API Name : 스토리 목록 조회
        * 마지막 수정 날짜 : 20.12.20
        */

        case "getStoryToday":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result = getStoryToday($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스토리 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No.
        * API Name : 스토리 삭제
        * 마지막 수정 날짜 : 20.12.22
        */
        case "deleteStory":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $storyIdx = $vars['storyIdx'];

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 스토리 인덱스 Validation
            if(is_null($storyIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "storyIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidStoryIdx($storyIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 storyIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isStoryOwner($userIdx, $storyIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "삭제 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isUploadedIn48Hrs($storyIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "48시간 이전에 업로드 된 스토리는 삭제할 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteStory($storyIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스토리 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
           * API No.
           * API Name : 스토리 하이라이트 등록
           * 마지막 수정 날짜 : 20.12.21
           */
        case "createStoryHighlights":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $storyIdx = isset($req->storyIdx) ? $req->storyIdx : null;
            $highlightImageUrl = isset($req->highlightImageUrl) ? $req->highlightImageUrl : null;
            $highlightTitle = isset($req->highlightTitle) ? $req->highlightTitle : null;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 스토리 인덱스 Validation
            if(is_null($storyIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "storyIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            for ($x=0; $x < sizeof($storyIdx); $x++) {
                if (!isValidStoryIdx($storyIdx[$x])) {
                    $res->isSuccess = False;
                    $res->code = 404;
                    $res->message = "유효하지 않은 storyIdx 입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
                if (!isStoryOwner($userIdx, $storyIdx[$x])){
                    $res->isSuccess = False;
                    $res->code = 401;
                    $res->message = "스토리 하이라이트 등록 권한이 없습니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
            }

            if(is_null($highlightImageUrl)){
                    $res->isSuccess = False;
                    $res->code = 400;
                    $res->message = "highlightImageUrl이 null입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
            }

            if(!preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/",$highlightImageUrl)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 url 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
                }

            if(is_null($highlightTitle)){
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "highlightTitle이 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            // 하이라이트 인덱스 받기
            $highlightIdx = createStoryHighlights($highlightImageUrl,$highlightTitle);

            // 스토리 인덱스를 하이라이트 인덱스와 연결
            for ($x=0; $x < sizeof($storyIdx); $x++){
                connectHighlights($highlightIdx,$storyIdx[$x]);
            }

            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스토리 하이라이트 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 스토리 하이라이트 수정 (미완)
        * 마지막 수정 날짜 : 20.12.21
        */
        case "updateStoryHighlights":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $storyIdx = isset($req->storyIdx) ? $req->storyIdx : null;
            $highlightIdx = isset($req->highlightIdx) ? $req->highlightIdx : null;
            $highlightImageUrl = isset($req->highlightImageUrl) ? $req->highlightImageUrl : null;
            $highlightTitle = isset($req->highlightTitle) ? $req->highlightTitle : null;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(is_null($storyIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "storyIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 스토리 하이라이트 인덱스 Validation
            if(is_null($highlightIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "highlightIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!is_integer($highlightIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "잘못된 형식의 highlightIdx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHighlightIdx($highlightIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 highlightIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isHighlightOwner($userIdx, $highlightIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "수정 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            for($x=0; $x < sizeof($storyIdx); $x++){
                // 스토리 인덱스 Validation
                if (!isValidStoryIdx($storyIdx[$x])){
                    $res->isSuccess = False;
                    $res->code = 404;
                    $res->message = "유효하지 않은 storyIdx 입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }
                if (!isStoryOwner($userIdx,$storyIdx[$x])){
                    $res->isSuccess = False;
                    $res->code = 401;
                    $res->message = "추가 권한이 없습니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break 2;
                }

            }
            deleteHighlightedStories($highlightIdx);
            for($x=0; $x < sizeof($storyIdx); $x++){
                if (!isStoryInHighlight($storyIdx[$x],$highlightIdx)){
                    insertStory($highlightIdx, $storyIdx[$x]);
                }
                updateStory($highlightIdx, $storyIdx[$x]);
            }

            updateStoryHighlights($highlightImageUrl, $highlightTitle,$highlightIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스토리 하이라이트 수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 스토리 하이라이트 삭제
        * 마지막 수정 날짜 : 20.12.21
        */
        case "deleteStoryHighlights":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $highlightIdx = $vars['highlightIdx'];

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 스토리 하이라이트 인덱스 Validation
            if(is_null($highlightIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "highlightIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidHighlightIdx($highlightIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 highlightIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if (!isHighlightOwner($userIdx, $highlightIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "삭제 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteStoryHighlights($highlightIdx);
            deleteHighlightedStories($highlightIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "스토리 하이라이트 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 8
        * API Name : 사용자 스토리 하이라이트 조회
        * 마지막 수정 날짜 : 20.10.14
        */
        case "getStoryHighlights":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $targetUserIdx = $vars['targetUserIdx'];
            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(userBlocked($userIdx, $targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "접근 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 타겟 사용자 인덱스 Validation
            $res->result = getStoryHighlights($targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "사용자 스토리 하이라이트 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

            /*
             * API No. 9 (미완)
             * API Name : 피드 게시글 조회
             * 마지막 수정 날짜 : 20.12.19
             */
        case "getFeedPosts":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getFeedPosts($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "피드 게시글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 댓글 등록
        * 마지막 수정 날짜 : 20.12.19
        */
        case "createComment":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = isset($req->postIdx) ? $req->postIdx : null;
            $commentContent = isset($req->commentContent) ? $req->commentContent : null;
            $parentCommentIdx = isset($req->parentCommentIdx) ? $req->parentCommentIdx : -1;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 421;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 게시글 인덱스 Validation
            if(is_null($postIdx)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "postIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidPostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 421;
                $res->message = "유효하지 않은 postIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isCommentEnabled($postIdx)){
                $res->isSuccess = False;
                $res->code = 421;
                $res->message = "댓글 기능이 해제된 게시글입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 댓글 내용 Validation
            if(is_null($commentContent)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "commentContent가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 부모 댓글 인덱스 Validation

            if(!isValidCommentIdx($parentCommentIdx) and $parentCommentIdx != -1) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "유효하지 않은 parentCommentIdx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createComment($userIdx, $postIdx, $commentContent, $parentCommentIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "댓글 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No.
         * API Name : 댓글 조회
         * 마지막 수정 날짜 : 20.12.22
         */
        case "getComments":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = $vars['postIdx'];

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 게시글 인덱스 Validation
            if (!isValidPostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 postIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getComments($userIdx, $postIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "댓글 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No.
        * API Name : 댓글 삭제
        * 마지막 수정 날짜 : 20.12.19
        */
        case "deleteComment":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $commentIdx = $vars['commentIdx'];

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isCommentOwner($userIdx, $commentIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "삭제 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 댓글 인덱스 Validation
            if(is_null($commentIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "commentIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidCommentIdx($commentIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 commentIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteComment($commentIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "댓글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 친한 친구 등록
        * 마지막 수정 날짜 : 20.12.19
        */
        case "createCloseFriend":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $targetUserIdx = isset($req->targetUserIdx) ? $req->targetUserIdx : null;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 친한 친구로 설정된 사용자 인덱스 Validation

            if(is_null($targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "targetUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if ($userIdx == $targetUserIdx){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "나를 CloseFriend로 설정할 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isFollowedByUser($userIdx, $targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "내가 팔로우 하고 있는 사용자만 CloseFriend로 설정할 수 있습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (isCloseFriend($userIdx, $targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "이미 CloseFriend입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            createCloseFriend($userIdx, $targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "친한 친구 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
        * API No.
        * API Name : 친한 친구 해제
        * 마지막 수정 날짜 : 20.12.20
        */
        case "deleteCloseFriend":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $targetUserIdx = $vars['targetUserIdx'];

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 친한 친구로 설정된 사용자 인덱스 Validation

            if(is_null($targetUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "targetUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 targetUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isCloseFriend($userIdx, $targetUserIdx)){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "CloseFriend로 설정되지 않은 사용자입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteCloseFriend($userIdx, $targetUserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "친한 친구 해제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 10
        * API Name : 친한 친구 목록 조회
        * 마지막 수정 날짜 : 20.12.20
        */

        case "getCloseFriends":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getCloseFriends($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "친한친구 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



        /*
        * API No.
        * API Name : 게시글 저장 및 저장 해제
        * 마지막 수정 날짜 : 20.12.20
        */
        case "savePost":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $postIdx = isset($req->postIdx) ? $req->postIdx : null;

            // 저장한 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 저장된 게시글 인덱스 Validation
            if(is_null($postIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "postIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidPostIdx($postIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 postIdx입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isSavedExists($userIdx, $postIdx)){
                if(isSaved($userIdx, $postIdx)){
                    deleteSavedPost($userIdx, $postIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "게시글 저장 해제 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }

                if(!isSaved($userIdx, $postIdx)){
                    savePostAgain($userIdx, $postIdx);
                    $res->isSuccess = True;
                    $res->code = 200;
                    $res->message = "게시글 저장 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

                }
            }

            if(!isSavedExists($userIdx, $postIdx)){
                savePost($userIdx, $postIdx);
                $res->isSuccess = TRUE;
                $res->code = 200;
                $res->message = "게시글 저장 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No.
        * API Name : DM 보내기
        * 마지막 수정 날짜 : 20.12.21
        */
        case "createDM":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $sendUserIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $roomIdx = isset($req->roomIdx) ? $req->roomIdx : null;
            $receivedUserIdx = isset($req->receivedUserIdx) ? $req->receivedUserIdx : null;
            $messageType = isset($req->messageType) ? $req->messageType : null;
            $messageContent = isset($req->messageContent) ? $req->messageContent : null;

            // 채팅방 인덱스 Validation
            // 있으면 기존 roomIdx를 넣고, 없으면 새로 생성
            if(is_null($roomIdx)){
                if(isValidConversation($sendUserIdx,$receivedUserIdx)){
                    $roomIdx = getRoomIdx($sendUserIdx,$receivedUserIdx);

                }
                if(!isValidConversation($sendUserIdx,$receivedUserIdx)){
                    $roomIdx = getNewRoomIdx();
                }

            }

            // DM 보낸 사용자 인덱스 Validation
            if(is_null($sendUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "sendUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($sendUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 sendUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // DM 받은 사용자 인덱스 Validation
            if(is_null($receivedUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "receivedUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($receivedUserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 receivedUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 메세지 종류 Validation
            if(is_null($messageType)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "messageType이 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if(!isValidMessageType($messageType)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "잘못된 messageType입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 메세지 내용 Validation
            if(is_null($messageContent)) {
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "messageContent가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            readDM($sendUserIdx, $roomIdx);
            createDM($roomIdx, $sendUserIdx, $receivedUserIdx, $messageType, $messageContent);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "DM 보내기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : DM 삭제
        * 마지막 수정 날짜 : 20.12.22
        */
        case "deleteDM":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $roomIdx = $vars['roomIdx'];
            $messageIdx = $vars['messageIdx'];

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 채팅방 인덱스 Validation
            if(is_null($roomIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "roomIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidRoomIdx($roomIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 roomIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            // 메세지 인덱스 Validation
            if(is_null($messageIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "messageIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidMessageIdx($roomIdx, $messageIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 messageIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if (!isMessageOwner($userIdx,$roomIdx,$messageIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "삭제 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteDM($userIdx, $roomIdx, $messageIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "DM 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
        * API No.
        * API Name : DM 확인
        * 마지막 수정 날짜 : 20.12.21
        */
        case "readDM":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;
            $roomIdx = isset($req->roomIdx) ? $req->roomIdx : null;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 채팅방 인덱스 Validation
            if(is_null($roomIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "roomIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidRoomIdx($roomIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 roomIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 사용자가 채팅방안에 있는지 확인
            if (!isAllowedUser($userIdx,$roomIdx)){
                $res->isSuccess = False;
                $res->code = 401;
                $res->message = "확인 권한이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            readDM($userIdx, $roomIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "DM 확인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 10
        * API Name : 읽지 않은 DM 개수 조회
        * 마지막 수정 날짜 : 20.12.20
        */

        case "getDMNum":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getDMNum($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "읽지 않은 DM 개수 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No.
        * API Name : 저장된 게시글 목록 조회
        * 마지막 수정 날짜 : 20.12.20
        */

        case "getSavedPosts":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdx = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            // 사용자 인덱스 Validation
            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 200;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = getSavedPosts($userIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "저장된 게시글 목록 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No.
        * API Name : 사용자 차단
        * 마지막 수정 날짜 : 20.11.02
        */
        case "blockUser":
            http_response_code(200);
            $userIdx = isset($req->userIdx) ? $req->userIdx : null;
            $blockedUserIdx = isset($req->blockedUserIdx) ? $req->blockedUserIdx : null;

            // 사용자 인덱스 Validation
            if(is_null($userIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "userIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($userIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 userIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            // 친한 친구로 설정된 사용자 인덱스 Validation

            if(is_null($blockedUserIdx)) {
                $res->isSuccess = False;
                $res->code = 400;
                $res->message = "blockedUserIdx가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidUserIdx($followeduserIdx)){
                $res->isSuccess = False;
                $res->code = 404;
                $res->message = "유효하지 않은 blockedUserIdx 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isCloseFriend($userIdx, $followeduserIdx)){
                $res->isSuccess = False;
                $res->code = 409;
                $res->message = "CloseFriend로 설정되지 않은 사용자입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result = deleteCloseFriend($userIdx, $followeduserIdx);
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "친한 친구 해제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
