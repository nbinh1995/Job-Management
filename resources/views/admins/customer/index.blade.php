@extends('layouts.app')
@section('title','Customers')
@section('header_page','Customers')

@push('head')
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
@endpush

@push('script')
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
    <!-- BootBox -->
    <script src="{{ asset('AdminLTE/plugins/bootbox/bootbox.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/mobile-detect/mobile-detect.min.js')}}"></script>
    <script>
        var md = new MobileDetect(window.navigator.userAgent);
    </script>
    <script src="{{asset('js/admin/customer.js')}}"></script>
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
                            <div class="row align-items-center">
                                <div class=" col-sm-12 col-md-2 mb-2">
                                    <button class="btn btn-primary create" data-url="{{route('customers.create')}}"><i
                                                class="fas fa-plus-circle mr-1"
                                                style="pointer-events: none"></i>{{__('Add Customer')}}</button>
                                </div>
                                <div class="col-md-4 col-sm-12 mb-2">
                                    <form action="{{route('ajaxUpdateUnpaid')}}" class="form-inline unpaid-form"
                                          method="post">
                                        <div class="input-group w-100">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <label class="text-xs">{{__('Unpaid Threshold')}}</label>
                                            </span>
                                            </div>
                                            <input id="unpaid-amount"
                                                   title="{{isset($unpaid_amount) ? number_format($unpaid_amount) : ''}}"
                                                   class="form-control unpaid-amount" min="0" type="number"
                                                   name="unpaid-amount" value="{{$unpaid_amount ?? ''}}">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <button title="{{__('Update')}}"
                                                        class="btn btn-info btn-xs update-unpaid"><i
                                                            class="fas fa-save"></i></button>
                                            </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-12 col-md-6 mb-2">
                                    <div class="row justify-content-md-end">
                                        <div class="form-inline mr-2">
                                            <label for="sort-option" class="mr-2">{{__('Sort by:')}}</label>
                                            <select name="sort-option" class="form-control" id="sort-option">
                                                <option value="0">{{__('Most recent')}}</option>
                                                <option value="1">{{__('Newest')}}</option>
                                                <option value="2">{{__('Oldest')}}</option>
                                            </select>
                                        </div>
                                        <div class="form-inline">
                                            <label for="option-list" class="mr-2">{{__('Unpaid List:')}}</label>
                                            <select name="option-list" class="form-control" id="option-list">
                                                <option value="0">All List</option>
                                                <option value="1">UnPaid List</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered" id="common-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Customer Note</th>
                                    <th>UnPaid</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Customer Note</th>
                                    <th>UnPaid</th>
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