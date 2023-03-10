@extends('layouts.master')
@if($invoice_type == 'paid')
    @section('title')
    الفواتير المدفوعة
    @stop
@endif

@if($invoice_type == 'unpaid')
    @section('title')
    الفواتير الغير مدفوعة
    @stop
@endif

@if($invoice_type == 'partial')
    @section('title')
    الفواتير المدفوعة جزئيا
    @stop
@endif
@if($invoice_type == 'invoices')
    @section('title')
    قائمة الفواتير
    @stop
@endif

@section('css')
<!-- Internal Data table css -->
<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
                            <h4 class="content-title mb-0 my-auto"><a href="{{ route('home') }}">الرئيسيه</a></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    الفواتبر </span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
    @if (session()->has('delete_invoice'))
    <script>
        window.onload = function() {
            notif({
                msg: "تم حذف الفاتورة بنجاح",
                type: "success"
            })
        }
    </script>
    @endif
    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('restore_invoice'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم استعادة الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif
				<!-- row -->
				<div class="row">

                    @if (session()->has('Status_Update'))
                    <script>
                            window.onload = function() {
                                notif({
                                    msg: "تم تحديث حالة الدفع بنجاح",
                                    type: "success"
                                })
                            }
                        </script>
                    @endif
                    @if (session()->has('Add'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session()->get('Add') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                        <!--div-->
                        <div class="col-xl-12">
                            <div class="card mg-b-20">
                                <div class="card-header pb-0">
                                    @can( 'invoice_add')
                                        <a href="{{route('invoices.create')}}" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                                            class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>

                                    @endcan
                                    @can('excel_export')

                                        <a class="modal-effect btn btn-sm btn-primary" href="{{ route('invoice_export') }}"
                                            style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل
                                        </a>
                                        @endcan


                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table key-buttons text-md-nowrap">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">رقم الفاتوره</th>
                                                    <th class="border-bottom-0">تاريخ الفاتوره</th>
                                                    <th class="border-bottom-0">تاريخ الاسنحقاق</th>
                                                    <th class="border-bottom-0">المنتج</th>
                                                    <th class="border-bottom-0">القسم</th>
                                                    <th class="border-bottom-0">الخصم</th>
                                                    <th class="border-bottom-0">نسبة الضريبه</th>
                                                    <th class="border-bottom-0">قيمة الضريبه</th>
                                                    <th class="border-bottom-0">الاجمالى</th>
                                                    <th class="border-bottom-0">الحاله</th>
                                                    <th class="border-bottom-0">ملاحظات</th>
                                                    <th class="border-bottom-0">العمليات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoices as $key=>$invoice)

                                                <tr>
                                                    <td>{{++$key}}</td>
                                                    <td>{{$invoice->invoice_number}}</td>
                                                    <td>{{$invoice->invoice_Date}}</td>
                                                    <td>{{$invoice->Due_date}}</td>
                                                    <td>{{$invoice->product}}</td>
                                                    @can('invoice_show')

                                                    <td>
                                                        <a href="{{route('invoiceDetails.edit', $invoice->id)}}"> {{$invoice->section->section_name}}</a>
                                                    </td>

                                                    @endcan
                                                    <td>{{$invoice->Discount}}</td>
                                                    <td>{{$invoice->Rate_VAT}}</td>
                                                    <td>{{$invoice->Value_VAT}}</td>
                                                    <td>{{$invoice->Total}}</td>
                                                    <td>
                                                        @if ($invoice->Value_Status == 1)
                                                            <span class="text-success">{{ $invoice->Status }}</span>
                                                        @elseif($invoice->Value_Status == 2)
                                                            <span class="text-danger">{{ $invoice->Status }}</span>
                                                        @else
                                                            <span class="text-warning">{{ $invoice->Status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$invoice->note}}</td>
                                                    <td>
                                                        <div class="dropdown" style="height: 55px;">
                                                            <button aria-expanded="false" aria-haspopup="true"
                                                                class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                                type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                            <div class="dropdown-menu tx-13">
                                                                @can('invoice_edit')

                                                                <a class="dropdown-item"
                                                                    href=" {{ route('invoices.edit', $invoice->id) }}"><i class="fas fa-edit"></i>&nbsp;&nbsp;تعديل
                                                                    الفاتورة</a>

                                                                @endcan
                                                                @can('invoice_delete')

                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                    data-toggle="modal" data-target="#delete_invoice"><i
                                                                        class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                    الفاتورة</a>

                                                                @endcan
                                                                @can('payment_status_change')

                                                                <a class="dropdown-item"
                                                                    href="{{route('invoices.show', $invoice->id) }}"><i
                                                                        class=" text-success fas
                                                                                                                                                fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                    حالة
                                                                    الدفع</a>

                                                                @endcan
                                                                @can('invoice_archive')

                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                    data-toggle="modal" data-target="#Transfer_invoice"><i
                                                                        class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                                    الارشيف</a>
                                                                @endcan

                                                                @can('invoice_print')

                                                                    <a class="dropdown-item" href="{{route('Print_invoice', $invoice->id) }}"><i
                                                                        class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                            الفاتورة
                                                                    </a>
                                                                @endcan
                                                                @can('payment_show')

                                                                    <a class="dropdown-item" href="{{route('payments', $invoice->id) }}"><i
                                                                        class="text-success fas fa-eye"></i>&nbsp;&nbsp;عرض
                                                                            الدفعات
                                                                    </a>
                                                                @endcan

                                                            </div>
                                                        </div>

                                                    </td>

                                                </tr>


                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/div-->

                        <!--div-->

           <!-- حذف الفاتورة -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
            </div>
            <div class="modal-body">
                هل انت متاكد من عملية الحذف ؟
                <input type="hidden" name="invoice_id" id="invoice_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                <button type="submit" class="btn btn-danger">تاكيد</button>
            </div>
            </form>
        </div>
    </div>
</div>

  <!-- ارشيف الفاتورة -->
  <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                  {{ method_field('delete') }}
                  {{ csrf_field() }}
          </div>
          <div class="modal-body">
              هل انت متاكد من عملية الارشفة ؟
              <input type="hidden" name="invoice_id" id="invoice_id" value="">
              <input type="hidden" name="id_page" id="id_page" value="2">

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
              <button type="submit" class="btn btn-success">تاكيد</button>
          </div>
          </form>
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
<!-- Internal Data tables -->
<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{asset('assets/js/table-data.js')}}"></script>
<script>
    $('#delete_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>
<script>
    $('#Transfer_invoice').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var invoice_id = button.data('invoice_id')
        var modal = $(this)
        modal.find('.modal-body #invoice_id').val(invoice_id);
    })
</script>
@endsection
