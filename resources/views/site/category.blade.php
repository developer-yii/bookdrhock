@extends('layouts.site')
@section('title', (is_object($category) ? $category->name : $category) . '- FANDOMZ')
{{-- @section('meta_description', '') --}}
@section('meta_keywords', '')

@section('content')
    <section id="wrapper" class="category-single category-{{ is_object($category) ? $category->name : $category }}">
        <div class="banner d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <h1 class="font-bold text-white text-capitalize">{{ is_object($category) ? $category->name : $category }}</h1>
                </div>
            </div>
        </div>
        <div class="category-poll-div">
            <div class="container py-5 my-5">
                @if (isset($polls) && !empty($polls) && count($polls) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-grid-container-ctm">
                                @foreach ($polls as $poll)
                                    <div class="poll-card">
                                        <div class="image-container">
                                            <a href="{{ route('poll.view', $poll->slug) }}">
                                                @if (isset($poll->feature_image) && !empty($poll->feature_image))
                                                    <img src="{{ $poll->getImagePath($poll->feature_image, $poll->slug, 'poll_feature_image') }}"
                                                        alt="{{ $poll->title }}" />
                                                @else
                                                    <img src="{{ @asset('assets/images/bodybg.jpg') }}"
                                                        alt="{{ $poll->title }}" />
                                                @endif
                                            </a>
                                        </div>
                                        <div
                                            class="content-container equal-height-box d-flex flex-column justify-content-between">
                                            <div class="box-title">
                                                <h3 class="m-0 text-capitalize font-bold">
                                                    <a href="{{ route('poll.view', $poll->slug) }}"
                                                        class="">{{ $poll->title }}</a>
                                                </h3>
                                                @if (isset($poll->description) && !empty($poll->description))
                                                    <div class="card-text mt-3">
                                                        {!! Str::limit(Str::replaceArray('&nbsp;', [''], strip_tags($poll->description)), 100) !!}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="boc-btn mt-4">
                                                <a href="{{ route('poll.view', $poll->slug) }}" class="btn btn-primary">View
                                                    poll</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="notice-message">
                        <h4>No any {{ is_object($category) ? $category->name : $category }}'s poll found.</h4>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
