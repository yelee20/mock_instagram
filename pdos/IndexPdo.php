<?php

// Check Validation 유효성 체크
function isValidUserIdx($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where userIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isValidUserID($userID)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where userID = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userID]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isValidPostIdx($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Post where postIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isValidContact($personalContact)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where personalContact = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$personalContact]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isValidEmail($email)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where email = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$email]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isCommentEnabled($postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Post where postIdx = ? and isDeleted = 'N' and isCommentEnabled = 'Y') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

function isValidCommentIdx($commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Comment where commentIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}


function isValidStoryIdx($storyIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Story where storyIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$storyIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

function isValidHighlightIdx($highlightIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from StoryHighlights where highlightIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

// 유효한 채팅방 인덱스
function isValidRoomIdx($roomIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DM where roomIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$roomIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

// 유효한 메세지 인덱스
function isValidMessageIdx($roomIdx,$messageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select Exists(select roomIdx, messageIdx from DM where roomIdx =? and messageIdx=? and isDeleted ='N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$roomIdx,$messageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

function isValidConversation($sendUserIdx,$receivedUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DM where (sendUserIdx = $sendUserIdx 
and receivedUserIdx = $receivedUserIdx) or (sendUserIdx = $receivedUserIdx 
and receivedUserIdx = $sendUserIdx) and isDeleted='N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$sendUserIdx,$receivedUserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

function getRoomIdx($sendUserIdx,$receivedUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select DISTINCT roomIdx 
            from DM 
            where (sendUserIdx = $sendUserIdx 
and receivedUserIdx = $receivedUserIdx) or (sendUserIdx = $receivedUserIdx 
and receivedUserIdx = $sendUserIdx) and isDeleted='N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$sendUserIdx,$receivedUserIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['roomIdx'];
}

function getNewRoomIdx(){
    $pdo = pdoSqlConnect();
    $query = "select max(roomIdx)+1 as roomIdx from DM;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['roomIdx'];
}

function isValidMessageType($messageType)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DM where messageType = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$messageType]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}


function isFollowedByUser($userIdx, $followeduserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from FollowLog where userIdx = $userIdx and 
                            followeduserIdx = $followeduserIdx and isAccepted = 'Y') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isCloseFriend($userIdx, $followeduserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from FollowLog where userIdx = ? and 
                followeduserIdx = ? and isCloseFriend = 'Y' and isAccepted = 'Y') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}


function isPostOwner($userIdx, $postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Post where userIdx = ? and 
                postIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isMessageOwner($userIdx, $roomIdx, $messageIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DM where sendUserIdx = ? and 
                roomIdx = ? and messageIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$roomIdx, $messageIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function commentOnPost($postIdx, $commentIdx){ // 댓글 달린 게시글이 맞는지
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Post
inner join( select postIdx, commentIdx from Comment where Comment.isDeleted = 'N') C on C.postIdx = Post.postIdx
where C.postIdx = $postIdx and C.commentIdx = $commentIdx) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isCommentOwner($userIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select P.userIdx as postOwner, C.userIdx as commentOwner, commentIdx
                from Comment as C
                inner join (select postIdx, userIdx from Post) P on C.postIdx = P.postIdx
                where commentIdx = $commentIdx and (P.userIdx = $userIdx or C.userIdx = $userIdx)) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$commentIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}


function isStoryOwner($userIdx, $storyIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from Story where userIdx = ? and 
                storyIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$storyIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}
function isHighlightOwner($userIdx, $highlightIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select userIdx, S.storyIdx, highlightIdx, HighlightedStories.isDeleted from HighlightedStories
inner join (select userIdx, storyIdx from Story) S on S.storyIdx = HighlightedStories.storyIdx
where userIdx = ? and highlightIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$highlightIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

//READ
function getUsers($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select * from User where userID Like concat('%',?,'%');";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ 사용자 프로필 조회
function getUserProfile($userIdx,$targetUserIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select U.userIdx, U.isPublic, U.userID, U.profileImageUrl, ifnull(S.createdAt,'N') as storyToday, U.userName, U.userType, BB.blueBadge, U.bio, CONCAT(ifnull(Post.PostNo,0), ' 게시물') as PostNo, CONCAT(ifnull(Follower.FollowerNo,0), ' 팔로워') as FollowerNo, CONCAT(ifnull(Following.FollowingNo,0), ' 팔로잉') as FollowingNo, case when FB.followeduserIdx is null then 0 else 1 end as isFollowedByMe, CONCAT(DM.MessageNum,'개') as MessageNum
from User as U
left join (SELECT S.userIdx, (CASE WHEN S.createdAt is Null then 'N'
                                  ELSE 'Y' End) as createdAt
FROM Story as S
WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) S on S.userIdx = U.userIdx
inner join (
select COUNT(P.postIdx) as PostNo, P.userIdx
from Post as P
group by userIdx) Post on Post.userIdx = U.userIdx and U.userIdx = $targetUserIdx
Left join (select F.userIdx, COUNT(F.followedUserIdx) as FollowingNo
from FollowLog as F
where F.isAccepted = 'Y'
group by userIdx) Following on Following.userIdx = U.userIdx
Left join(select F.followedUserIdx, COUNT(F.userIdx) as FollowerNo
from FollowLog as F
where F.isAccepted = 'Y'
group by followedUserIdx) Follower on Follower.followedUserIdx = U.userIdx
Left join (select DM.receivedUserIdx, COUNT(DM.messageIdx) as MessageNum
from DM
where isSeen = 'N'
group by receivedUserIdx) DM on DM.receivedUserIdx = U.userIdx
left join(select BB.userIdx, BB.blueBadge
from BlueBadge as BB
WHere BB.isDeleted='N') BB on U.userIdx = BB.userIdx
left join (select userIdx, followeduserIdx
from FollowLog where userIdx = $userIdx and followeduserIdx = $targetUserIdx) FB on FB.followeduserIdx = U.userIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//READ 내 프로필 조회
function getMyProfile($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "
select U.userIdx, U.isPublic, U.userID, U.profileImageUrl, ifnull(S.createdAt,'N') as storyToday, U.userName, U.userType, BB.blueBadge, U.bio, CONCAT(ifnull(Post.PostNo,0), ' 게시물') as PostNo, CONCAT(ifnull(Follower.FollowerNo,0), ' 팔로워') as FollowerNo, CONCAT(ifnull(Following.FollowingNo,0), ' 팔로잉') as FollowingNo, CONCAT(DM.MessageNum,'개') as MessageNum
from User as U
left join (SELECT S.userIdx, (CASE WHEN S.createdAt is Null then 'N'
                                  ELSE 'Y' End) as createdAt
FROM Story as S
WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) S on S.userIdx = U.userIdx
inner join (
select COUNT(P.postIdx) as PostNo, P.userIdx
from Post as P
group by userIdx) Post on Post.userIdx = U.userIdx and U.userIdx = $userIdx
Left join (select F.userIdx, COUNT(F.followedUserIdx) as FollowingNo
from FollowLog as F
where F.isAccepted = 'Y'
group by userIdx) Following on Following.userIdx = U.userIdx
Left join(select F.followedUserIdx, COUNT(F.userIdx) as FollowerNo
from FollowLog as F
where F.isAccepted = 'Y'
group by followedUserIdx) Follower on Follower.followedUserIdx = U.userIdx
Left join (select DM.receivedUserIdx, COUNT(DM.messageIdx) as MessageNum
from DM
where isSeen = 'N'
group by receivedUserIdx) DM on DM.receivedUserIdx = U.userIdx
left join(select BB.userIdx, BB.blueBadge
from BlueBadge as BB
WHere BB.isDeleted='N') BB on U.userIdx = BB.userIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}


// Create 회원가입
function createUser($userID, $userName, $profileImageUrl, $bio, $isPublic, $userType, $personalContact, $pwd_hash, $gender, $birthday)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO User (userID, userName, profileImageUrl, bio, isPublic, userType, personalContact, password, gender, birthday) VALUES (?,?,?,?,?,?,?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userID, $userName, $profileImageUrl, $bio, $isPublic, $userType, $personalContact, $pwd_hash, $gender, $birthday]);
    $st = null;
    $pdo = null;

}

// UPDATE 사용자 정보 수정
function updateUserInfo($userID, $userName, $profileImageUrl, $bio, $isPublic, $userType, $personalContact, $password, $gender, $birthday, $userIdx)
{
        $pdo = pdoSqlConnect();
        $query = "UPDATE User
                        SET userID = ?,
                            userName  = ?,
                            profileImageUrl  = ?,
                            bio  = ?,
                            isPublic  = ?,
                            userType  = ?,
                            personalContact = ?, 
                            password = ?, 
                            gender = ?, 
                            birthday = ?,
                            updatedAt = CURRENT_TIMESTAMP
                        WHERE userIdx = ?;";

        $st = $pdo->prepare($query);
        $st->execute([$userID, $userName, $profileImageUrl, $bio, $isPublic, $userType ,$personalContact, $password, $gender, $birthday, $userIdx]);
        $st = null;
        $pdo = null;
}

function isDuplicateUserID($userIdx, $userID)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where userIdx != ? 
                and userID = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $userID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isDuplicateContact($userIdx, $personalContact)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where userIdx != ? and personalContact = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $personalContact]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}



// DELETE 회원 탈퇴
function deleteUser($userIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE User
                        SET isDeleted = 'Y'
                        WHERE userIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st = null;
    $pdo = null;
}

// Create 로그인
function login($userID, $personalContact, $password)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where (userID = ? or personalContact = ?) and password = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userID, $personalContact, $password]);
//    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

// Delete 로그아웃
function logout($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from User where userIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
//    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}

// Create 게시글 등록
function createPost($userIdx,$postCaption, $location, $isCommentEnabled)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO Post (userIdx,postCaption,location,isCommentEnabled) VALUES (?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postCaption, $location, $isCommentEnabled]);

    $postIdx = $pdo->lastInsertId();
    $st = null;
    $pdo = null;

    return $postIdx;

}

