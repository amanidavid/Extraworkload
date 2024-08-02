<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        
        .attendance-details {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #ffffff;
            color: #333333;
        }
        
        h2 {
            color: #007bff;
        }
        
        .header-top-area {
            background-color: #343a40;
        }
        
        .header-top-area a {
            color: #ffffff;
        }
        
        .nav-link {
            color: #ffffff;
        }
        
        .nav-link:hover {
            color: #007bff;
        }
        
        .dropdown-header-top {
            background-color: #ffffff;
            color: #3429d8;
        }
        
        .dropdown-header-top a {
            color: #007bff;
        }
        
        .dropdown-header-top a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                        @role('Staff')
                        <li>
                            <a title="Landing Page" href="{{route('form.show')}}" aria-expanded="false">
                                <span class="fas fa-calendar-check icon-wrap sub-icon-mg" aria-hidden="true" style="color: #007bff"></span>
                                <span class="mini-click-non">Attendance Form</span>
                            </a>
                        </li>
                        <li>
                            <a title="Generate Report" href="{{route('form.shows')}}" aria-expanded="false">
                                <span class="fas fa-money-bill-wave icon-wrap sub-icon-mg" style="color: #007bff"></span>
                                <span class="mini-click-non">Claim Form</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a title="Generate Report" href="{{ route('attendance.index') }}" aria-expanded="false">
                                <span class="fas fa-id-card icon-wrap sub-icon-mg"></span>
                                <span class="mini-click-non">My Attendance</span>
                            </a>
                        </li> --}}
                        @endrole

                        @role('Student')
                        <li>
                            <a title="Generate Report" href="{{ route('attendance.index') }}" aria-expanded="false">
                                <span class="fas fa-id-card icon-wrap sub-icon-mg" style="color: #007bff"></span>
                                <span class="mini-click-non">My Attendance</span>
                            </a>
                        </li>
                        @endrole

                    </ul>
                </nav>
            </div>
        </nav>
    </div>
</body>

</html>
