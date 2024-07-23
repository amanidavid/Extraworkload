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
            padding: 4px;
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
        /* Ensure the table is responsive */

    </style>
</head>
<body>
    <div class="container">
        
        <h2>THE INSTITUTE OF FINANCE MANAGEMENT</h2>
        <p>CLAIM FOR EXTRA HOURS TEACHING WORKLOAD</p>
        <p>(FULL/PART TIME ACADEMIC STAFF)</p>
        <p>(FOR HUMAN RESOURCES OFFICE)</p>
        @if (!empty($extraworkload))
        <p><strong>NAME:</strong>{{$extraworkload[0]->name}}</p>
        <p><strong>RANK:</strong>{{$extraworkload[0]->Rank}}</p>
        <p><strong>DEPARTMENT:</strong>{{$extraworkload[0]->dname}}</p>
       
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
                <td>{{ $item->dname }}</td>
                <td>{{ $item->module_code }}</td>
                <td>{{ $item->coursename }}</td>
                <td>{{ $item->lecture_hrs }}</td>
                <td>{{ $item->tutorial_hrs }}</td>
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
                <td>{{ $extraworkload[0]->lecture_hrs }}</td>
                <td>{{ $extraworkload[0]->tutorial_hrs }}</td>
                {{-- <td>(Your data here)</td> --}}
                <td>{{ $extraworkload[0]->OverallTotal }}</td>
            </tr>
            <tr>
                <td>MIN WORKLOAD</td>
                <td colspan="4">{{ $extraworkload[0]->Minworkload }}</td>
            </tr>
            <tr>
                <td>CLAIMED EXTRA HOURS</td>
                <td colspan="4">{{ $extraworkload[0]->Extraworkload }}</td>
            </tr>
        </table>
        @else

        <h2>No Records Found For Summary</h2>

    @endif

    <table>
        @if (!empty($extraworkload))

        <thead>
            <tr>
                <th>S/No</th>
                <th>TRANSPORT ALLOWANCE</th>
                <th>NUMBER OF SESSIONS/DAYS</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($extraworkload as $index => $result) --}}
            <tr>
                <td>i</td>
                <td>TAUGHT MAIN CAMPUS FROM 7 00PM</td>
                <td>{{ $extraworkload[0]->Main_Campus }}</td>
            </tr>
            <tr>
                <td>ii</td>
                <td>TAUGHT MAKTABA VENUES</td>
                <td>{{ $extraworkload[0]->TAUGHT_MAKTABA_VENUES }}</td>
            </tr>
            <tr>
                <td>iii</td>
                <td>TAUGHT DURING WEEKENDS</td>
                <td>{{ $extraworkload[0]->TAUGHT_ON_WEEKENDS }}</td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
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
