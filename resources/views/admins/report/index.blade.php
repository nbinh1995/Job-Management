@extends('layouts.app')

@section('title', __('Reports'))
@section('header_page', __('Reports'))
@section('content')

<div class="modal fade" id="modal-report">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__('Chart Report')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="chart-wrapper">
                            <canvas id="report-chart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="table-responsive">
                            <table id="chart-table" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{__('ID')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Total Price')}}</th>
                                        <th>{{__('Percent')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                @include('partials.form.form-filter_report')
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="col-12 total"></div>
                                <div class="table-responsive">
                                    <table id="reportTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Price USD')}}</th>
                                                <th>{{__('Price Yen')}}</th>
                                                <th>{{__('Start Date')}}</th>
                                                <th>{{__('Pay Date')}}</th>
                                                <th>{{__('Paid')}}</th>
                                                <th>{{__('Customer')}}</th>
                                                <th>{{__('Job Method')}}</th>
                                                <th>{{__('Job Type')}}</th>
                                                <th>{{__('Deadline')}}</th>
                                                <th>{{__('Finish Date')}}</th>
                                                <th>{{__('Note')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="col-12 total"></div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</div>
@include('partials.common.admin-confirm_model',['model_title' => __('Confirm delete'),'model_message' => __('Are you
sure to delete this Job?'),])
@include('partials.common.admin-overlay')
@endsection

@push('script')
<script src="{{asset('/AdminLTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/mobile-detect/mobile-detect.min.js')}}"></script>
<script>
    var md = new MobileDetect(window.navigator.userAgent);

</script>
<script src="{{asset('/js/admin/exRate.js')}}"></script>
<script src="{{asset('/js/admin/report.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js')}}">
</script>
<script src="{{asset('/js/admin/report_chart.js')}}"></script>
@endpush

@push('head')
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/chart.js/Chart.min.css')}}">
@endpush