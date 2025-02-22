<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Schedule - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ac7909;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #ac7909;
        }
        .project-name {
            font-weight: bold;
            color: #333;
        }
        .project-details {
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Schedule</h1>
        <p>{{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SN.</th>
                <th>Project Details</th>
                <th>Team Leaders</th>
                <th>Labors</th>
                <th>Engineers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailySchedules as $projectId => $schedules)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="project-name">{{ $schedules[0]->project->name }}</div>
                        <div class="project-details">
                            Quotation: {{ $schedules[0]->project->quotation_number }}<br>
                            Location: {{ $schedules[0]->project->location }}
                        </div>
                    </td>
                    <td>
                        {{ $schedules->where('employee.type', 'supervisor')->pluck('employee.name')->implode(', ') }}
                    </td>
                    <td>
                        {{ $schedules->where('employee.type', 'technician')->pluck('employee.name')->implode(', ') }}
                    </td>
                    <td>
                        {{ $schedules->where('employee.type', 'engineer')->pluck('employee.name')->implode(', ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 