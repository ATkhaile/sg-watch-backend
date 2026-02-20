<?php

return [
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'notfound' => 'User not found',
    'token_failed' => 'The provided token is invalid.',
    'token_expired' => 'The provided token has expired.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'login' => [
        'success' => 'Login successful',
        'user_password_failed' => 'User password is incorrect',
        'failed' => 'Login failed',
        'invalid_token' => 'Invalid token',
        'expired_token' => 'Token has expired',
        'user_not_found' => 'User not found',
        'lat_login_updated_failed' => 'Failed to update last login time.',
        'verification_code_sent' => 'Verification code sent',
        'verification_code_invalid' => 'Invalid verification code'
    ],
    'logout' => [
        'success' => 'Logout successful',
        'failed' => 'Logout failed',
    ],
    'change_password' => [
        'notfound' => 'User not found',
        'failed' => 'Failed to change password',
        'success' => 'Password has been changed',
    ],
    'reset_token' => [
       'invalid' => 'Invalid token',
       'success' => 'Password reset successful',
    ],
    'forgot_password' => [
        'success' => 'Password reset link sent',
        'failed' => 'Failed to send password reset link',
        'notfound' => 'User not found',
    ],
    'register' => [
        'success' => 'Registration successful',
        'failed' => 'Registration failed',
        'email_verification_sent' => 'Verification email sent',
        'email_verification_success' => 'Email verification completed',
        'email_verification_failed' => 'Failed to create email verification',
        'email_verification_error' => 'An error occurred during email verification',
        'affiliate_token_invalid' => 'Affiliate token is invalid.',
        'affiliate_token_used_or_expired' => 'Affiliate token has already been used or is expired.'
    ],
    'reset_password' => [
        'success' => 'Password has been reset',
        'failed' => 'Failed to reset password',
        'notfound' => 'User not found',
    ],
    'password' => [
        'reset_success' => 'Password has been reset',
        'reset_failed' => 'Failed to reset password',
        'change_success' => 'Password has been changed',
        'change_failed' => 'Failed to change password',
        'old_password_incorrect' => 'Old password is incorrect'
    ],
    'validation' => [
        'password_old' => [
            'required' => '※Password is required',
            'min' => '※Password must be at least 8 characters',
            'max' => '※Password must be at most 16 characters',
            'confirmed' => '※Password confirmation does not match',
        ],
        'password_confirmation' => [
            'required' => '※Password confirmation is required',
            'same' => '※Password confirmation does not match',
        ],
        'password' => [
            'required' => '※Password is required',
            'min' => '※Password must be at least 8 characters',
            'max' => '※Password must be at most 16 characters',
            'confirmed' => '※Password confirmation does not match',
        ],
        'first_name' => [
            'required' => '※First name is required',
            'string' => '※First name must be a string',
            'max' => '※First name must be at most 50 characters',
        ],
        'last_name' => [
            'required' => '※Last name is required',
            'string' => '※Last name must be a string',
            'max' => '※Last name must be at most 50 characters',
        ],
        'email' => [
            'required' => '※Email is required',
            'email' => '※Invalid email format',
            'notfound' => '※User not found',
            'exists' => '※Email already exists',
            'unique' => '※Email already exists',
            'required_without' => '※Email is required',
        ],
        'verification_code' => [
            'string' => '※Verification code must be a string'
        ],
        'invite_code' => [
            'string' => '※Invite code must be a string',
            'max' => '※Invite code must be at most 255 characters',
            'exists' => '※Invalid invite code',
        ],
        'gender' => [
            'in' => '※Gender must be one of: male, female, other, unknown',
        ],
        'birthday' => [
            'date' => '※Birthday must be a valid date',
            'date_format' => '※Birthday must be in YYYY-MM-DD format',
            'before' => '※Birthday must be before today',
        ],
        'avatar_url' => [
            'max' => '※Avatar URL must be at most 500 characters',
        ],
    ],
    'update_profile' => [
        'success' => 'Profile updated successfully',
        'failed' => 'Failed to update profile',
    ],
    'password_otp' => [
        'sent' => 'If the email exists, a verification code has been sent.',
        'invalid' => 'Invalid or expired verification code.',
        'too_many_attempts' => 'Too many attempts. Please request a new code.',
        'verified' => 'Verification successful.',
    ],
    'unauthorized' => 'This action is unauthorized.',
];
