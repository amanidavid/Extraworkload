@extends("layouts.app")
@section('content')
    <div class="all-content-wrapper">
           <!-- Advanced Form Start -->
           <div class="advanced-form-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline10-list mg-tb-30 responsive-mg-t-0 table-mg-t-pro-n dk-res-t-pro-0 nk-ds-n-pro-t-0">
                            <div class="sparkline10-hd">
                                <div class="main-sparkline10-hd">
                                    <h1>Chosen select</h1>
                                </div>
                            </div>
                            <div class="sparkline10-graph">
                                <div class="input-knob-dial-wrap">
                                    <form action="{{ route('attendance.generate')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                           
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label>Month:</label>
                                                    <select class="chosen-select form-control" name="month" tabindex="-1" required>
                                                        <option value="">Select Month</option>
                                                        <option value="1 ">January</option>
														<option value="2 ">February</option>
														<option value="3">March</option>
														<option value="4">April</option>
														<option value="5">May</option>
														<option value="6">June</option>
														<option value="7 ">July</option>
														<option value="8">August</option>
														<option value="9">September</option>
														<option value="10">October</option>
														<option value="11">November</option>
														<option value="12">December</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label>Event Name:</label>
                                                    <select class="chosen-select form-control" name="event_name" tabindex="-1" required>
                                                        <option value="">Select Event</option>
                                                        @foreach ($events as $event)
                                                            <option value="{{ $event->event_name }}">{{ $event->event_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label>Module:</label>
                                                    <select class="chosen-select form-control" name="module_code" tabindex="-1" required>
                                                        <option value="">Select Module</option>
                                                        @foreach ($module as $module)
                                                            <option value="{{ $module->module_code }}">{{ $module->module_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                     
                                        </div>
                                        <button type="submit" class="btn btn-primary">Generate PDF</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </div>
    </div>
   @endsection

