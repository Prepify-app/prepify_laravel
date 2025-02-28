<?php

namespace App\Services;

class ResponseService
{
    // Auth related messages
    public const INVALID_CREDENTIALS = 'Invalid credentials';
    public const USER_CREATION_ERROR = 'Something went wrong while trying to create user';
    public const LOGOUT_SUCCESS = 'Successfully logged out';

    // Profile related messages
    public const PROFILE_UPDATED = 'Profile updated successfully';
    public const ACCOUNT_DELETED = 'Account deleted successfully';

    // Password related messages
    public const PASSWORD_MISMATCH = "Password didn't match the current password";
    public const PASSWORD_UPDATED = 'Password updated successfully';
    public const PASSWORD_UPDATE_ERROR = 'An error occurred while updating password';
    public const PASSWORD_RESET_SUCCESS = 'Password reset successfully';
    public const PASSWORD_RESET_LINK_SENT = 'Reset link sent successfully';
    public const INVALID_RESET_TOKEN = 'Token is invalid or expired';

    // Email verification messages
    public const VERIFICATION_LINK_SENT = 'Verification link has been sent';
    public const USER_NOT_FOUND = 'User not found';
    public const EMAIL_ALREADY_VERIFIED = 'Email is already verified';
    public const EMAIL_VERIFIED = 'Email has been verified successfully';
    public const INVALID_VERIFICATION = 'Invalid verification request';
    public const INVALID_VERIFICATION_TOKEN = 'Invalid or expired token.';
} 