function insertPostImage($postIdx,$imageUrl){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO PostImage (postIdx, imageUrl) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx,$imageUrl]);

    $st = null;
    $pdo = null;

}
function insertPostVideo($postIdx, $videoUrl, $length){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO PostVideo (postIdx, videoUrl, length) VALUES (?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$postIdx,$videoUrl,$length]);

    $st = null;
    $pdo = null;

}


// UPDATE 게시글 수정
    function updatePost($postCaption, $location, $isCommentEnabled, $postIdx){
        $pdo = pdoSqlConnect();
        $query = "UPDATE Post
                        SET postCaption = ?,
                            location  = ?,
                            isCommentEnabled = ?,
                            updatedAt = CURRENT_TIMESTAMP
                        WHERE postIdx = ?";

        $st = $pdo->prepare($query);
        $st->execute([$postCaption, $location, $isCommentEnabled, $postIdx]);
        $st = null;
        $pdo = null;
    }

// DELETE 게시글 삭제
function deletePost($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Post
                        SET isDeleted = 'Y'
                        WHERE postIdx = $postIdx";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    $st = null;
    $pdo = null;
}

// DELETE 게시글에 올라간 사진 삭제
function deletePostImage($postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE PostImage
                        SET isDeleted = 'Y'
                        WHERE postIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    $st = null;
    $pdo = null;
}

