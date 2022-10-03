@extends('layouts.site')

@push('extraStyle')
    <!--Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
@endpush


@section('content')
    <section id="wrapper" class="home">
        <div class="banner d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <h1 class="font-bold text-white text-capitalize">welcome to the bookdrhock</h1>
                </div>
            </div>
        </div>
        <div class="latest-poll-div">
            <div class="container py-5 my-5">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="title">
                                <h2 class="text-capitalize text-blue text-center font-bold title-border">latest polls</h2>
                            </div>
                            <div class="slider-navigation-container position-relative">
                                @if (isset($latest_polls) && !empty($latest_polls) && count($latest_polls) > 0)
                                    <div class="latest-polls-slider-custombtn-prev btn btn-primary"><i
                                            class=" ti-arrow-left"></i></div>
                                    <div class="latest-polls-slider-custombtn-next btn btn-primary"><i
                                            class="ti-arrow-right"></i></div>
                                @endif
                            </div>
                        </div>
                        @if (isset($latest_polls) && !empty($latest_polls) && count($latest_polls) > 0)
                            <div class="swiper latest-polls pt-4 mt-5" data-count="{{ count($latest_polls) }}">
                                <div class="swiper-wrapper">
                                    @foreach ($latest_polls as $latest_poll)
                                        <div class="swiper-slide">
                                            <div class="poll-card w-100">
                                                <div class="card-content">
                                                    <h3 class="card-title text-capitalize text-white">
                                                        {{ $latest_poll->title }}</h3>
                                                    @if (isset($latest_poll->description) && !empty($latest_poll->description))
                                                        <div class="card-text">
                                                            {!! Str::limit($latest_poll->description, 25) !!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="card-link">
                                                    <a href="{{ route('poll.view', $latest_poll->slug) }}" title="Read Full"
                                                        class="btn btn-info">
                                                        <span>View poll</span>
                                                    </a>
                                                </span>
                                                @if (isset($latest_poll->feature_image) && !empty($latest_poll->feature_image))
                                                    <img src="{{ $latest_poll->getImagePath($latest_poll->feature_image, $latest_poll->slug, 'poll_feature_image') }}"
                                                        alt="{{ $latest_poll->title }}" />
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="notice-message">
                                <h4>No any latest poll found.</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="popular-poll-div bg-light">
            <div class="container py-5 my-5">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="title">
                                <h2 class="text-capitalize text-blue text-center font-bold title-border">popular polls
                                </h2>
                            </div>
                            <div class="slider-navigation-container position-relative">
                                @if (isset($popular_polls) && !empty($popular_polls) && count($popular_polls) > 0)
                                    <div class="popular-polls-slider-custombtn-prev btn btn-primary"><i
                                            class=" ti-arrow-left"></i></div>
                                    <div class="popular-polls-slider-custombtn-next btn btn-primary"><i
                                            class="ti-arrow-right"></i></div>
                                @endif
                            </div>
                        </div>
                        @if (isset($popular_polls) && !empty($popular_polls) && count($popular_polls) > 0)
                            <div class="swiper popular-polls pt-4 mt-5" data-count="{{ count($popular_polls) }}">
                                <div class="swiper-wrapper">
                                    @foreach ($popular_polls as $popular_poll)
                                        <div class="swiper-slide">
                                            <div class="poll-card w-100">
                                                <div class="card-content">
                                                    <h3 class="card-title text-capitalize text-white">
                                                        {{ $popular_poll->title }}</h3>
                                                    @if (isset($popular_poll->description) && !empty($popular_poll->description))
                                                        <div class="card-text">
                                                            {!! Str::limit($popular_poll->description, 25) !!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="card-link">
                                                    <a href="{{ route('poll.view', $popular_poll->slug) }}"
                                                        title="Read Full" class="btn btn-info">
                                                        <span>View poll</span>
                                                    </a>
                                                </span>
                                                @if (isset($popular_poll->feature_image) && !empty($popular_poll->feature_image))
                                                    <img src="{{ $popular_poll->getImagePath($popular_poll->feature_image, $popular_poll->slug, 'poll_feature_image') }}"
                                                        alt="{{ $popular_poll->title }}" />
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="notice-message">
                                <h4>No any popular poll found.</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="category-poll-div">
            <div class="container py-5 my-5">
                @if (isset($categorywise_polls) && !empty($categorywise_polls) && count($categorywise_polls) > 0)
                    @foreach ($categorywise_polls as $category => $polls)
                        <div class="row @if (!$loop->first) mt-5 pt-5 @endif">
                            <div class="col-md-12 mb-5">
                                <div class="title text-center">
                                    <h2 class="text-capitalize text-blue text-center font-bold title-border-center">
                                        {{ $category }}</h2>
                                </div>
                                @if (isset($polls) && !empty($polls) && count($polls) > 0)
                                    <div class="card-container mt-5 pt-5">
                                        @foreach ($polls as $poll)
                                            <div class="poll-card">
                                                <div class="image-container">
                                                    <a href="{{ route('poll.view', $poll->slug) }}">
                                                        <img src="{{ $poll->getImagePath($poll->feature_image, $poll->slug, 'poll_feature_image') }}"
                                                            alt="{{ $poll->title }}" />
                                                    </a>
                                                </div>
                                                <div class="content-container">
                                                    <h3 class="m-0 text-capitalize">
                                                        <a href="{{ route('poll.view', $poll->slug) }}"
                                                            class="text-dark">{{ $poll->title }}</a>
                                                    </h3>
                                                    <a href="{{ route('poll.view', $poll->slug) }}"
                                                        class="btn btn-primary mt-3">View poll</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="notice-message">
                                        <h4>No any {{ $category }}'s poll found.</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="notice-message">
                        <h4>No any poll's category found.</h4>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('extraScript')
    <!-- Swiper-js  -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
@endpush
