<?php
function confirmIPAddress($value) {

    $q = "SELECT attempts, (CASE when lastlogin is not NULL and DATE_ADD(LastLogin, INTERVAL ".TIME_PERIOD.
    " MINUTE)>NOW() then 1 else 0 end) as Denied FROM ".TBL_ATTEMPTS." WHERE ip = '$value'";

    $result = mysql_query($q, $this->connection);
    $data = mysql_fetch_array($result); 

  //Verify that at least one login attempt is in database

    if (!$data) {
    return 0;
    }
    if ($data["attempts"] >= ATTEMPTS_NUMBER)
    {
    if($data["Denied"] == 1)
    {
        return 1;
    }
    else
    { 
        $this->clearLoginAttempts($value);
        return 0; 
    }
}
    return 0;
}

function addLoginAttempt($value) {

   //Increase number of attempts. Set last login attempt if required.

    $q = "SELECT * FROM ".TBL_ATTEMPTS." WHERE ip = '$value'";
    $result = mysql_query($q, $this->connection);
    $data = mysql_fetch_array($result);

    if($data)
    {
        $attempts = $data["attempts"]+1;        

        if($attempts==3) {
            $q = "UPDATE ".TBL_ATTEMPTS." SET attempts=".$attempts.", lastlogin=NOW() WHERE ip = '$value'";
            $result = mysql_query($q, $this->connection);
        } else {
            $q = "UPDATE ".TBL_ATTEMPTS." SET attempts=".$attempts." WHERE ip = '$value'";
            $result = mysql_query($q, $this->connection);
    }
    } else {
        $q = "INSERT INTO ".TBL_ATTEMPTS." (attempts,IP,lastlogin) values (1, '$value', NOW())";
        $result = mysql_query($q, $this->connection);
    }
}

function clearLoginAttempts($value) {
    $q = "UPDATE ".TBL_ATTEMPTS." SET attempts = 0 WHERE ip = '$value'";
    return mysql_query($q, $this->connection);
}
?>