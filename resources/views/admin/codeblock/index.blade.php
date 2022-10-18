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
            <h4 class="page-title text-capitalize">{{ __('codeblock') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li class="active">{{ __('codeblock') }}</li>
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
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('All codeblock') }}</h2>
                </div>
                <form action="{{ route('codeblock.createorupdate') }}" method="POST" id="insert-codeblock-form">
                    @csrf
                    @if (isset($header_codeblock) && !empty($header_codeblock))
                        <!--row-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="header_codeblock"
                                        class="control-label">{{ __('Insert code below the header') }}</label>
                                    <textarea name="header_codeblock" id="header_codeblock" class="form-control w-100" rows="10">{!! $header_codeblock->codeblock ? $header_codeblock->codeblock : '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                    @endif
                    @if (isset($footer_codeblock) && !empty($footer_codeblock))
                        <!--row-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="footer_codeblock"
                                        class="control-label">{{ __('Insert code above the footer') }}</label>
                                    <textarea name="footer_codeblock" id="footer_codeblock" class="form-control w-100" rows="10">{!! $footer_codeblock->codeblock ? $footer_codeblock->codeblock : '' !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--row-->
                    @endif
                    <button type="submit" class="btn btn-primary" id="addorupdate-codeblock">{{ __('Submit') }}</button>
                </form>
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
            indexUrl: "{{ route('codeblock') }}",
            addOrUpdateUrl: "{{ route('codeblock.createorupdate') }}",
        }
    </script>
    @if ($message = Session::get('success'))
        <script type="text/javascript">
            $(document).ready(function() {
                showMessage('success', "{{ $message }}")
            })
        </script>
    @endif
    <script src="{{ addAdminJsLink('codeblock.js') }}" type="text/javascript"></script>
@endpush
