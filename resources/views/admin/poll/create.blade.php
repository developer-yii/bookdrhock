@extends('layouts.admin')

@push('extraStyle')
    <!-- Switchery switch  -->
    <link href="{{ asset('plugins/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css">

    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">

    <!-- Date picker plugins css -->
    <link href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Touchspin plugins css -->
    <link href="{{ asset('plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Dropify plugins css -->
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Dropify plugins css -->
    <link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title text-capitalize">{{ __('Add New Poll') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li><a href="{{ route('poll') }}">{{ __('Polls') }}</a></li>
                <li class="active">{{ __('Add New') }}</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <form action="#" method="POST" id="poll-form" enctype="multipart/form-data">
        <div class="row poll-form-container">
            <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 col-xs-12 right-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ __('Add New') }}
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="form-container">
                                <div class="form-body">
                                    <input type="hidden" name="id" id="id">
                                    <!--row-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="title" class="control-label">{{ __('Title*') }}</label>
                                                <input type="text" id="title" name="title" class="form-control"
                                                    value="{{ old('title') }}">
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--row-->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="datepicker_container">
                                                <label for="start_datetime"
                                                    class="control-label">{{ __('Start Datetime') }}</label>
                                                <input type="text" class="form-control datetimepicker-custom"
                                                    id="start_datetime" value="{{ old('start_datetime') }}"
                                                    name="start_datetime" placeholder="dd-mm-yyyy"
                                                    data-date-container='#datepicker_container'>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="datepicker_container">
                                                <label for="end_datetime"
                                                    class="control-label">{{ __('End Datetime') }}</label>
                                                <input type="text" class="form-control datetimepicker-custom"
                                                    id="end_datetime" value="{{ old('end_datetime') }}" name="end_datetime"
                                                    placeholder="dd-mm-yyyy" data-date-container='#datepicker_container'>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--row-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description"
                                                    class="control-label">{{ __('Description') }}</label>
                                                <textarea type="text" id="description" name="description ckeditor" class="form-control">{{ old('description') }}</textarea>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--row-->
                                    <!--row-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4
                                                class="form-field-title font-weight-bold text-capitalize border-bottom pb-2 mb-5 mt-2">
                                                {{ __('Poll options') }}</h4>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="options-container">
                                                <div class="option-card">
                                                    <div class="row h-100">
                                                        <div class="option-card-form">
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-3">
                                                                    <div class="custom-image-upload-container">
                                                                        <div class="image-upload-wrap">
                                                                            <input
                                                                                class="form-control option_image file-upload-input"
                                                                                type='file' id="option_image"
                                                                                name="option[0][image]"
                                                                                value="{{ old('option_image') }}">
                                                                            <div class="drag-text-message">
                                                                                <i class="icon-cloud-upload"></i>
                                                                                <p>Drag and drop a file or select add Image
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="file-upload-content">
                                                                            <img class="file-upload-image" src=""
                                                                                alt="upload image" />
                                                                            <i class="fa fa-times-circle remove-image"></i>
                                                                        </div>
                                                                    </div>
                                                                    <span class="help-block error-span"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-0">
                                                                    <input type="text" id="option_title"
                                                                        name="option[0][title]"
                                                                        class="form-control option_title"
                                                                        value="{{ old('option_title') }}"
                                                                        placeholder="Option Title*">
                                                                    <span class="help-block error-span"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 align-self-end">
                                                            <button type="button" class="btn btn-danger w-100 delete-btn"
                                                                id="delete-btn" disabled>{{ __('Delete') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="option-card plus-btn">
                                                    <i class="fa fa-plus-square"></i>
                                                    <p>Click here to add more options</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--row-->
                                </div>
                            </div>
                        </div>
                        {{-- <div class="panel-footer">
                            <button type="button" class="btn btn-primary" id="addorupdate-poll">{{ __('Submit') }}
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-xs-12 left-box">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('information') }}</div>
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label for="popular_tag"
                                                class="control-label mb-0 pr-2">{{ __('Is that popular poll?') }}</label>
                                            <input type="checkbox" name="popular_tag" id="popular_tag" class="js-switch"
                                                data-color="#13dafe" />
                                        </div>
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            @if (isset($categories) && !empty($categories))
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="category" class="control-label">{{ __('Category') }}</label>
                                            <select name="category" id="category"
                                                class="custom-select width-equal col-12">
                                                <option value="">{{ __('Uncategorized') }}</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" class="text-capitalize">
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--row-->
                            @endif
                            @if (isset($voteHours) && !empty($voteHours))
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="vote_schedule"
                                                class="control-label">{{ __('User can vote again after') }}</label>
                                            <select name="vote_schedule" id="vote_schedule"
                                                class="custom-select width-equal col-12">
                                                @foreach ($voteHours as $voteHours_key => $voteHours_value)
                                                    <option value="{{ $voteHours_key }}" class="text-capitalize">
                                                        {{ $voteHours_value }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--row-->
                            @endif
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="vote_add">{{ __('User can vote') }}</label>
                                        <input id="vote_add" type="text" value="0" name="vote_add"
                                            data-bts-button-down-class="btn btn-default btn-outline"
                                            data-bts-button-up-class="btn btn-default btn-outline">
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="option_select">{{ __('How many option user select?') }}</label>
                                        <input id="option_select" type="text" value="0" name="option_select"
                                            data-bts-button-down-class="btn btn-default btn-outline"
                                            data-bts-button-up-class="btn btn-default btn-outline">
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                            @if (isset($recaptcha) && !empty($recaptcha))
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="captcha_type"
                                                class="control-label">{{ __('Captcha Type') }}</label>
                                            <select name="captcha_type" id="captcha_type"
                                                class="custom-select width-equal col-12">
                                                @foreach ($recaptcha as $recaptcha_key => $recaptcha_value)
                                                    <option value="{{ $recaptcha_key }}" class="text-capitalize">
                                                        {{ $recaptcha_value }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--row-->
                            @endif
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="feature_image">{{ __('Feature Image') }}</label>
                                        <div class="custom-image-upload-container">
                                            <div class="image-upload-wrap">
                                                <input class="form-control file-upload-input" type='file'
                                                    id="feature_image" name="feature_image"
                                                    value="{{ old('feature_image') }}">
                                                <div class="drag-text-message">
                                                    <i class="icon-cloud-upload"></i>
                                                    <p>Drag and drop a file or select add Image</p>
                                                </div>
                                            </div>
                                            <div class="file-upload-content">
                                                <img class="file-upload-image" src="" alt="upload image" />
                                                <i class="fa fa-times-circle remove-image"></i>
                                            </div>
                                        </div>
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-primary w-100"
                                id="addorupdate-poll">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- /.row -->
@endsection

@push('extraScript')
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <!-- Tinymce  -->
    <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap switch  -->
    <script src="{{ asset('plugins/switchery/dist/switchery.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap Touchspin  -->
    <script src="{{ asset('plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript">
    </script>

    <!-- Dropify  -->
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}" type="text/javascript"></script>

    <!-- Moment  -->
    <script src="{{ asset('plugins/bootstrap-datetimepicker/moment.min.js') }}"></script>

    <!-- Bootstrap Datetimepicker  -->
    <script src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('poll') }}",
            addOrUpdateUrl: "{{ route('poll.createorupdate') }}",
            deleteUrl: "{{ route('poll.delete') }}"
        }

        function pollEditRedirect(id) {
            url = "{{ route('poll.editForm', ':id') }}";
            url = url.replace(':id', id);
            window.location.href = url;
        }
    </script>
    <script src="{{ addAdminJsLink('poll/poll-create.js') }}" type="text/javascript"></script>
@endpush
