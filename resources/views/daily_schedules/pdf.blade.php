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
        .text-center {
            text-align: center;
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
            @foreach($dailySchedules as $schedule)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>
                        <div class="project-name">{{ $schedule->project->name }}</div>
                        <div class="project-details">
                            {{ $schedule->project->quotation_number }}<br>
                            {{ $schedule->project->location }}
                        </div>
                    </td>
                    <td>
                        @if($schedule->employee->where('type', 'supervisor')->count() > 0)
                            @foreach($schedule->employee->where('type', 'supervisor')->pluck('name') as $employee)
                                {{ $employee }} @if(!$loop->last)- @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($schedule->employee->where('type', 'technician')->count() > 0)
                            @foreach($schedule->employee->where('type', 'technician')->pluck('name') as $employee)
                            {{ $employee }} @if(!$loop->last)- @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($schedule->employee->where('type', 'engineer')->count() > 0)
                            @foreach($schedule->employee->where('type', 'engineer')->pluck('name') as $employee)
                                {{ $employee }} @if(!$loop->last)- @endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 