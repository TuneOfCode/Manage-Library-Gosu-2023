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
    public static string $ALREADY_LOGIN = "You have already logged in";
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

    #region Package
    public static string $CREATE_PACKAGE_SUCCESS = "Create package success!";
    public static string $CREATE_PACKAGE_FAILED = "Create package failed!";
    public static string $UPDATE_PACKAGE_SUCCESS = "Update package success!";
    public static string $UPDATE_PACKAGE_FAILED = "Update package failed!";
    public static string $DELETE_PACKAGE_SUCCESS = "Delete package success!";
    public static string $DELETE_PACKAGE_FAILED = "Delete package failed!";
    public static string $GET_PACKAGE_SUCCESS = "Get package success!";
    public static string $GET_PACKAGE_FAILED = "Get package failed!";
    public static string $GET_LIST_PACKAGE_SUCCESS = "Get list package success!";
    public static string $GET_LIST_PACKAGE_FAILED = "Get list package failed!";
    public static string $PACKAGE_NOT_EXIST = "Package does not exist";
    public static string $REGISTER_PACKAGE_SUCCESS = "Register package success!";
    public static string $REGISTER_PACKAGE_FAILED = "Register package failed!";
    public static string $PACKAGE_ALREADY_REGISTERED = "Package already registered";
    public static string $NOT_ENOUGH_BALANCE = "Your balance is not enough to register this package";
    public static string $PACKAGE_IS_USING = "Package is using";
    #endregion

    #region Category
    public static string $CREATE_CATEGORY_SUCCESS = "Create category success!";
    public static string $CREATE_CATEGORY_FAILED = "Create category failed!";
    public static string $UPDATE_CATEGORY_SUCCESS = "Update category success!";
    public static string $UPDATE_CATEGORY_FAILED = "Update category failed!";
    public static string $DELETE_CATEGORY_SUCCESS = "Delete category success!";
    public static string $DELETE_CATEGORY_FAILED = "Delete category failed!";
    public static string $GET_CATEGORY_SUCCESS = "Get category success!";
    public static string $GET_CATEGORY_FAILED = "Get category failed!";
    public static string $GET_LIST_CATEGORY_SUCCESS = "Get list category success!";
    public static string $GET_LIST_CATEGORY_FAILED = "Get list category failed!";
    public static string $CATEGORY_NOT_EXIST = "Category does not exist";
    public static string $CATEGORY_EXIST = "Category does exist";
    #endregion

    #region Book
    public static string $CREATE_BOOK_SUCCESS = "Create book success!";
    public static string $CREATE_BOOK_FAILED = "Create book failed!";
    public static string $UPDATE_BOOK_SUCCESS = "Update book success!";
    public static string $UPDATE_BOOK_FAILED = "Update book failed!";
    public static string $DELETE_BOOK_SUCCESS = "Delete book success!";
    public static string $DELETE_BOOK_FAILED = "Delete book failed!";
    public static string $GET_BOOK_SUCCESS = "Get book success!";
    public static string $GET_BOOK_FAILED = "Get book failed!";
    public static string $GET_LIST_BOOK_SUCCESS = "Get list book success!";
    public static string $GET_LIST_BOOK_FAILED = "Get list book failed!";
    public static string $BOOK_NOT_EXIST = "Book does not exist";
    public static string $BOOK_EXIST = "Book does exist";
    public static string $BOOK_IS_USING = "Book is using";
    public static string $BOOK_IS_NOT_USING = "Book is not using";
    public static string $BOOK_IS_NOT_AVAILABLE = "Book is not available";
    public static string $BOOK_IS_AVAILABLE = "Book is available";
    public static string $BOOK_IS_NOT_BORROWED = "Book is not borrowed";
    public static string $BOOK_IS_BORROWED = "Book is borrowed";
    public static string $BOOK_IS_NOT_RETURNED = "Book is not returned";
    public static string $BOOK_IS_RETURNED = "Book is returned";
    public static string $BOOK_IS_NOT_DELETED = "Book is not deleted";
    public static string $BOOK_IS_DELETED = "Book is deleted";
    public static string $BOOK_IS_NOT_RESTORED = "Book is not restored";
    public static string $BOOK_IS_RESTORED = "Book is restored";
    public static string $BOOK_IS_NOT_RENEWED = "Book is not renewed";
    public static string $BOOK_IS_RENEWED = "Book is renewed";
    public static string $BOOK_IS_NOT_REJECTED = "Book is not rejected";
    public static string $BOOK_IS_REJECTED = "Book is rejected";
    public static string $BOOK_IS_NOT_APPROVED = "Book is not approved";
    public static string $BOOK_IS_APPROVED = "Book is approved";
    public static string $BOOK_IS_NOT_REJECTED_BY_ADMIN = "Book is not rejected by admin";
    public static string $BOOK_IS_REJECTED_BY_ADMIN = "Book is rejected by admin";
    public static string $BOOK_IS_NOT_APPROVED_BY_ADMIN = "Book is not approved by admin";
    public static string $BOOK_IS_APPROVED_BY_ADMIN = "Book is approved by admin";
    #endregion

    #region BookUser
    public static string $GET_LIST_BOOK_USER_SUCCESS = "Get list history rent books success!";
    public static string $GET_LIST_BOOK_USER_FAILED = "Get list history rent books failed!";
    public static string $GET_BOOK_USER_SUCCESS = "Get history rent book success!";
    public static string $GET_BOOK_USER_FAILED = "Get history rent book failed!";
    public static string $BOOK_USER_NOT_EXIST = "Rent book does not exist";
    public static string $BOOK_USER_EXIST = "Rent book does exist";
    public static string $BORROW_BOOKS_SUCCESS = "Borrow books success!";
    public static string $BORROW_BOOKS_FAILED = "Borrow books failed!";
    public static string $RETURN_BOOKS_SUCCESS = "Return books success!";
    public static string $RETURN_BOOKS_FAILED = "Return books failed!";
    public static string $APPROVE_BOOKS_SUCCESS = "Approve books success!";
    public static string $APPROVE_BOOKS_FAILED = "Approve books failed!";
    public static string $REJECT_BOOKS_SUCCESS = "Reject books success!";
    public static string $REJECT_BOOKS_FAILED = "Reject books failed!";
    public static string $CANCEL_BOOKS_SUCCESS = "Cancel books success!";
    public static string $CANCEL_BOOKS_FAILED = "Cancel books failed!";
    public static string $PAY_BOOKS_SUCCESS = "Pay books success!";
    public static string $PAY_BOOKS_FAILED = "Pay books failed!";
    public static string $CONFIRM_RECEIVED_BOOKS_SUCCESS = "Confirm received books success!";
    public static string $CONFIRM_RECEIVED_BOOKS_FAILED = "Confirm received books failed!";
    public static string $PAY_EXTRA_MONEY_SUCCESS = "Pay extra money success!";
    public static string $PAY_EXTRA_MONEY_FAILED = "Pay extra money failed!";
    public static string $MEMBER_NOT_ENOUGH_SCORE = "Member is not enough score";
    public static string $MEMBER_NOT_ENOUGH_BALANCE = "Member is not enough balance";
    public static string $MEMBER_NOT_VIP = "Package of member is not vip";
    public static string $BOOK_NOT_ENOUGH_QUANTITY = "Book is not enough quantity";
    public static string $MEMBER_BORROWING_BOOKS_LIMIT = "Member is borrowing books limit";
    public static string $INVALD_STATUS = "Invalid status";
    public static string $DELETE_BOOK_USER_SUCCESS = "Delete history rent books success!";
    public static string $DELETE_BOOK_USER_FAILED = "Delete history rent books failed!";
    #endregion
}
