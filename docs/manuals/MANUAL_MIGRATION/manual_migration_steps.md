# 手動でmigrationを実行する方法

## 前提条件
- 既存のデータを保持したい
- migrationファイルが実行できない環境

## 手順

### 1. バックアップを取る（必須）
```sql
-- 念のため既存データをバックアップ
CREATE TABLE work_logs_backup AS SELECT * FROM work_logs;
```

### 2. migrationテーブルの確認
```sql
-- migrationテーブルの状態を確認
SELECT * FROM migrations ORDER BY batch DESC LIMIT 5;
```

### 3. カラム追加SQL実行
```sql
-- role_percentagesカラムを追加
ALTER TABLE work_logs 
ADD COLUMN role_percentages JSON NULL 
COMMENT '役割別工数配分（DS,QA,PM,PMO,PG,SE）' 
AFTER category;

-- primary_roleカラムを追加  
ALTER TABLE work_logs 
ADD COLUMN primary_role ENUM('DS', 'QA', 'PM', 'PMO', 'PG', 'SE') NULL 
COMMENT '主要役割' 
AFTER role_percentages;
```

### 4. migration記録を手動追加
```sql
-- migrationテーブルに手動でレコードを追加
INSERT INTO migrations (migration, batch) 
VALUES ('2025_10_08_203158_add_role_percentages_to_work_logs_table', 
        (SELECT COALESCE(MAX(batch), 0) + 1 FROM migrations AS m));
```

### 5. 結果確認
```sql
-- テーブル構造を確認
DESCRIBE work_logs;

-- 新しいカラムが正しく追加されているか確認
SELECT 
    COLUMN_NAME, 
    COLUMN_TYPE, 
    IS_NULLABLE, 
    COLUMN_DEFAULT, 
    COLUMN_COMMENT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'work_logs' 
    AND COLUMN_NAME IN ('role_percentages', 'primary_role');
```

## 注意事項

1. **必ずバックアップを取る**
   - 作業前に必ずデータベースのバックアップを取得

2. **本番環境では慎重に**
   - 可能であれば開発環境で先にテスト

3. **Laravelアプリケーションの再起動**
   - カラム追加後、アプリケーションを再起動
   - キャッシュをクリア: `php artisan cache:clear`

4. **既存データの確認**
   - 新しいカラムは最初は全てNULLになる
   - フロントエンドでNULL値に対する適切な処理が必要