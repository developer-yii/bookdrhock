@extends('layouts.admin')

@push('extraStyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <!--switchery CSS -->
    <link href="{{ asset('plugins/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title text-capitalize">{{ __('settings') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li class="active">{{ __('settings') }}</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="white-box">
                <div class="d-flex mb-5 justify-content-between">
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('All settings') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        @if (isset($settings) && !empty($settings) && count($settings) > 0)
                            @foreach ($settings as $setting)
                                <div
                                    class="d-flex align-items-center justify-content-between @if (!$loop->last) mb-5 @endif">
                                    <div class="setting-info">
                                        <h4 class="box-title mb-0">{{ $setting->name }}</h4>
                                        @if (isset($setting->description) && !empty($setting->description))
                                            <p class="text-muted font-13 mb-0">{{ $setting->description }}</p>
                                        @endif
                                    </div>
                                    <div class="setting-btn">
                                        <div class="{{ str_replace(' ', '-', strtolower($setting->name)) }}"
                                            data-setting-id="{{ $setting->id }}">
                                            <input type="checkbox"
                                                name="{{ str_replace(' ', '_', strtolower($setting->name)) }}"
                                                id="{{ str_replace(' ', '_', strtolower($setting->name)) }}"
                                                class="js-switch setting-switch {{ str_replace(' ', '_', strtolower($setting->name)) }}"
                                                data-color="#3d3b3b" data-size="large"
                                                @if (isset($setting->setting) && !empty($setting->setting) && $setting->setting == 1) checked @endif />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('extraScript')
    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <!-- switchery  -->
    <script src="{{ asset('plugins/switchery/dist/switchery.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('setting') }}",
            addOrUpdateUrl: "{{ route('setting.createorupdate') }}",
        }
    </script>
    <script src="{{ addAdminJsLink('setting.js') }}" type="text/javascript"></script>
@endpush
