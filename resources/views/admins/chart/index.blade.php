@extends('layouts.app')
@section('title','Chart')
@section('header_page','Chart Report')

@push('head')

@endpush

@push('script')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE/plugins/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('/AdminLTE/plugins/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('AdminLTE/plugins/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js')}}"></script>

<script>
    var url_month = "{{ route('ajaxTotalPriceMonth')}}";
</script>
<script src="{{asset('js/admin/chartjs.js')}}"></script>

@endpush

@push('head')
<link rel="stylesheet" href="{{asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.css')}}">
<style>
    #refresh-chart{
        margin-top: 26px 
    }
    @media(max-width: 576px){
        #refresh-chart{
            margin-top: 0; 
        }
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="mr-2 form-group col-md-3 col-sm-12">
                                <label class="control-label">{{__('From:')}}</label>
                                <input class="form-control" readonly name="chartFrom" data-date value="{{$chartFrom ?? ''}}" placeholder="yyyy-mm">
                            </div>
                            <div class=" form-group col-sm-12 col-md-3">
                                <label class="control-label">{{__('To:')}}</label>
                                <input class="form-control" readonly name="chartTo" data-date value="{{$chartTo ?? ''}}" placeholder="yyyy-mm">
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <button class="btn btn-primary" id="refresh-chart">{{__('Show')}}</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart" style="min-height:400px">
                            <canvas id="year-chart" style="width:100%,height:auto"></canvas>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

@endsection