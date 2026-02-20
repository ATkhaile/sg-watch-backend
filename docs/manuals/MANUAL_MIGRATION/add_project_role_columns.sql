-- projectsテーブルに役割別工数配分カラムを追加するSQL
-- プロジェクト作成・編集時に役割別の予算配分を設定

-- role_budget_percentagesカラムを追加（JSON型、NULL許可）
ALTER TABLE projects 
ADD COLUMN role_budget_percentages JSON NULL 
COMMENT '役割別工数予算配分（DS,QA,PM,PMO,PG,SE）' 
AFTER planned_hours;

-- 既存データの確認
SELECT id, code, name, planned_hours, role_budget_percentages, contract_amount 
FROM projects 
LIMIT 5;