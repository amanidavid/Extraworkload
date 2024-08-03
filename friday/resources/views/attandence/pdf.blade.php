<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .symbol {
            font-family: "Segoe UI Symbol", "Arial Unicode MS", Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>THE INSTITUTE OF FINANCE MANAGEMENT</h1>
        @if (!empty($results))
            <h2>DEPARTMENT OF {{ $department->dept_name }}</h2>
            {{-- <h3>{{ $module_code }} - {{ $module_name }}</h3> --}}
            <h3>ATTENDANCE SHEET</h3>
            <h3>{{ $event_name }}</h3>
           
        @else
            <h2>No Records Found</h2>
            <p>Parameters: Module Code - {{ $module_code }}, Month - {{ $month }}, Event Name - {{ $event_name }}</p>
        @endif
    </div>
    <div class="content">
        @if (!empty($results))
            <p>Subject: {{ $module_code }} - {{ $modulename }}</p>
            <p>Facilitator: {{ $user['name'] }}</p>
            <p>Month: {{ \Carbon\Carbon::create()->month($month)->format('F') }}</p>
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Full Name</th>
                        <th>Registration Number</th>
                        @foreach ($dates as $date)
                            <th>{{ $date }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendance as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student['student_name'] }}</td>
                            <td>{{ $student['registration_number'] }}</td>
                            @foreach ($dates as $date)
                                <td class="symbol">
                                    @if ($student['attendance_dates']->contains($date))
                                      <p style="color: black;">Present</p>

                                    @else
                                    <p style="color: red;">Absent</p>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No attendance records found for the selected month and module.</p>
            <p>Parameters: Module Code - {{ $module_code }}, Month - {{ $month }}, Event Name - {{ $event_name }}</p>
        @endif
    </div>
</body>
</html>
