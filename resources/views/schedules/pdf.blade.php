<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Schedules - {{ $month }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ac7909;
            margin: 0;
            font-size: 22px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #ac7909;
            font-size: 14px;
        }
        .project-name {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        .project-details {
            color: #666;
            font-size: 13px;
        }
        .text-center {
            text-align: center;
        }
        .date-column {
            white-space: nowrap;
            font-size: 13px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Schedules</h1>
        <p>{{ $month }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">#</th>
                <th>Project Details</th>
                <th class="date-column">Delivery Date</th>
                <th class="date-column">Start Date</th>
                <th class="date-column">End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $index => $schedule)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="project-name">{{ $schedule->project->name }}</div>
                        <div class="project-details">{{ $schedule->project->quotation_number }}</div>
                    </td>
                    <td class="date-column">{{ $schedule->project->delivery_date ? date('d-m-Y', strtotime($schedule->project->delivery_date)) : '-' }}</td>
                    <td class="date-column">{{ date('d-m-Y', strtotime($schedule->start_date)) }}</td>
                    <td class="date-column">{{ date('d-m-Y', strtotime($schedule->end_date)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->timezone('Asia/Kuwait')->format('d-m-Y H:i:s') }}
    </div>
</body>
</html> 