<!DOCTYPE html>
<html>
<head>
    <title>Claim for Extra Hours Teaching Workload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }
        .summary-table {
            margin-top: 20px;
        }
        .summary-table th {
            background-color: #e0e0e0;
        }
        .summary-table td {
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <img src="{{url ('img/ifm.png')}}" alt="Institute Logo">           
        <h2>THE INSTITUTE OF FINANCE MANAGEMENT</h2>
        <p>CLAIM FOR EXTRA HOURS TEACHING WORKLOAD</p>
        <p>(FULL/PART TIME ACADEMIC STAFF)</p>
        <p>(FOR HUMAN RESOURCES OFFICE)</p>
        @if (!empty($extraworkload))
        <p><strong>NAME:</strong>{{$extraworkload[0]->lecturer_name}}</p>
        <p><strong>RANK:</strong>{{$extraworkload[0]->levels_of_teaching}}</p>
        <p><strong>DEPARTMENT:</strong>{{$extraworkload[0]->Department}}</p>
       
        @else

        <h2>No Records Found</h2>

    @endif
        <table>
            @if (!empty($extraworkload))
            <tr>
                <th>FACULTY</th>
                <th>DEPARTMENT</th>
                <th>MODULE(S) CODE</th>
                <th>PROGRAMME</th>
                <th>LECTURE HOURS</th>
                <th>TUTORIAL HOURS</th>
                <th>TOTAL</th>
            </tr>
            @foreach($extraworkload as $item)
            <tr>
                <td>{{ $item->faculty_name }}</td>
                <td>{{ $item->Department }}</td>
                <td>{{ $item->module_code }}</td>
                <td>{{ $item->Programme }}</td>
                <td>{{ $item->lecture_hours }}</td>
                <td>{{ $item->tutorial_hours }}</td>
                <td>{{ $item->Total }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p>No claim records found for the selected month and Facilitator.</p>
    @endif

        <table class="summary-table">
            
            @if (!empty($extraworkload))
            <tr>
                <th>SUMMARY</th>
                <th>LECTURE</th>
                <th>TUTORIAL</th>
                {{-- <th>OTHER</th> --}}
                <th>TOTAL</th>
            </tr>
            <tr>
                <td>TOTAL HOURS TAUGHT</td>
                <td>{{ $extraworkload[0]->total_lecture_hours }}</td>
                <td>{{ $extraworkload[0]->total_tutorial_hours }}</td>
                {{-- <td>(Your data here)</td> --}}
                <td>{{ $extraworkload[0]->total_hours }}</td>
            </tr>
            <tr>
                <td>MIN WORKLOAD</td>
                <td colspan="4">{{ $extraworkload[0]->min_workload }}</td>
            </tr>
            <tr>
                <td>CLAIMED EXTRA HOURS</td>
                <td colspan="4">{{ $extraworkload[0]->extra_claimed_hours }}</td>
            </tr>
        </table>
        @else

        <h2>No Records Found For Summary</h2>

    @endif

        <div class="footer">
            <p><strong>CERTIFICATION</strong></p>
            <p>i. I HAVE SUBMITTED THE SUPPORTING DOCUMENTS TO THE HEAD OF DEPARTMENT</p>
            <p>ii. <span>I CERTIFY THAT THE PARTICULARS GIVEN ABOVE ARE CORRECT, AND I WISH TO CLAIM THE TEACHING ALLOWANCE FOR THE PERIOD OF (MONTH AND YEAR)</span></p>
            <p>SIGNATURE: _______________________________ DATE: _______________________________</p>
        
            <hr> <!-- Adding a horizontal line for separation -->
        
            <p><strong>FOR OFFICIAL USE ONLY</strong></p>
            <p>CLAIM APPROVED/NOT APPROVED FOR PAYMENT BY HEAD OF DEPARTMENT</p>
            <p>NAME: _______________________________ SIGNATURE: _______________________________ DATE: _______________________________</p>

          </div>
    </div>
</body>
</html>
