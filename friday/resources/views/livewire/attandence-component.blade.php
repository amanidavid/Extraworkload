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
                                                    <label>Module:</label>
                                                    <select class="chosen-select form-control" name="module_code" tabindex="-1" required>
                                                        <option value="">Select Module</option>
                                                        @foreach ($module as $module)
                                                            <option value="{{ $module->module_code }}">{{ $module->module_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label>Month:</label>
                                                    <select class="chosen-select form-control" name="month" tabindex="-1" required>
                                                        <option value="">Select Month</option>
                                                        <option value="january ">1</option>
														<option value="february ">2</option>
														<option value="march">3</option>
														<option value="april Islands">4</option>
														<option value="may">5</option>
														<option value="june">6</option>
														<option value="7 ">july</option>
														<option value="august">8</option>
														<option value="september">9</option>
														<option value="october">10</option>
														<option value="november">11</option>
														<option value="december">12</option>
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

