-- 緊急：プロジェクトテーブルに役割別予算配分カラムを追加
-- このSQLを実行してからプロジェクト編集をテストしてください

-- 1. カラムが既に存在するか確認
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'projects' 
  AND COLUMN_NAME = 'role_budget_percentages';

-- 2. カラムが存在しない場合、以下を実行
ALTER TABLE projects 
ADD COLUMN role_budget_percentages JSON NULL 
COMMENT '役割別工数予算配分（DS,QA,PM,PMO,PG,SE）' 
AFTER planned_hours;

-- 3. 結果確認
DESCRIBE projects;

-- 4. 既存データの確認
SELECT id, code, name, planned_hours, role_budget_percentages, contract_amount 
FROM projects 
LIMIT 3;