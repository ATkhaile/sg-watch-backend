# How to setup
## 1.First step
### install app's dependencies
```bash
# You need setting gd, zip, sodium in php.ini
$ istall php >= 8.2
$ composer install
```

You may need to install the following packages if they are not already installed in your project:
```bash
composer require tymon/jwt-auth:^2.1
```

### Edit env
```bash
$ cp .env.example .env
```

### Edit connect database
```
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=root
```

## 2.Next step
```bash
# in your app directory
# create storage symboric link
$ php artisan storage:link

# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate:refresh --seed

#run permission required
$ php artisan db:seed --class=Database\\Seeders\\ElcCommunity\\CommunityMasterSeeder

BASE_FRONTEND_URL="https://elemental.demo-dev.xyz/"
BASE_FRONTEND_URL_FORGET_PASSWORD="https://elemental.demo-dev.xyz/forget-password"
BASE_FRONTEND_URL_VERIFY_REGISTRATION="https://elemental.demo-dev.xyz/verify-registration"

# Email verification login
ENABLE_EMAIL_2FA=false
EMAIL_2FA_CODE_EXPIRE=15 # minutes

# Email verification register
ENABLE_EMAIL_VERIFICATION=false
EMAIL_VERIFICATION_TIMEOUT=15 # minutes

# login with account user@gmail.com/Laravel@2025

# generate jwt secret
$ php artisan jwt:secret
```
fin.

## 3.deploy
```bash
# update sorce code
git pull

# update DB
php artisan migrate
php artisan db:seed

or

php artisan migrate --seed

# When you want real seed data
php artisan db:seed --class=Database\\Seeders\\ElcCommunity\\CommunityMasterSeeder

# clear cache
php artisan optimize:clear
```

# Appendix
## How to setup Location Data
please download [Package file(shops.zip)](https://drive.google.com/drive/folders/1dQ0_ViGm9fIzqGLDZxV56i3kK3px_rCI?usp=drive_link)

```bash
# img shop location
$ unzip shops.zip -d storage/app/public

$ php artisan db:seed --class=ShopsTableSeeder && php artisan db:seed --class=OptionsTableSeeder
```

# ガイドライン
## 開発
- 「JWTのステートレス」と「セッション管理」の合わせ技によってAPI接続元とのセッションを管理


Cách chạy project
1. Cài dependencies

cd e:\JANPAN_CHAUTHAISON_SOURCE\DU_AN_FREELANCER\SGWatch\Backend\elemental-cloud
composer install
2. Tạo file .env

copy .env.example .env
Sau đó sửa .env với thông tin database của bạn:

DB_HOST=127.0.0.1
DB_PORT=3306 (MySQL 8.0 mặc định, .env.example đang để 8889 của MAMP)
DB_DATABASE= (tên database bạn muốn)
DB_USERNAME=root
DB_PASSWORD= (password MySQL của bạn)
3. Tạo APP_KEY

php artisan key:generate
4. Tạo JWT Secret

php artisan jwt:secret
5. Tạo database và chạy migration

php artisan migrate
6. Tạo storage link (cho upload file)

php artisan storage:link
7. Chạy server

php artisan serve
Server sẽ chạy tại http://localhost:8000