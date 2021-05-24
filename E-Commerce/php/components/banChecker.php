<?php
//banChecker checks if date expired and corrects db entry
function banChecker($conn, $userId)
{
    $banExpired = "";
    $timeStrg = date('y-m-d H:i:s');
    $dateTimeNow = strtotime($timeStrg);

    //Get user ban date
    $sql = "SELECT banned_until FROM user WHERE pk_user_id = '$userId'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $bannedUntil = strtotime($row['banned_until']);

    //compare dates
    $banExpired = $bannedUntil < $dateTimeNow;

    //update db if date expired
    if ($banExpired) {
        $sql = "UPDATE user SET banned_until = NULL WHERE pk_user_id = '$userId'";
        $conn->query($sql);
    }

    return $banExpired;
}
