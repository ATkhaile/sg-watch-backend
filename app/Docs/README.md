# API Documentation Guide
https://packagist.org/packages/darkaonline/l5-swagger

## Generate

```bash
php artisan l5-swagger:generate
```
APP_URL/api/documentation (https://elemental-cloud.demo-dev.xyz/api/documentation)

### Structure
```
app/
├── Docs/
│   ├── OpenApiInfo.php
│   └── Actions/
│       └── Api/
│           ├── News/
│           │   ├── CreateNewsAction.php
│           │   ├── GetAllNewsAction.php
│           │   ├── GetNewsDetailAction.php
│           │   └── ... (other file.php)
│           ├── Auth/
│           ├── Category/
│           └── ... (other domain)
```