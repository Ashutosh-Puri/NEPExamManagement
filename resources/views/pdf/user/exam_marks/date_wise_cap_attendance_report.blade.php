<!DOCTYPE html>
<html>
<head>
    <title>Date Wise Cap Attendance Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th,td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Date Wise Cap Attendance Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th style="text-align: left;">Faculty Name</th>
                @foreach($dates as $date)
                    <th>{{ $date }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $faculty_id => $attendance)
                <tr>
                    <td>{{ $faculty_id }}</td>
                    <td style="text-align: left;">{{ $attendance['name'] }}</td>
                    @foreach($attendance['dates'] as $date => $status)
                        <td>{{ $status }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
