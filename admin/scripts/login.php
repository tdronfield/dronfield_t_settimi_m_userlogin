<?php

function login($username, $password, $ip)
{
    // return 'You are trying to login with U:'.$username.'P:'.$password.'.';

    // check against user DB to check credentials
    // Check if UN and PW match DB  
    $pdo = Database::getInstance()->getConnection();
    $get_user_query = 'SELECT * FROM tbl_user WHERE user_name = :username AND user_pass = :password';
    $user_set = $pdo->prepare($get_user_query);
    $user_set->execute(
        array(
            ':username'=>$username,
            ':password'=>$password,
        )
    );

    // Set time and date BEFORE logging in
    // Then update timestamp in DB

    // Error Handle here: if account locked: don't fetch.
    // if($found_user['failed_attempts'] < 3){
    if ($found_user = $user_set->fetch(PDO::FETCH_ASSOC)){
        // Found user, log in!
        // Debugging line only
        // return "Logging in!!";

        // Indicate the ID
        $found_user_id = $found_user['user_id'];

        // Indicate lastlogin
        $_SESSION['user_date'] = $lastlogin;

        // Indicate login attempts
        $_SESSION['user_login'] = $user_login; 

        // Write user and id into session
        $_SESSION['user_id'] = $found_user_id;
        $_SESSION['user_name'] = $found_user['user_fname'];
        
        // Write Timestamp into session
        $_SESSION['user_date'] = $found_user['user_date'];

        // Write Login attempts into Session
        $_SESSION['user_login'] = $found_user['user_login'];

        // Write failed Attempts into session
        $_SESSION['failed_attempts'] = $found_user['failed_attempts'];

        // Update user timestamp 
        $update_user_login_query = 'UPDATE tbl_user SET user_date = CURRENT_TIMESTAMP WHERE user_id=:user_id';
        $update_user_login_set = $pdo->prepare($update_user_login_query);
        $update_user_login_set->execute(
            array(
                ':user_id'=>$found_user_id
            )
        );

        // Update user IP
        $update_user_query = 'UPDATE tbl_user SET user_ip = :user_ip WHERE user_id=:user_id';
        $update_user_set = $pdo->prepare($update_user_query);
        $update_user_set->execute(
            array(
                ':user_ip'=>$ip,
                ':user_id'=>$found_user_id
            )
        );

        // Update Successful Login Attempts
        $update_login_query = 'UPDATE tbl_user SET user_login = user_login + 1 WHERE user_id=:user_id';
        $update_login_set = $pdo->prepare($update_login_query);
        $update_login_set->execute(
            array(
                ':user_id'=>$found_user_id
            )
        );

        // Redirect user back to index.php
        redirect_to('index.php');

    } else {
        // Invalid attemp, rejected!
        return "Learn how to type!!";

        // LOG unsuccessful Login 
        $update_failed_query = 'UPDATE tbl_user SET failed_attempts = :failedattempts WHERE user_id=:user_id';
        $update_failed_set = $pdo->prepare($update_failed_query);
        $update_failed_set->execute(
            array(
                ':failedattempts'=>$failed_attempts,
                ':user_id'=>$found_user_id
            )
        );
    }
}
//}

function confirm_logged_in()
{
    if(!isset($_SESSION['user_id'])){
        redirect_to("admin_login.php");
    }
}

function logout()
{
    session_destroy();

    redirect_to('admin_login.php');
}

