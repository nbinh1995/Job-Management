@extends('layouts.app')
@section('title','Job\'s Methods')
@section('header_page','Methods')

@push('head')
<!-- datatable  -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
@endpush

@push('script')
<!-- datatable  -->
<script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Toastr -->
<script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
<!-- BootBox -->
<script src="{{ asset('AdminLTE/plugins/bootbox/bootbox.js')}}"></script>
<script src="{{asset('js/admin/jmethod.js')}}"></script>
@endpush

@section('content')
<div class="loading-process">
    <div class="center-middle">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary create" data-url="{{route('jmethods.create')}}"><i
                                class="fas fa-plus-circle mr-2"
                                style="pointer-events: none"></i>{{__('Add Method')}}</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered" id="common-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Method Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Method Name</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
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
@include('components.modal',['idModal'=>'common-modal'])

@endsection