// DELETE 게시글에 올라간 영상 삭제
function deletePostVideo($postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE PostVideo
                        SET isDeleted = 'Y'
                        WHERE postIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$postIdx]);
    $st = null;
    $pdo = null;
}

// Create 팔로우 신청
function createFollow($userIdx, $followeduserIdx)
{   $pdo = pdoSqlConnect();
    $query = "
INSERT INTO FollowLog (userIdx, followeduserIdx, isAccepted) VALUES
($userIdx, $followeduserIdx, (case when ($followeduserIdx) IN
                 (select userIdx from User where isPublic = 'Y')
    then 'Y' else 'P' end ));";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $followeduserIdx]);
    $st = null;
    $pdo = null;

}

// DELETE 팔로우 취소
function deleteFollow($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FollowLog SET isAccepted = 'D',
                     updatedAt = current_timestamp
where userIdx = ? and followeduserIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    $st = null;
    $pdo = null;
}
// 비공개 계정인지 확인
function isPublicAccount($userIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select userIdx from User where userIdx = ? and isPublic = 'Y') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}
// 팔로우 되어있는지 확인
function isFollowed($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from FollowLog where userIdx = ? and 
                followeduserIdx = ? and isAccepted = 'Y') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isNotAccepted($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from FollowLog where userIdx = $userIdx and followeduserIdx = $followeduserIdx and isAccepted = 'N' 
                            or userIdx = $userIdx and followeduserIdx = $followeduserIdx and isAccepted = 'P') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}


function isFollowedExists($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from FollowLog where userIdx = ? and 
                followeduserIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function followAgain($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FollowLog SET isAccepted = Case when $followeduserIdx IN (select userIdx from User where isPublic = 'Y') then 'Y' else 'P' end, 
                updatedAt = current_timestamp
                where userIdx = $userIdx and followeduserIdx = $followeduserIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$followeduserIdx]);
    $st = null;
    $pdo = null;
}

