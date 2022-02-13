@extends('layouts.app')

@section('title', __('Add Job'))
@section('header_page', __('Add Job'))
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    @if(session()->has('old'))
                        @php
                            $old = session()->get('old')
                        @endphp
                    @endif
                    <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" action="{{route('jobs.store')}}" method="post">
                                {{csrf_field()}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Name">{{__('Name')}}</label>
                                                <input title="{{__('Name')}}" name="Name" type="text"
                                                       class="form-control" id="Name"
                                                       value="{{$old['Name'] ?? ''}}"
                                                       placeholder="{{__('Job\'s name')}}" autofocus>
                                                @if($errors->has('Name'))
                                                    <label class="text-danger">{{$errors->get('Name')[0]}}</label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="Customer">{{__('Customer')}}</label>
                                                <select title="{{__('Customer')}}" name="CustomerID" id="Customer"
                                                        class="form-control select2bs4"
                                                        style="width: 100%;">
                                                    <option></option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->ID}}">{{$customer->Name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('CustomerID'))
                                                    <label class="text-danger">{{$errors->get('CustomerID')[0]}}</label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="Type">{{__('Type')}}</label>
                                                <select title="{{__('Job\'s Type')}}" name="TypeID" id="Type"
                                                        class="form-control select2bs4"
                                                        style="width: 100%;">
                                                    <option></option>
                                                    @foreach($types as $type)
                                                        <option value="{{$type->ID}}">{{$type->Name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('TypeID'))
                                                    <label class="text-danger">{{$errors->get('TypeID')[0]}}</label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="Method">{{__('Method')}}</label>
                                                <select title="{{__('Job\'s Method')}}" name="MethodID" id="Method"
                                                        class="form-control select2bs4"
                                                        style="width: 100%;">
                                                    <option></option>
                                                    @foreach($methods as $method)
                                                        <option value="{{$method->ID}}">{{$method->Name}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('MethodID'))
                                                    <label class="text-danger">{{$errors->get('MethodID')[0]}}</label>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="StartDate">{{__('Start Date')}}</label>
                                                <input id="StartDate" title="{{__('Start Date')}}"
                                                       value="{{$old['StartDate'] ?? \Carbon\Carbon::now()->format('Y-m-d')}}"
                                                       name="StartDate"
                                                       type="text"
                                                       class="form-control"
                                                />
                                                @if($errors->has('StartDate'))
                                                    <label class="text-danger">{{$errors->get('StartDate')[0]}}</label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="FinishDate">{{__('Finish Date')}}</label>
                                                <input id="FinishDate" title="{{__('Finish Date')}}"
                                                       value="{{$old['FinishDate'] ?? \Carbon\Carbon::now()->format('Y-m-d')}}"
                                                       name="FinishDate"
                                                       type="text" class="form-control"
                                                />
                                                @if($errors->has('FinishDate'))
                                                    <label class="text-danger">{{$errors->get('FinishDate')[0]}}</label>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="PriceYen">{{__('Price Yen')}}</label>
                                                            <input title="{{__('JPY')}}" min="0"
                                                                   value="{{$old['PriceYen'] ?? ''}}" name="PriceYen"
                                                                   type="number" class="form-control" id="PriceYen"
                                                                   placeholder="{{__('JPY')}}">
                                                        </div>
                                                        @if($errors->has('PriceYen'))
                                                            <label class="text-danger">{{$errors->get('PriceYen')[0]}}</label>
                                                        @endif
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="Price">{{__('Price USD')}}</label>
                                                            <input title="{{__('USD')}}" value="" disabled name="Price"
                                                                   type="number"
                                                                   class="form-control" id="Price"
                                                                   placeholder="{{__('USD')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <p id="ex-text" class="text-right text-md"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="Note">{{__('Note')}}</label>
                                                <textarea placeholder="{{__('Note')}}" title="{{__('Note')}}" rows="5"
                                                          name="Note" class="form-control"
                                                          id="Note">{{$old['Note'] ?? ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                                </div>
                            </form>
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

@push('script')
    <script src="{{asset('/AdminLTE/plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/AdminLTE/plugins/jquery-ui/jquery-ui.min.js')}}">
    </script>
    <script src="{{asset('/AdminLTE/plugins/inputmask/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('/js/admin/exRate.js')}}"></script>
    <script src="{{asset('/js/admin/create_job.js')}}"></script>
@endpush

@push('script')

    @if(session()->has('status'))
        <script>
            @if(session()->get('status') === 'success')
            toastr.success("{{session()->get('message')}}");
            @else
            toastr.error("{{session()->get('message')}}");
            @endif
        </script>
    @endif

    @if(isset($old['CustomerID']) && !$errors->has('CustomerID'))
        <script>
            $('#Customer option[value="{{$old['CustomerID']}}"]').attr('selected', true);
        </script>
    @endif

    @if(isset($old['TypeID']) && !$errors->has('TypeID'))
        <script>
            $('#Type option[value="{{$old['TypeID']}}"]').attr('selected', true);
        </script>
    @endif

    @if(isset($old['MethodID']) && !$errors->has('MethodID'))
        <script>
            $('#Method option[value="{{$old['MethodID']}}"]').attr('selected', true);
        </script>
    @endif
@endpush

@push('head')
    <link rel="stylesheet" href="{{asset('AdminLTE/plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.css')}}">
@endpush