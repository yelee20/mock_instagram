<?php
require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/JWTPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

header('Access-Control-Allow-Origin: *');

//에러출력하게 하는 코드
error_reporting(E_ALL);
ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   JWT   ****************** */
    $r->addRoute('POST', '/jwt', ['JWTController', 'createJwt']);   // JWT 생성: 로그인
    $r->addRoute('GET', '/jwt', ['JWTController', 'validateJwt']);  // JWT 유효성 검사

    /* ******************   UserInfo   ****************** */
    $r->addRoute('POST', '/users', ['IndexController', 'createUser']); // 회원가입
    $r->addRoute('PATCH', '/users', ['IndexController', 'updateUserInfo']); // 사용자 정보 수정
    $r->addRoute('DELETE', '/users', ['IndexController', 'deleteUser']); // 회원 탈퇴
    $r->addRoute('POST', '/login', ['IndexController', 'login']); // 로그인
    $r->addRoute('POST', '/logout', ['IndexController', 'logout']); // 로그아웃
    $r->addRoute('GET', '/users/{targetUserIdx}', ['IndexController', 'getUserProfile']); // 사용자 프로필 조회

    /* ******************   Follow   ****************** */
    $r->addRoute('PATCH', '/follow', ['IndexController', 'createFollow']); // 팔로우 신청 / 취소
    $r->addRoute('GET', '/follower/{targetUserIdx}', ['IndexController', 'getFollowerList']); // 팔로워 목록 조회
    $r->addRoute('GET', '/following/{targetUserIdx}', ['IndexController', 'getFollowingList']); // 팔로잉 목록 조회

    /* ******************   Post   ****************** */
    $r->addRoute('POST', '/posts', ['IndexController', 'createPost']); // 게시글 등록
    $r->addRoute('PATCH', '/posts/{postIdx}', ['IndexController', 'updatePost']); // 게시글 수정
    $r->addRoute('DELETE', '/posts/{postIdx}', ['IndexController', 'deletePost']); // 게시글 삭제
    $r->addRoute('GET', '/feed', ['IndexController', 'getFeedPosts']); //피드에 게시글 조회 (미완)
    $r->addRoute('GET', '/posts/{targetUserIdx}', ['IndexController', 'getUserPosts']); // 사용자 게시글 목록 조회

    /* ******************   Comment   ****************** */
    $r->addRoute('POST', '/comments', ['IndexController', 'createComment']); // 댓글 등록
    $r->addRoute('GET', '/comments/{postIdx}', ['IndexController', 'getComments']); // 댓글 조회
    $r->addRoute('DELETE', '/comments/{commentIdx}', ['IndexController', 'deleteComment']); // 댓글 삭제

    /* ******************   Like   ****************** */
    $r->addRoute('PATCH', '/like-post', ['IndexController', 'likePost']); // 게시글 좋아요 등록
    $r->addRoute('GET', '/like-post', ['IndexController', 'getLikedPosts']); // 좋아한 게시글 목록 조회
    $r->addRoute('PATCH', '/like-comment', ['IndexController', 'likeComment']); // 댓글 좋아요 등록 및 취소

    /* ******************   Story   ****************** */
    $r->addRoute('POST', '/story', ['IndexController', 'createStory']); // 스토리 등록
    $r->addRoute('GET', '/story', ['IndexController', 'getStoryToday']); // 스토리 목록 조회
    $r->addRoute('DELETE', '/story/{storyIdx}', ['IndexController', 'deleteStory']); // 스토리 삭제
    $r->addRoute('POST', '/story-highlights', ['IndexController', 'createStoryHighlights']); // 스토리 하이라이트 등록
    $r->addRoute('PATCH', '/story-highlights', ['IndexController', 'updateStoryHighlights']); // 스토리 하이라이트 수정
    $r->addRoute('DELETE', '/story-highlights/{highlightIdx}', ['IndexController', 'deleteStoryHighlights']); // 스토리 하이라이트 삭제
    $r->addRoute('GET', '/story-highlights/{targetUserIdx}', ['IndexController', 'getStoryHighlights']); // 사용자 스토리 하이라이트 조회

    /* ******************   DM   ****************** */
    $r->addRoute('POST', '/DM', ['IndexController', 'createDM']); // DM 보내기
    $r->addRoute('DELETE', '/DM/{roomIdx}/{messageIdx}', ['IndexController', 'deleteDM']); // DM 삭제
    $r->addRoute('PATCH', '/DM', ['IndexController', 'readDM']); // DM 확인
    $r->addRoute('GET', '/DM', ['IndexController', 'getDMNum']); // 읽지않은 DM 개수 조회

    /* ******************   Close Friends   ****************** */
    $r->addRoute('POST', '/close-friends', ['IndexController', 'createCloseFriend']); // 친한친구 등록
    $r->addRoute('DELETE', '/close-friends/{targetUserIdx}', ['IndexController', 'deleteCloseFriend']); // 친한친구 해제
    $r->addRoute('GET', '/close-friends', ['IndexController', 'getCloseFriends']); // 친한친구 리스트 조회

    /* ******************   Save posts   ****************** */
    $r->addRoute('PATCH', '/saved-posts', ['IndexController', 'savePost']); // 게시글 저장 및 저장 해제
    $r->addRoute('GET', '/saved-posts', ['IndexController', 'getSavedPosts']); // 저장된 게시글 목록 조회




//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'JWTController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/JWTController.php';
                break;
            /*case 'EventController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;
            case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