// GET 팔로워 목록 조회
function getFollowerList($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "
select DISTINCT FollowLog.followedUserIdx, followerIdx, profileImageUrl, ifnull(S.createdAt,'N') as storyToday, followerID, followerName, ByMe.isCloseFriend, case when FB.isAccepted is null then 'N' else 'Y' end as isFollowedByMe
from FollowLog
inner join (select userIdx as followerIdx, profileImageUrl, userID as followerID, userName as followerName from User) Follower on FollowLog.userIdx = Follower.followerIdx
inner join (select userIdx, followedUserIdx,isCloseFriend
from FollowLog where followedUserIdx = $userIdx) ByMe on ByMe.userIdx = FollowLog.userIdx
left join (SELECT S.userIdx, (CASE WHEN S.createdAt is Null then 'N'
                                  ELSE 'Y' End) as createdAt
FROM Story as S
WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) S on S.userIdx = Follower.followerIdx
left join(select userIdx, followeduserIdx, isAccepted
from FollowLog
where userIdx = $userIdx) FB on FB.userIdx = FollowLog.followeduserIdx
where FollowLog.followedUserIdx = ByMe.followedUserIdx and FollowLog.isAccepted = 'Y';
LIMIT 30 OFFSET 0
";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// GET 팔로잉 목록 조회
function getFollowingList($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "
select DISTINCT FollowLog.userIdx, FollowLog.followedUserIdx, profileImageUrl, ifnull(S.createdAt,'N') as storyToday, followedUserID, followedUserName, ByMe.isCloseFriend, case when FB.isAccepted is null then 'N' else 'Y' end as isFollowedByMe
from FollowLog
inner join (select userIdx as followedUserIdx, profileImageUrl, userID as followedUserID, userName as followedUserName from User) Following on FollowLog.followeduserIdx = Following.followedUserIdx
inner join (select userIdx, followedUserIdx,isCloseFriend
from FollowLog where userIdx = $userIdx) ByMe on ByMe.userIdx = FollowLog.userIdx and ByMe.followeduserIdx = FollowLog.followeduserIdx
left join (SELECT S.userIdx, (CASE WHEN S.createdAt is Null then 'N'
                                  ELSE 'Y' End) as createdAt
FROM Story as S
WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) S on S.userIdx = Following.followedUserIdx
left join(select userIdx, followeduserIdx, isAccepted
from FollowLog
where userIdx = $userIdx) FB on FB.userIdx = FollowLog.userIdx
where FollowLog.isAccepted = 'Y';
LIMIT 30 OFFSET 0
";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// GET 사용자 게시글 목록 조회
function getUserPosts($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select PI.postIdx, imageUrl, M.isMultiple
from PostImage as PI, Post as P
inner join(select postIdx, (case when COUNT(PI.imageUrl) > 1 then '1'
                                else '0' end) as isMultiple from PostImage as PI group by postIdx) M on M.postIdx = P.postIdx
where userIdx = ? and P.postIdx = PI.postIdx and (PI.postIdx, postImageIdx) in
(select postIdx,min(postImageIdx) from PostImage group by postIdx)
order by PI.postIdx DESC
LIMIT 54 OFFSET 0";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


// Create 댓글 등록
function createComment($userIdx, $postIdx, $commentContent, $parentCommentIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO Comment (userIdx, postIdx, commentContent, parentCommentIdx) VALUES (?, ?, ?, ?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx, $commentContent, $parentCommentIdx]);
    $st = null;
    $pdo = null;

}


// GET 댓글 조회
function getComments($userIdx,$postIdx)
{
    $pdo = pdoSqlConnect();
    $query = "
select C.commentIdx, C.parentCommentIdx,C.userIdx, userID, profileimageUrl, C.commentContent, CT.createdAt, LikeNum, case when isLiked is not null then 'Y' else 'N'end as isLikedByMe
from Comment as C
inner join(select userIdx, userID, profileimageUrl
from User where isDeleted = 'N') U on U.userIdx = C.userIdx
inner join (SELECT C.commentIdx, CASE WHEN TIMESTAMPDIFF(SECOND, C.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 60 then CONCAT(TIMESTAMPDIFF(SECOND, C.createdAt, CURRENT_TIMESTAMP), '초')
                       WHEN TIMESTAMPDIFF(MINUTE , C.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 60 then CONCAT(TIMESTAMPDIFF(MINUTE, C.createdAt, CURRENT_TIMESTAMP), '분')
                       WHEN TIMESTAMPDIFF(HOUR, C.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 24 then CONCAT(TIMESTAMPDIFF(HOUR, C.createdAt, CURRENT_TIMESTAMP), '시간')
                       WHEN DATEDIFF(CURRENT_TIMESTAMP, C.createdAt) BETWEEN 0 and 7 then CONCAT(DATEDIFF(CURRENT_TIMESTAMP, C.createdAt), '일')
                       ELSE CONCAT(FLOOR(DATEDIFF(CURRENT_TIMESTAMP, C.createdAt)/7), '주')
                        END as createdAt
    from Comment as C) CT on CT.commentIdx = C.commentIdx
left join (SELECT commentIdx, COUNT(userIdx) as LikeNum  from LikeComment
where isDeleted = 'N'
GROUP BY commentIdx
) LC on LC.commentIdx = C.commentIdx
left join(
SELECT userIdx as isLiked, commentIdx
FROM LikeComment
WHERE userIdx = $userIdx) LByMe on LByMe.commentIdx = C.commentIdx
where C.isDeleted = 'N' and postIdx = $postIdx
order by C.commentIdx desc;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// DELETE 댓글 삭제
function deleteComment($commentIdx)
{   $pdo = pdoSqlConnect();
    $query = "UPDATE Comment
                        SET isDeleted = 'Y',
                        updatedAt = current_timestamp
                        WHERE commentIdx = $commentIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$commentIdx]);
    $st = null;
    $pdo = null;

}

// Create 게시글 좋아요 등록
function likePost($userIdx, $postIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikePost (userIdx, postIdx) VALUES (?, ?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx]);
    $st = null;
    $pdo = null;

}

// DELETE 게시글 좋아요 취소
function deletePostLike($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikePost SET isDeleted = 'Y',
            updatedAt = current_timestamp
            where userIdx = ? and postIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    $st = null;
    $pdo = null;
}

// 게시글 좋아요 눌렀는지 확인
function isLikedPost($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from LikePost where userIdx = ? and 
                postIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isLikedPostExists($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from LikePost where userIdx = ? and 
                postIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function likePostAgain($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikePost SET isDeleted = 'N',
              updatedAt = current_timestamp 
              where userIdx = ? and postIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    $st = null;
    $pdo = null;
}

// Get 좋아한 게시글 목록 조회
function getLikedPosts($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select PI.postIdx, imageUrl, M.isMultiple
from PostImage as PI, Post as P
inner join(select postIdx, userIdx
from LikePost
where isDeleted = 'N' and userIdx = ?) L on L.postIdx = P.postIdx
inner join(select postIdx, (case when COUNT(PI.imageUrl) > 1 then 'Y'
                                else 'N' end) as isMultiple from PostImage as PI group by postIdx) M on M.postIdx = P.postIdx
where P.postIdx = PI.postIdx and (PI.postIdx, postImageIdx) in
(select postIdx,min(postImageIdx) from PostImage group by postIdx)
order by PI.postIdx DESC
LIMIT 54 OFFSET 0
;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// Create 댓글 좋아요 등록
function likeComment($userIdx, $commentIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO LikeComment (userIdx, commentIdx) VALUES (?, ?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $commentIdx]);
    $st = null;
    $pdo = null;

}

// 댓글 좋아요 취소
function deleteCommentLike($userIdx, $commentIdx)
{   $pdo = pdoSqlConnect();
    $query = "UPDATE LikeComment SET isDeleted = 'Y' , updatedAt = current_timestamp where userIdx = ? and commentIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$commentIdx]);
    $st = null;
    $pdo = null;
}


// 댓글 좋아요 눌렀는지 확인
function isLikedComment($userIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from LikeComment where userIdx = ? and 
                commentIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isLikedCommentExists($userIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from LikeComment where userIdx = ? and 
                commentIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function likeCommentAgain($userIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE LikeComment SET isDeleted = 'N' , updatedAt = current_timestamp where userIdx = ? and commentIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$commentIdx]);
    $st = null;
    $pdo = null;
}

// Create 스토리 등록
function createStory($userIdx,$closeFriendsOnly)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO Story (userIdx,closeFriendsOnly) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$closeFriendsOnly]);

    $storyIdx = $pdo->lastInsertId();
    $st = null;
    $pdo = null;

    return $storyIdx;

}

function insertStoryImage($storyIdx,$imageUrl){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO StoryImage (storyIdx, imageUrl) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$storyIdx,$imageUrl]);

    $st = null;
    $pdo = null;

}
function insertStoryVideo($storyIdx,$videoUrl){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO StoryVideo (storyIdx, videoUrl) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$storyIdx,$videoUrl]);

    $st = null;
    $pdo = null;

}

