<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>従業員アカウント作成のお知らせ</title>
    <style>
        body {
            font-family: 'Hiragino Sans', 'ヒラギノ角ゴ ProN W3', 'Hiragino Kaku Gothic ProN', 'メイリオ', Meiryo, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 24px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .login-info {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
        }
        .login-info h3 {
            margin-top: 0;
            color: #1e40af;
        }
        .credential-item {
            margin: 10px 0;
            padding: 8px;
            background-color: #ffffff;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        .credential-label {
            font-weight: bold;
            color: #374151;
            display: block;
            margin-bottom: 4px;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background-color: #f9fafb;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 14px;
            border: 1px solid #e5e7eb;
        }
        .login-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .login-button:hover {
            background-color: #1d4ed8;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .warning-icon {
            color: #d97706;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .company-name {
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TeamManage</h1>
        <p>プロジェクト管理システム</p>
    </div>

    <div class="content">
        <h2>従業員アカウント作成完了のお知らせ</h2>
        
        <p>{{ $employee->name }} 様</p>
        
        <p>
            この度は、TeamManageの従業員として登録いただき、ありがとうございます。<br>
            あなたの従業員アカウントとログイン用のユーザーアカウントが作成されました。
        </p>

        <div class="login-info">
            <h3>🔐 ログイン情報</h3>
            
            <div class="credential-item">
                <span class="credential-label">メールアドレス:</span>
                <div class="credential-value">{{ $employee->email }}</div>
            </div>
            
            <div class="credential-item">
                <span class="credential-label">パスワード:</span>
                <div class="credential-value">{{ $password }}</div>
            </div>
            
            <div class="credential-item">
                <span class="credential-label">従業員コード:</span>
                <div class="credential-value">{{ $employee->employee_code }}</div>
            </div>
            
            <div class="credential-item">
                <span class="credential-label">権限:</span>
                <div class="credential-value">
                    @if($employee->role === 'admin')
                        管理者
                    @elseif($employee->role === 'manager')
                        マネージャー
                    @else
                        メンバー
                    @endif
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="login-button">
                🚀 ログインする
            </a>
        </div>

        <div class="warning">
            <span class="warning-icon">⚠️ セキュリティについて</span>
            <ul style="margin: 10px 0;">
                <li>パスワードは他人に教えないでください</li>
                <li>初回ログイン後は、セキュリティのためパスワードの変更をお勧めします</li>
                <li>このメールは他人に転送しないでください</li>
            </ul>
        </div>

        <h3>🛠️ TeamManageでできること</h3>
        <ul>
            <li><strong>工数管理:</strong> プロジェクトの作業時間を記録</li>
            <li><strong>進捗確認:</strong> プロジェクトの進捗状況をリアルタイムで確認</li>
            <li><strong>Help要請:</strong> 困った時のサポート要請</li>
            <li><strong>レポート:</strong> 作業実績とプロジェクト状況の確認</li>
        </ul>

        <p>
            ご不明な点がございましたら、システム管理者までお問い合わせください。<br>
            TeamManageを通じて、効率的なプロジェクト管理を実現しましょう！
        </p>
    </div>

    <div class="footer">
        <p>
            <span class="company-name">TeamManage</span> プロジェクト管理システム<br>
            このメールは自動送信されています。返信はできません。
        </p>
    </div>
</body>
</html>