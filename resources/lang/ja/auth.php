<?php

return [
    'failed' => 'Thông tin đăng nhập không chính xác.',
    'password' => 'Mật khẩu không chính xác.',
    'notfound' => 'Không tìm thấy người dùng',
    'token_failed' => 'Token không hợp lệ',
    'token_expired' => 'Token đã hết hạn',
    'throttle' => 'Đăng nhập quá nhiều lần. Vui lòng thử lại sau :seconds giây.',
    'login' => [
        'success' => 'Đăng nhập thành công',
        'user_password_failed' => 'Email hoặc mật khẩu không chính xác',
        'failed' => 'Đăng nhập thất bại',
        'invalid_token' => 'Token không hợp lệ',
        'expired_token' => 'Token đã hết hạn',
        'user_not_found' => 'Không tìm thấy người dùng',
        'lat_login_updated_failed' => 'Cập nhật thời gian đăng nhập thất bại.',
        'verification_code_sent' => 'Đã gửi mã xác nhận',
        'verification_code_invalid' => 'Mã xác nhận không hợp lệ'
    ],
    'logout' => [
        'success' => 'Đăng xuất thành công',
        'failed' => 'Đăng xuất thất bại'
    ],
    'change_password' => [
        'notfound' => 'Không tìm thấy người dùng',
        'failed' => 'Đổi mật khẩu thất bại',
        'success' => 'Đổi mật khẩu thành công',
    ],
    'reset_token' => [
        'invalid' => 'Token không hợp lệ',
        'success' => 'Đặt lại mật khẩu thành công'
    ],
    'forgot_password' => [
        'success' => 'Đặt lại mật khẩu thành công',
        'failed' => 'Đặt lại mật khẩu thất bại',
        'notfound' => 'Không tìm thấy người dùng',
    ],
    'register' => [
        'success' => 'Đăng ký thành công',
        'failed' => 'Đăng ký thất bại',
        'email_verification_sent' => 'Đã gửi email xác nhận',
        'email_verification_success' => 'Xác nhận email thành công',
        'email_verification_failed' => 'Xác nhận email thất bại',
        'email_verification_error' => 'Lỗi trong quá trình xác nhận email',
        'affiliate_token_invalid' => 'Mã giới thiệu không hợp lệ.',
        'affiliate_token_used_or_expired' => 'Mã giới thiệu đã được sử dụng hoặc hết hạn.'
    ],
    'reset_password' => [
        'success' => 'Đặt lại mật khẩu thành công',
        'failed' => 'Đặt lại mật khẩu thất bại',
        'notfound' => 'Không tìm thấy người dùng',
    ],
    'password' => [
        'reset_success' => 'Đặt lại mật khẩu thành công',
        'reset_failed' => 'Đặt lại mật khẩu thất bại',
        'change_success' => 'Đổi mật khẩu thành công',
        'change_failed' => 'Đổi mật khẩu thất bại',
        'old_password_incorrect' => 'Mật khẩu cũ không chính xác'
    ],
    'validation' => [
        'password_old' => [
            'required' => 'Vui lòng nhập mật khẩu.',
            'min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'max' => 'Mật khẩu tối đa 16 ký tự.',
            'confirmed' => 'Mật khẩu xác nhận không khớp.',
        ],
        'password' => [
            'required' => 'Vui lòng nhập mật khẩu.',
            'min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'max' => 'Mật khẩu tối đa 16 ký tự.',
            'confirmed' => 'Mật khẩu xác nhận không khớp.',
        ],
        'password_confirmation' => [
            'required' => 'Vui lòng nhập mật khẩu xác nhận.',
            'same' => 'Mật khẩu xác nhận không khớp.',
        ],
        'first_name' => [
            'required' => 'Vui lòng nhập tên.',
            'string' => 'Tên phải là chuỗi ký tự.',
            'max' => 'Tên tối đa 50 ký tự.',
        ],
        'last_name' => [
            'required' => 'Vui lòng nhập họ.',
            'string' => 'Họ phải là chuỗi ký tự.',
            'max' => 'Họ tối đa 50 ký tự.',
        ],
        'email' => [
            'required' => 'Vui lòng nhập email.',
            'email' => 'Vui lòng nhập email hợp lệ.',
            'not_exists' => 'Email chưa được đăng ký.',
            'exists' => 'Email đã được đăng ký.',
            'unique' => 'Email đã được đăng ký.',
            'required_without' => 'Vui lòng nhập email.',
        ],
        'verification_code' => [
            'required' => 'Vui lòng nhập mã xác nhận.',
            'string' => 'Mã xác nhận phải là chuỗi ký tự.',
        ],
        'invite_code' => [
            'string' => 'Mã mời phải là chuỗi ký tự',
            'max' => 'Mã mời tối đa 255 ký tự',
            'exists' => 'Mã mời không hợp lệ',
        ],
        'gender' => [
            'in' => 'Giới tính phải là male, female, other hoặc unknown',
        ],
        'birthday' => [
            'date' => 'Ngày sinh phải là ngày hợp lệ',
            'date_format' => 'Ngày sinh phải có định dạng YYYY-MM-DD',
            'before' => 'Ngày sinh phải trước ngày hôm nay',
        ],
        'avatar_url' => [
            'max' => 'URL avatar tối đa 500 ký tự',
        ],
    ],
    'update_profile' => [
        'success' => 'Cập nhật hồ sơ thành công',
        'failed' => 'Cập nhật hồ sơ thất bại',
    ],
    'password_otp' => [
        'sent' => 'Nếu email tồn tại, mã xác nhận đã được gửi.',
        'invalid' => 'Mã xác nhận không đúng hoặc đã hết hạn.',
        'too_many_attempts' => 'Nhập sai quá nhiều lần. Vui lòng yêu cầu mã mới.',
        'verified' => 'Xác nhận thành công.',
        'send_failed' => 'Gửi mã xác nhận thất bại.',
    ],
    'unauthorized' => 'Bạn không có quyền truy cập.',
];