// GET 스토리 조회
function getStoryToday($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT S.storyIdx, U.userID as storyUserID, U.profileImageUrl, ifNull(CF.isCloseFriend, 'N') as isCloseFriend, case when isSeenByMe = 1 then 'Y' else 'N' end as isSeenByMe
    FROM User as U
    Inner join (SELECT S.storyIdx, S.userIdx, S.createdAt from Story as S
    WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) S on U.userIdx = S.userIdx
    Left join (
    SELECT U.userID as storyUserID, S.storyIdx, S.createdAt, FL.isCloseFriend
    FROM Story as S
    Inner join User as U on U.userIdx = S.userIdx
    Inner join (select FL.userIdx, FL.isCloseFriend
    from FollowLog as FL
    where FL.followedUserIdx = $userIdx) FL on FL.userIdx = U.userIdx
    WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) CF on CF.storyUserID = U.userID and CF.storyIdx = S.storyIdx
    left join (select userIdx as isSeenByMe, storyIdx
    from StoryViewLog
    where userIdx = $userIdx) VL on VL.storyIdx = S.storyIdx
    ORDER BY S.createdAt DESC;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// DELETE 스토리 삭제
function deleteStory($storyIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Story
                        SET isDeleted = 'Y'
                        WHERE storyIdx = $storyIdx";

    $st = $pdo->prepare($query);
    $st->execute([$storyIdx]);
    $st = null;
    $pdo = null;
}

// 스토리가 48시간 이내에 올라온건지 확인
function isUploadedIn48Hrs($storyIdx)
{   $pdo = pdoSqlConnect();
    $query = "select exists(SELECT S.userIdx, S.storyIdx, S.createdAt
                    FROM Story as S
                    WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 48 and storyIdx = ?) as Exist";
    $st = $pdo->prepare($query);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $st->execute([$storyIdx]);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);

}


