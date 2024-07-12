<!DOCTYPE html>
<html>
<head>
    <title>Attendance PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
        }
        .content {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>THE INSTITUTE OF FINANCE MANAGEMENT</h1>
        @if (!empty($results))
            <h2>DEPARTMENT OF {{ $results[0]->department_name }}</h2>
            <h3>{{ $results[0]->coursename }}</h3>
            <h3>ATTENDANCE SHEET</h3>
        @else
            <h2>No Records Found</h2>
        @endif
    </div>
    <div class="content">
        @if (!empty($results))
            <p>Subject:  {{ $results[0]->module_code }} {{ $results[0]->modulename }} </p>
            <p>Facilitator: {{ $results[0]->lecturer_name }}</p>
            <p>Month: {{ \Carbon\Carbon::create()->month($month)->format('F') }}</p>
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Full Name</th>
                        <th>Registration NUmber</th>
                        <th>Total Session Attempt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $index => $result)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $result->student_name }}</td>
                            <td>{{ $result->Registration }}</td>
                            <td>{{ $result->attendance_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No attendance records found for the selected month and module.</p>
        @endif
    </div>
</body>
</html>
