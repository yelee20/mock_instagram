<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        /*
         * API No. 1
         * API Name : JWT 생성 테스트 API (로그인)
         * 마지막 수정 날짜 : 20.08.29
         */
        case "createJwt":
            http_response_code(200);

            // 보안에 취약 (userIdx 넣기)
            // 1) 로그인 시 email, password 받기
            $ID = isset($req->ID) ? $req->ID : null;
            $password = isset($req->password) ? $req->password : null;

            if (is_null($ID)) {
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "ID가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            if (is_null($password)) {
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "password가 null입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            if (!isValidUser($ID, $password)) { // JWTPdo.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 404;
                $res->message = "유효하지 않은 아이디 입니다. 회원가입을 해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            // 2) JWT 발급
            // Payload에 맞게 다시 설정 요함, 아래는 Payload에 userIdx를 넣기 위한 과정
            $userIdx = getUserIdxByID($ID);  // JWTPdo.php 에 구현
            $jwt = getJWT($userIdx, JWT_SECRET_KEY); // function.php 에 구현

            $res->result = $jwt;
            $res->isSuccess = TRUE;
            $res->code = 200;
            $res->message = "JWT 발급 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
         * API No. 2
         * API Name : JWT 유효성 검사 테스트 API
         * 마지막 수정 날짜 : 20.08.29
         */
        case "validateJwt":

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            // 1) JWT 유효성 검사
            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다"; 
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            // 2) JWT Payload 반환
            http_response_code(200);
            $res->result = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