// Create 스토리 하이라이트 등록
function createStoryHighlights($highlightImageUrl,$highlightTitle)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO StoryHighlights (highlightImageUrl,highlightTitle) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$highlightImageUrl,$highlightTitle]);
    $highlightIdx = $pdo->lastInsertId();
    $st = null;
    $pdo = null;

    return $highlightIdx;

}

// Create 스토리 하이라이트 등록 Part2  - 하이라이트에 들어갈 스토리 추가

function connectHighlights($highlightIdx,$storyIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO HighlightedStories (highlightIdx,storyIdx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx,$storyIdx]);
    $st = null;
    $pdo = null;

}

// UPDATE 스토리 하이라이트 수정
function updateStoryHighlights($highlightImageUrl, $highlightTitle,$highlightIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE StoryHighlights
                        SET highlightImageUrl = ?,
                            highlightTitle = ?
                        WHERE highlightIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$highlightImageUrl, $highlightTitle, $highlightIdx]);
    $st = null;
    $pdo = null;
}

// 하이라이트에 스토리 추가

function insertStory($highlightIdx,$storyIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO HighlightedStories (highlightIdx,storyIdx) VALUES (?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx,$storyIdx]);
    $st = null;
    $pdo = null;

}

// 하이라이트 스토리 수정
function updateStory($highlightIdx,$storyIdx)
{   $pdo = pdoSqlConnect();
    $query = "UPDATE HighlightedStories
                        SET isDeleted = 'N'
                        WHERE highlightIdx = ? and storyIdx = ?";
    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx,$storyIdx]);
    $st = null;
    $pdo = null;

}


// 스토리가 하이라이트에 추가 되어있는지 확인

function isStoryInHighlight($storyIdx,$highlightIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(
                select storyIdx
                from HighlightedStories
                where storyIdx = ? and highlightIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$storyIdx,$highlightIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}


// DELETE 스토리 하이라이트 삭제
function deleteStoryHighlights($highlightIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE StoryHighlights
                        SET isDeleted = 'Y', updatedAt = current_timestamp
                        WHERE highlightIdx = $highlightIdx";

    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx]);
    $st = null;
    $pdo = null;
}

// DELETE 스토리 하이라이트에서 스토리 삭제
function deleteHighlightedStories($highlightIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE HighlightedStories
                        SET isDeleted = 'Y'
                        WHERE highlightIdx = $highlightIdx";

    $st = $pdo->prepare($query);
    $st->execute([$highlightIdx]);
    $st = null;
    $pdo = null;
}

