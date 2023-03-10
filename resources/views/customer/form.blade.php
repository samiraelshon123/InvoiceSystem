@extends('layouts.master')
@section('css')
<!-- Internal Nice-select css  -->
<link href="{{asset('assets/plugins/jquery-nice-select/css/nice-select.css')}}" rel="stylesheet" />
@if ($user->id != '')
@section('title')
تعديل عميل - نظام الفواتير
@stop

@else
@section('title')
اضافة عميل - نظام الفواتير
@stop
@endif

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            @if ($user->id != '')
            <h4 class="content-title mb-0 my-auto"><a href="{{ route('home') }}">الرئيسيه</a></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                عميل</span>

            @else
            <h4 class="content-title mb-0 my-auto"><a href="{{ route('home') }}">الرئيسيه</a></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة
                عميل</span>
            @endif

        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-lg-12 col-md-12">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('customers.index') }}">رجوع</a>
                    </div>
                </div><br>

                <form action="{{$route}}" method="POST">
                    @csrf
                    @if ($user->id != '')
                    @method('PUT')
                    @endif

                <div class="">

                    <div class="row mg-b-20">
                        <div class="parsley-input col-md-6" id="fnWrapper">
                            <label>اسم المستخدم: <span class="tx-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{$user->name}}" required>

                        </div>

                        <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                            <label>البريد الالكتروني: <span class="tx-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{$user->email}}" required>

                        </div>
                    </div>


                </div>

                <div class="row mg-b-20">
                    <div class="parsley-input col-md-6" id="fnWrapper">
                        <label>رقم الموبايل: <span class="tx-danger">*</span></label>
                        <input type="text" class="form-control" name="phone" value="{{$user->phone}}" required>

                    </div>

                    <div class="parsley-input col-md-6 mg-t-20 mg-md-t-0" id="lnWrapper">
                        <label> العنوان: <span class="tx-danger">*</span></label>
                        <input type="address" class="form-control" name="address" value="{{$user->address}}" required>

                    </div>
                </div>
                <div class="row row-sm mg-b-20">
                    <div class="col-lg-6">
                        <label class="form-label">نوع العميل</label>
                        <select name="type" id="select-beast" class="form-control  nice-select  custom-select">

                            <option value="1" {{($user->type ==1) ? 'selected' : '' }}>مورد</option>
                            <option value="0" {{($user->type ==0) ? 'selected' : '' }}>زبون</option>
                        </select>
                    </div>
                </div>


                <div class="mg-t-30">
                    @if ($user->id != '')
                    <button class="btn btn-main-primary pd-x-20" type="submit">تحديث</button>
                    @else
                    <button class="btn btn-main-primary pd-x-20" type="submit">تاكيد</button>
                    @endif
                </div>
            </form>
            </div>
        </div>
    </div>
</div>




</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')

<!-- Internal Nice-select js-->
<script src="{{asset('assets/plugins/jquery-nice-select/js/jquery.nice-select.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-nice-select/js/nice-select.js')}}"></script>

<!--Internal  Parsley.min js -->
<script src="{{asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{asset('assets/js/form-validation.js')}}"></script>
@endsection
