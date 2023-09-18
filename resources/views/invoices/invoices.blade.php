@extends('layouts.master')

@section('title')
    لوحة التحكم - الفواتير
@endsection


@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الفواتير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('flash::message')
    <!-- row -->
    <div class="row">

        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <a href="{{ route('invoices.create') }}" class="modal-effect btn btn-sm btn-primary"
                        style="color:white"><i class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="invoices-table" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>

                                </tr>

                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($invoices as $invoice)
                                    <?php $i++; ?>
                                    <tr>
                                        <td class="border-bottom-0">{{ $i }}</td>
                                        <td><a
                                                href="{{ route('invoice_details.edit', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                                        </td>

                                        <td class="border-bottom-0">{{ $invoice->invoice_date }}</td>
                                        <td class="border-bottom-0">{{ $invoice->due_date }}</td>
                                        <td class="border-bottom-0">{{ $invoice->product }}</td>
                                        <td class="border-bottom-0">{{ $invoice->section->section_name }}</td>
                                        <td class="border-bottom-0">{{ $invoice->discount }}</td>
                                        <td class="border-bottom-0">{{ $invoice->rate_vat }}</td>
                                        <td class="border-bottom-0">{{ $invoice->value_vat }}</td>
                                        <td class="border-bottom-0">{{ $invoice->total }}</td>
                                        <td data-status="paidStatus">
                                            @if ($invoice->value_status == 1)
                                                <span class="badge badge-pill badge-success">{{ $invoice->status }}</span>
                                            @elseif($invoice->value_status == 0)
                                                <span class="badge badge-pill badge-danger">{{ $invoice->status }}</span>
                                            @else
                                                <span class="badge badge-pill badge-warning">{{ $invoice->status }}</span>
                                            @endif

                                        </td>
                                        <td class="border-bottom-0">{{ $invoice->note }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">
                                                    <a class="dropdown-item"
                                                        href=" {{ route('invoices.edit', $invoice->id) }}">تعديل
                                                        الفاتورة</a>



                                                    <a class="dropdown-item" href="{{ route('status.show' , $invoice->id) }}"><i
                                                            class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغيير
                                                        حالة الدفع
                                                    </a>


                                                    <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                                        method="post" data-kt-debts-table-filter="delete_form">
                                                        <a class="dropdown-item" href=""
                                                            data-kt-debts-table-filter="delete_row"><i
                                                                class=" text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                            الفاتورة</a>
                                                    </form>




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
                            @method('DELETE')
                            @csrf
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




    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>

    <script>
        toastr.options = {
            positionClass: 'toast-bottom-left', // Set the position to left-bottom
        };

        function handlePayment(parent) {
            let paynmentStatusTd = parent.querySelector('[data-status="paidStatus"]');
            const verfiedForm = parent.querySelector(
                '[data-kt-subscription-table-filter="verifiedSubscriptionPayment_form"]');

            Swal.fire({
                text: "هل أنت متأكد من أنك تريد الدفع ؟",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "نعم ، ادفع المبلغ!",
                cancelButtonText: "لا ، ارجع",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    if (paynmentStatusTd.innerText === 'مدفوعة') {
                        Swal.fire({
                            text: "لا يمكن دفع الفواتير المدفوعة",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "حسنا، اذهب",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    } else {
                        let Url = verfiedForm.action;
                        let method = verfiedForm.method;
                        let csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: Url,
                            type: method,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    toastr.success(response.message);
                                    let paynmentStatusSpan = paynmentStatusTd.getElementsByTagName(
                                        'span')[0];
                                    paynmentStatusSpan.innerText = 'مدفوعة';
                                    paynmentStatusSpan.classList.add('badge-success');
                                    paynmentStatusSpan.classList.remove('badge-danger');
                                } else if (response.status == 'warning') {
                                    toastr.warning(response.message);
                                } else if (response.status == 'error') {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("XHR status:", xhr.status);
                                console.log("XHR statusText:", xhr.statusText);
                                console.log("Error:", error);
                            }
                        })
                    }
                }
            });
        }

        const payButtons = document.querySelectorAll(
            '[data-kt-Subscription-table-filter="verfiedSubscriptionPaynment_row"]');
        payButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = e.target.closest('tr');
                handlePayment(parent);
            });
        });
    </script>

    <script>
        let table = document.getElementById('invoices-table');
        // Select all delete buttons

        dataTable = $(table).DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_',
            }
        });
        const deleteButtons = table.querySelectorAll('[data-kt-debts-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function(e) {
                e.preventDefault();
                // Select parent row
                const parent = e.target.closest('tr');
                const invoiceName = parent.querySelectorAll('td')[1].innerText;
                // Select all delete form
                const deletForm = parent.querySelector(
                    '[data-kt-debts-table-filter="delete_form"]');

                Swal.fire({
                    text: "هل أنت متأكد من أنك تريد حذف  " + invoiceName + "؟",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "نعم ، احذف!",
                    cancelButtonText: "لا ، ارجع",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function(result) {
                    if (result.value) {
                        // Remove current row
                        let Url = deletForm.action;
                        let method = deletForm.method;
                        let csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: Url,
                            type: method,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            data: {
                                '_method': 'delete'
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    toastr.success(response.message);
                                    dataTable.row($(parent)).remove().draw();
                                } else if (response.status == 'warning') {
                                    toastr.warning(response.message);
                                } else if (response.status == 'error') {
                                    toastr.error(response.message);
                                }
                                console.log(response.message)
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        }).then(function() {
                            // Detect checked checkboxes
                            toggleToolbars();
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: invoiceName + " لم يتم حذفها .",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "حسنا ، اذهب!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            })
        });
    </script>
@endsection