// GET 스토리 하이라이트 조회
function getStoryHighlights($userIdx)
{
    $query = "select DISTINCT SH.highlightIdx, highlightTitle, highlightImageUrl
from StoryHighlights as SH
inner join (select userIdx, Story.storyIdx, highlightIdx
from HighlightedStories, Story where Story.storyIdx = HighlightedStories.storyIdx) S on S.highlightIdx = SH.highlightIdx
where userIdx = ? order by SH.highlightIdx DESC
LIMIT 20 OFFSET 0;";
    $pdo = pdoSqlConnect();

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//GET
function getFeedPosts($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "Select DISTINCT U.userIdx, U.userID, profileImageUrl,
       ifnull(ST.storyToday,'0') as storyToday, P.postIdx,
       concat_ws(',',PostUrl.imageList, ifNull(PostUrl.videoList,'')) as PostSource,
       (PostUrl.numOfImage + PostUrl.numOfVid) as numOfUrl,
       case when isLiked is not null then '1' else '0' end as isLikedByMe,
       case when isSaved is not null then '1' else '0' end as isSavedByMe,
       CASE when CHAR_LENGTH(P.postCaption) > 13 then CONCAT(SUBString(postCaption,1,13), '...더보기') ELSE P.postCaption END as postCaption,
       PT.createdAt, commentNum, P.location,
       ifnull(LikeNum,Null) as LikeNum,
       commentedUserID, C.commentContent

from User as U
left join (SELECT DISTINCT S.userIdx, (CASE WHEN S.createdAt is Null then '0'
                                      ELSE '1' End) as storyToday
    FROM Story as S
    WHERE HOUR(TIMEDIFF(S.createdAt, CURRENT_TIMESTAMP())) < 24) ST on ST.userIdx = U.userIdx
left join (select postIdx, userIdx, postCaption, location from Post) P on P.userIdx = U.userIdx
left Join (select P.postIdx, imageList, ifNull(numOfImage,0) as numOfImage, videoList, ifNull(numOfVid,0) as numOfVid
from Post as P
left join (select postIdx, count(imageUrl) as numOfImage, GROUP_CONCAT(imageUrl) as imageList from PostImage
            group by postIdx) PI on PI.postIdx = P.postIdx
left join(select postIdx, COUNT(videoUrl) as numOfVid, GROUP_CONCAT(videoUrl) as videoList
    from PostVideo group by postIdx) PV on PV.postIdx = P.postIdx) PostUrl on P.postIdx = PostUrl.postIdx
left join (SELECT userIdx as isLiked, postIdx
    FROM LikePost
    WHERE userIdx = $userIdx and isDeleted = 'N') L on L.postIdx = P.postIdx
LEFT JOIN (SELECT userIdx as isSaved, postIdx
    from SavedPost
    WHERE userIdx = $userIdx and isDeleted = 'N') Saved on P.postIdx = Saved.postIdx
left join (SELECT P.postIdx, CASE WHEN TIMESTAMPDIFF(SECOND, P.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 60 then CONCAT(TIMESTAMPDIFF(SECOND, P.createdAt, CURRENT_TIMESTAMP), '초 전')
                       WHEN TIMESTAMPDIFF(MINUTE , P.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 60 then CONCAT(TIMESTAMPDIFF(MINUTE, P.createdAt, CURRENT_TIMESTAMP), '분 전')
                       WHEN TIMESTAMPDIFF(HOUR, P.createdAt, CURRENT_TIMESTAMP) BETWEEN 0 and 24 then CONCAT(TIMESTAMPDIFF(HOUR, P.createdAt, CURRENT_TIMESTAMP), '시간 전')
                       WHEN DATEDIFF(CURRENT_TIMESTAMP, P.createdAt) BETWEEN 0 and 7 then CONCAT(DATEDIFF(CURRENT_TIMESTAMP, P.createdAt), '일 전')
                       WHEN DATEDIFF(CURRENT_TIMESTAMP, P.createdAt) BETWEEN 7 and 30 then CONCAT(FLOOR(DATEDIFF(CURRENT_TIMESTAMP, P.createdAt)/7), '주 전')
                       WHEN DATE_FORMAT(P.createdAt, '%Y') = 2020 THEN DATE_FORMAT(P.createdAt, '%c월 %e일')
                        ELSE DATE_FORMAT(P.createdAt, '%Y년 %c월 %e일') END as createdAt
    from Post as P) PT on PT.postIdx = P.postIdx
left join (SELECT postIdx, CASE when count(commentIdx) - 1 = 0 then null else CONCAT_WS('','댓글 ', (count(commentIdx) - 1),'개 모두보기') end as commentNum
    FROM Comment as C
    GROUP BY postIdx) CN on CN.postIdx = P.postIdx
Left join ( SELECT postIdx, count(userIdx) as LikeNum  from LikePost
    GROUP BY postIdx) LK on P.postIdx = LK.postIdx
Left join (SELECT User.userID as commentedUserID, C.userIdx, C.postIdx, C.commentContent
    From Comment as C
    inner join (select User.userID, User.userIdx from User) User on C.userIdx = User.userIdx
    inner join (Select postIdx, min(commentContent) as commentContent
    from Comment
    group by postIdx) CC on CC.commentContent = C.commentContent
    where C.userIdx = User.userIdx)
     C on P.postIdx = C.postIdx
inner join (select DISTINCT postIdx, FL.userIdx
from Post
inner join(select followeduserIdx, userIdx
from FollowLog
where userIdx = $userIdx and isAccepted = 'Y') FL on followeduserIdx = Post.userIdx or Post.userIdx = FL.userIdx
) FL on FL.postIdx = P.postIdx
Order by postIdx DESC;
LIMIT 7 OFFSET 0
";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


//GET 친한 친구 목록 조회
function getCloseFriends($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select FL.followedUserIdx, FL.isCloseFriend
from FollowLog as FL
where FL.userIdx = ? and FL.isCloseFriend = 'Y'
LIMIT 15 OFFSET 0;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


// Create DM 보내기
function createDM($roomIdx, $sendUserIdx, $receivedUserIdx, $messageType, $messageContent)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO DM (roomIdx, sendUserIdx, receivedUserIdx, messageType, messageContent) VALUES (?, ?, ?, ?, ?);";
    $st = $pdo->prepare($query);
    $st->execute([$roomIdx, $sendUserIdx, $receivedUserIdx, $messageType, $messageContent]);
    $st = null;
    $pdo = null;

}

// DELETE DM 삭제
function deleteDM($userIdx, $roomIdx, $messageIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE DM
                        SET isDeleted = 'Y',
                            isSeen = 'N', updatedAt = current_timestamp
                        WHERE sendUserIdx = ? and roomIdx = ? and messageIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $roomIdx, $messageIdx]);
    $st = null;
    $pdo = null;
}

// DM 확인
function readDM($userIdx, $roomIdx)
{   $pdo = pdoSqlConnect();
    $query = "UPDATE DM SET isSeen = 'Y' where receiveduserIdx = ? and roomIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$roomIdx]);
    $st = null;
    $pdo = null;
}

function isAllowedUser($userIdx,$roomIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from DM where  receivedUserIdx = $userIdx and roomIdx = $roomIdx) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$roomIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

//GET
function getDMNum($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "
Select U.userIdx, U.userID, DM.MessageNum
    From User as U
    left join(select DM.receivedUserIdx, CONCAT(COUNT(DM.MessageIdx), '개') as MessageNum
    from DM
    where isSeen = 'N'
    group by receivedUserIdx) DM on DM.receivedUserIdx = U.userIdx
    WHERE U.userIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

// CREATE 친한친구 등록
function createCloseFriend($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FollowLog
                        SET isCloseFriend = 'Y', updatedAt = current_timestamp
                        WHERE userIdx = ? and followeduserIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $followeduserIdx]);
    $st = null;
    $pdo = null;
}

// DELETE 친한친구 해제
function deleteCloseFriend($userIdx, $followeduserIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE FollowLog
                        SET isCloseFriend = 'N', updatedAt = current_timestamp
                        WHERE userIdx = ? and followeduserIdx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $followeduserIdx]);
    $st = null;
    $pdo = null;
}



