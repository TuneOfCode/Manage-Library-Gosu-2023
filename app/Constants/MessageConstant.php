<?php

namespace App\Constants;

class MessageConstant {
    #region Auth
    public static string $REGISTER_SUCCESS = "Register success! Please go to your email and receive the confirmation code.";
    public static string $REGISTER_FAILED = "Register failed!";
    public static string $RESEND_OTP_EMAIL_SUCCESS = "Resend otp email success! Please go to your email and receive the confirmation code.";
    public static string $RESEND_OTP_EMAIL_FAILED = "Resend otp email failed!";
    public static string $VERIFY_EMAIL_SUCCESS = "Verify email success!";
    public static string $VERIFY_EMAIL_FAILED = "Verify email failed!";
    public static string $LOGIN_SUCCESS = "Login success!";
    public static string $LOGIN_FAILED = "Login failed!";
    public static string $ME_SUCCESS = "Get current member success!";
    public static string $ME_FAILED = "Get current member failed!";
    public static string $FORGOT_PASSWORD_SUCCESS = "The system created new password! Please go to your email and receive it.";
    public static string $FORGOT_PASSWORD_FAILED = "Create new password or email forgot password failed!";
    public static string $CHANGE_PASSWORD_SUCCESS = "Change password success!";
    public static string $CHANGE_PASSWORD_FAILED = "Change password failed!";
    public static string $UPDATE_ME_SUCCESS = "Update current member success!";
    public static string $UPDATE_ME_FAILED = "Update current member failed!";
    public static string $UPLOAD_AVATAR_SUCCESS = "Upload avatar success!";
    public static string $UPLOAD_AVATAR_FAILED = "Upload avatar failed!";
    public static string $REFRESH_TOKEN_SUCCESS = "Get refresh token success!";
    public static string $REFRESH_TOKEN_FAILED = "Get refresh token failded!";
    public static string $MEMBER_NOT_EXIST = "Member does not exist";
    public static string $MEMBER_VERIFIED_EMAIL = "Member has been verified email";
    public static string $OTP_IS_STILL_USABLE = "The member has been sent an otp code before and the code is still valid";
    public static string $INVALID_OTP_CODE = "Invalid otp code";
    public static string $WRONG_LOGIN = "Wrong username or password";
    public static string $EMAIL_NOT_VERIFIED = "Email not confirmed. The system has sent you an email. Please go to your email to receive the verification code!";
    public static string $ACCOUNT_LOCKED = "This account has been locked";
    public static string $YOU_LOGGED = "You have logged";
    public static string $EMAIL_NOT_EXIST = "Email does not exist";
    public static string $EMAIL_EXIST = "Email does exist";
    public static string $WRONG_OLD_PASSWORD = "Wrong old password";
    public static string $INVALID_TOKEN = "Invalid token";
    #endregion

    #region User
    public static string $CREATE_USER_SUCCESS = "Create user success!";
    public static string $CREATE_USER_FAILED = "Create user failed!";
    public static string $UPDATE_USER_SUCCESS = "Update user success!";
    public static string $UPDATE_USER_FAILED = "Update user failed!";
    public static string $DELETE_USER_SUCCESS = "Delete user success!";
    public static string $DELETE_USER_FAILED = "Delete user failed!";
    public static string $GET_USER_SUCCESS = "Get user success!";
    public static string $GET_USER_FAILED = "Get user failed!";
    public static string $GET_LIST_USER_SUCCESS = "Get list user success!";
    public static string $GET_LIST_USER_FAILED = "Get list user failed!";
    public static string $USER_NOT_EXIST = "User does not exist";
    public static string $USER_EXIST = "User does exist";
    public static string $LOCK_USER_SUCCESS = "Lock user success!";
    public static string $LOCK_USER_FAILED = "Lock user failed!";
    public static string $UNLOCK_USER_SUCCESS = "Unlock user success!";
    public static string $UNLOCK_USER_FAILED = "Unlock user failed!";
    #endregion
}
