@extends('layouts.app')
@section('title','Settings')
@section('header_page','Settings')

@push('head')
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
@endpush

@push('script')
    <!-- Toastr -->
    <script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('settings.update')}}" method="post">
                        {{csrf_field()}}
                        {{method_field('put')}}
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="unpaid-amount">{{__('Unpaid Amount')}}</label>
                                                <input type="number" class="form-control" id="unpaid-amount"
                                                       name="unpaid-amount" value="{{ $unpaid_amount ?? 10000 }}"
                                                       autofocus>
                                                @if($errors->has('unpaid-amount'))
                                                    <label class="text-danger">{{$errors->get('unpaid-amount')[0]}}</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </form>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>

@endsection

@push('script')
    <script src="{{asset('/AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    @if(session()->has('status'))
        <script>
            @if(session()->get('status') === 'success')
            toastr.success("{{session()->get('message')}}");
            @else
            toastr.error("{{session()->get('message')}}");
            @endif
        </script>
    @endif
@endpush

@push('head')
    <link rel="stylesheet" href="{{asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
@endpush