// Create 게시글 저장
function savePost($userIdx, $postIdx)
{   $pdo = pdoSqlConnect();
    $query = "INSERT INTO SavedPost (userIdx, postIdx) VALUES (?, ?);";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx]);
    $st = null;
    $pdo = null;

}

// Delete 게시글 저장 해제
function deleteSavedPost($userIdx, $postIdx)
{   $pdo = pdoSqlConnect();
    $query = "UPDATE SavedPost SET isDeleted = 'Y', updatedAt = current_timestamp where userIdx = ? and postIdx = ?;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdx, $postIdx]);
    $st = null;
    $pdo = null;

}

function isSaved($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from SavedPost where userIdx = ? and 
                postIdx = ? and isDeleted = 'N') as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}

function isSavedExists($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "select exists(select * from SavedPost where userIdx = ? and 
                postIdx = ?) as Exist;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    return intval($res[0]['Exist']);
}


function savePostAgain($userIdx, $postIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE SavedPost SET isDeleted = 'N', updatedAt = current_timestamp where userIdx = ? and postIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx,$postIdx]);
    $st = null;
    $pdo = null;
}
//GET 저장된 게시글 목록 조회
function getSavedPosts($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select PI.postIdx, imageUrl, M.isMultiple
                from PostImage as PI, Post as P
                inner join(select postIdx, userIdx
                from SavedPost
                where isDeleted = 'N' and userIdx = 1) S on S.postIdx = P.postIdx
                inner join(select postIdx, (case when COUNT(PI.imageUrl) > 1 then 'Y'
                                                else 'N' end) as isMultiple from PostImage as PI group by postIdx) M on M.postIdx = P.postIdx
                where P.postIdx = PI.postIdx and (PI.postIdx, postImageIdx) in
                (select postIdx,min(postImageIdx) from PostImage group by postIdx)
                order by PI.postIdx DESC
                LIMIT 54 OFFSET 0;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }


// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }

// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
