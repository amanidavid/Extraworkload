@extends('layouts.app')

@section('content')
<div class="container"><br><br>
    <h2 class="text-center" style="margin-right: 50%">My Attendance</h2>

    @if ($message)
    <div class="alert alert-warning text-center">{{ $message }}</div>
    @else
        <div class="table-responsive" style="margin-left: 20%;">
            <table class="table table-bordered" style="width: auto; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 20%;">S/N</th>
                        <th style="width: 20%;">Module Code</th>
                        <th style="width: 20%;">Date</th>
                        {{-- <th style="width: 20%;">Event</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendanceRecords as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $record['module_code'] }}</td> 
                            <td>{{ \Carbon\Carbon::parse($record['created_at'])->format('Y-m-d') }}</td>
                            {{-- <td>{{ $record['event_name'] }}</td> <!-- Display event_name --> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
