<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $employee->name }} - {{ $year_month }} 月報</title>
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #2c3e50;
        }

        .header h1 {
            font-size: 16pt;
            margin: 0 0 4px 0;
            color: #2c3e50;
        }

        .header .subtitle {
            font-size: 10pt;
            color: #7f8c8d;
            margin: 4px 0;
        }

        .summary {
            background-color: #f5f5f5;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-grid td {
            width: 33.33%;
            text-align: center;
            padding: 5px;
        }

        .summary-label {
            font-size: 8pt;
            color: #666;
            display: block;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 13pt;
            font-weight: bold;
            color: #2c3e50;
        }

        .project-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .project-header {
            background-color: #3498db;
            color: white;
            padding: 8px 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            font-size: 11pt;
            font-weight: bold;
        }

        .work-log-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 7pt;
            table-layout: fixed;
        }

        .work-log-table th {
            background-color: #34495e;
            color: white;
            padding: 3px;
            text-align: left;
            font-size: 7pt;
            font-weight: bold;
        }

        .work-log-table td {
            padding: 2px 3px;
            border-bottom: 1px solid #ddd;
            font-size: 7pt;
            vertical-align: top;
        }

        .work-log-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .date-column {
            width: 10%;
            white-space: nowrap;
            font-size: 7pt;
        }

        .hours-column {
            width: 6%;
            text-align: right;
            font-size: 7pt;
        }

        .category-column {
            width: 7%;
            font-size: 7pt;
        }

        .role-column {
            width: 6%;
            font-size: 7pt;
        }

        .task-column {
            width: 42%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .note-column {
            width: 29%;
            font-size: 7pt;
            color: #666;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .category-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 6pt;
            color: white;
        }

        .category-dev { background-color: #3498db; }
        .category-design { background-color: #9b59b6; }
        .category-mtg { background-color: #e67e22; }
        .category-test { background-color: #27ae60; }
        .category-ops { background-color: #34495e; }
        .category-other { background-color: #95a5a6; }

        .project-total {
            text-align: right;
            font-weight: bold;
            padding: 8px 0;
            border-top: 2px solid #34495e;
            color: #2c3e50;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #bdc3c7;
            text-align: right;
            font-size: 8pt;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $year_month }} 業務月報</h1>
        <div class="subtitle">{{ $employee->name }}（{{ $employee->employee_code }}）</div>
    </div>

    <div class="summary">
        <table class="summary-grid">
            <tr>
                <td>
                    <span class="summary-label">総稼働時間</span>
                    <div class="summary-value">{{ $total_hours }}h</div>
                </td>
                <td>
                    <span class="summary-label">稼働日数</span>
                    <div class="summary-value">{{ $total_days }}日</div>
                </td>
                <td>
                    <span class="summary-label">参画プロジェクト</span>
                    <div class="summary-value">{{ $project_count }}件</div>
                </td>
            </tr>
        </table>
    </div>

    @foreach($project_groups as $projectId => $logs)
        @php
            $project = $logs->first()->project;
            $projectHours = $logs->sum('duration_minutes') / 60;
        @endphp

        <div class="project-section">
            <div class="project-header">
                {{ $project->code }} - {{ $project->name }}
            </div>

            <table class="work-log-table">
                <thead>
                    <tr>
                        <th class="date-column">日付</th>
                        <th class="hours-column">時間</th>
                        <th class="category-column">カテゴリ</th>
                        <th class="role-column">役割</th>
                        <th class="task-column">作業タイトル</th>
                        <th class="note-column">備考</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td class="date-column">{{ \Carbon\Carbon::parse($log->work_date)->format('Y-m-d (D)') }}</td>
                            <td class="hours-column">{{ number_format($log->duration_minutes / 60, 1) }}h</td>
                            <td class="category-column">
                                <span class="category-badge category-{{ $log->category }}">
                                    {{ strtoupper($log->category) }}
                                </span>
                            </td>
                            <td class="role-column">{{ $log->primary_role ?? '-' }}</td>
                            <td class="task-column">{{ $log->task_title }}</td>
                            <td class="note-column">{{ $log->note ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="project-total">
                プロジェクト合計: {{ number_format($projectHours, 1) }}時間
            </div>
        </div>
    @endforeach

    <div class="footer">
        出力日時: {{ $generated_at }}
    </div>
</body>
</html>
