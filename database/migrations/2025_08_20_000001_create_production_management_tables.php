<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // プロジェクトテーブル
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('プロジェクトコード');
            $table->string('name')->comment('プロジェクト名');
            $table->string('client_name')->comment('クライアント名');
            $table->date('start_date')->comment('開始日');
            $table->date('end_date')->comment('終了日');
            $table->date('estimated_completion_date')->nullable()->comment('予定完了日');
            $table->date('actual_completion_date')->nullable()->comment('実際の完了日');
            $table->integer('planned_hours')->comment('計画工数（時間）');
            $table->decimal('contract_amount', 12, 2)->comment('受注金額');
            $table->decimal('external_cost_budget', 12, 2)->default(0)->comment('外注費予算');
            $table->decimal('external_cost_actual', 12, 2)->default(0)->comment('外注費実績');
            $table->enum('status', ['planned', 'active', 'on_hold', 'completed', 'canceled'])->default('planned')->comment('ステータス');
            $table->enum('progress_method', ['hours_based', 'milestone_based'])->default('hours_based')->comment('進捗管理方法');
            $table->bigInteger('project_manager_id')->unsigned()->nullable()->comment('プロジェクトマネージャー');
            $table->text('description')->nullable()->comment('備考');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'start_date']);
            $table->index('end_date');
        });

        // 従業員テーブル（生産管理用）
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique()->comment('社員コード');
            $table->string('name')->comment('氏名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->enum('role', ['admin', 'manager', 'member'])->default('member')->comment('権限');
            $table->boolean('active')->default(true)->comment('有効フラグ');
            $table->integer('standard_hours_per_month')->default(160)->comment('想定稼働時間（月）');
            $table->decimal('hourly_rate', 8, 2)->nullable()->comment('時給');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'freelance'])->default('full_time')->comment('雇用形態');
            $table->enum('visibility_scope', ['normal', 'financial_restricted'])->default('normal')->comment('表示権限');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['active', 'role']);
        });

        // 従業員給与履歴テーブル
        Schema::create('employee_compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('salary_monthly', 12, 2)->comment('月給');
            $table->date('effective_from')->comment('適用開始日');
            $table->date('effective_to')->nullable()->comment('適用終了日');
            $table->decimal('overhead_rate', 5, 4)->default(0.2)->comment('上乗せ率');
            $table->text('memo')->nullable()->comment('備考');
            $table->timestamps();

            $table->index(['employee_id', 'effective_from']);
        });

        // プロジェクトメンバー配属テーブル
        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('member_role')->comment('プロジェクト内役割');
            $table->integer('allocation_rate')->default(100)->comment('配分率（%）');
            $table->date('start_date')->comment('配属開始日');
            $table->date('end_date')->nullable()->comment('配属終了日');
            $table->timestamps();

            $table->unique(['project_id', 'employee_id', 'start_date']);
            $table->index(['project_id', 'employee_id']);
        });

        // 工数ログテーブル
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('work_date')->comment('作業日');
            $table->integer('duration_minutes')->comment('稼働時間（分）');
            $table->integer('break_time_minutes')->default(0)->comment('休憩時間（分）');
            $table->datetime('started_at')->nullable()->comment('開始時刻');
            $table->datetime('ended_at')->nullable()->comment('終了時刻');
            $table->enum('category', ['dev', 'design', 'mtg', 'test', 'ops', 'other'])->comment('作業カテゴリ');
            $table->string('task_title')->comment('作業タイトル');
            $table->text('note')->nullable()->comment('備考');
            $table->boolean('is_billable')->default(true)->comment('請求対象フラグ');
            $table->boolean('overtime_flag')->default(false)->comment('残業フラグ');
            $table->bigInteger('approved_by')->unsigned()->nullable()->comment('承認者');
            $table->datetime('approved_at')->nullable()->comment('承認日時');
            $table->bigInteger('created_by')->unsigned()->nullable()->comment('登録者（代理入力対応）');
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('employees')->onDelete('set null');
            $table->index(['project_id', 'work_date']);
            $table->index(['employee_id', 'work_date']);
            $table->index('work_date');
        });

        // 外注費明細テーブル
        Schema::create('external_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('vendor_name')->comment('発注先');
            $table->enum('category', ['dev', 'design', 'qa', 'translation', 'other'])->comment('カテゴリ');
            $table->decimal('amount', 12, 2)->comment('金額');
            $table->boolean('tax_excluded')->default(true)->comment('税抜フラグ');
            $table->date('invoice_date')->comment('請求日');
            $table->datetime('paid_at')->nullable()->comment('支払日');
            $table->text('memo')->nullable()->comment('備考');
            $table->json('attachments')->nullable()->comment('添付ファイル');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'invoice_date']);
        });

        // Help要請テーブル
        Schema::create('help_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('assignee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('title')->comment('要請タイトル');
            $table->text('description')->comment('詳細');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium')->comment('重要度');
            $table->enum('status', ['open', 'triaged', 'in_progress', 'resolved', 'wont_fix', 'canceled'])->default('open')->comment('ステータス');
            $table->boolean('blocker')->default(false)->comment('進捗阻害フラグ');
            $table->bigInteger('related_work_log_id')->unsigned()->nullable()->comment('関連工数ID');
            $table->datetime('sla_due_at')->nullable()->comment('期限');
            $table->datetime('resolved_at')->nullable()->comment('解決日時');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('related_work_log_id')->references('id')->on('work_logs')->onDelete('set null');
            $table->index(['project_id', 'status']);
            $table->index(['assignee_id', 'status']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_requests');
        Schema::dropIfExists('external_costs');
        Schema::dropIfExists('work_logs');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('employee_compensations');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('projects');
    }
};