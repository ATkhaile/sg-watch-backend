-- work_logsテーブルに新しいカラムを追加するSQL
-- 既存データを保持したままカラムを追加します

-- role_percentagesカラムを追加（JSON型、NULL許可）
ALTER TABLE work_logs 
ADD COLUMN role_percentages JSON NULL 
COMMENT '役割別工数配分（DS,QA,PM,PMO,PG,SE）' 
AFTER category;

-- primary_roleカラムを追加（ENUM型、NULL許可）
ALTER TABLE work_logs 
ADD COLUMN primary_role ENUM('DS', 'QA', 'PM', 'PMO', 'PG', 'SE') NULL 
COMMENT '主要役割' 
AFTER role_percentages;

-- 既存データの確認
SELECT id, category, role_percentages, primary_role, task_title 
FROM work_logs 
LIMIT 5;