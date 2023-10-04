@extends('layouts.master')
@section('css')

@section('title')
    لوحة التحكم - نشاطات المستخدمين
@stop

<!-- Internal Data table css -->

<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ نشاطات
                المستخدمين</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

<style>
    .hidden {
        display: none;
    }

    .toggle-button {
        cursor: pointer;
        text-decoration: underline;
        border: none;
        background-color: transparent;
        padding: 0;
        margin: 0;
    }
</style>

@include('flash::message')

<!-- row opened -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive hoverable-table">
                    <table class="table table-hover" id="example1" style="text-align: center;">
                        <thead>
                            <tr>
                                <th class="wd-10p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">اسم المستخدم</th>
                                <th class="wd-20p border-bottom-0">النشاط</th>
                                {{-- <th class="wd-20p border-bottom-0">الموضوع</th> --}}
                                <th class="wd-15p border-bottom-0">البيانات</th>
                                <th class="wd-15p border-bottom-0">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activityLogs as $activity)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($activity->causer)
                                            {{ $activity->causer->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    {{-- <td>
                                        @if ($activity->subject)
                                            {{ $activity->subject->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td> --}}
                                    <td>
                                        @if (count($activity->changes['attributes']) > 0)
                                            <button class="toggle-button">عرض تفاصيل البيانات</button>
                                            <div class="extra-info hidden">
                                                @foreach ($activity->changes['attributes'] as $attribute => $value)
                                                    <p><strong>{{ $attribute }}:</strong>
                                                        @if (is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </p>
                                                @endforeach
                                            </div>
                                        @else
                                            لا يوجد بيانات لعرضها
                                        @endif
                                    </td>

                                    <td>{{ $activity->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!--/div-->
</div>

</div>
<!-- /row -->
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
    $(document).ready(function() {
        $('.toggle-button').click(function() {
            // Toggle the visibility of the next div with class 'extra-info'
            $(this).next('.extra-info').toggle();
        });
    });
</script>



@endsection
