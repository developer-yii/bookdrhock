@extends('layouts.site')

@push('extraStyle')
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
        <div class="latest-poll-div py-5 my-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-5 mb-sm-0">
                        <div class="title text-center">
                            <h2 class="text-capitalize text-blue text-center font-bold title-border-center">
                                latest polls</h2>
                        </div>
                        @if (isset($latest_polls) && !empty($latest_polls) && count($latest_polls) > 0)
                            <div class="card-grid-container-ctm mt-2 pt-5">
                                @foreach ($latest_polls as $poll)
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
                                    @if ($loop->iteration == 3)
                                        @php break; @endphp
                                    @endif
                                @endforeach
                            </div>
                            @if (count($latest_polls) > 3)
                                <div class="btn-container text-center mt-5">
                                    <a href="{{ route('site.getCategoryView', 'latest') }}" class="btn btn-primary">View
                                        more</a>
                                </div>
                            @endif
                        @else
                            <div class="notice-message">
                                <h4>No any latest poll found.</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="popular-poll-div bg-light py-5 my-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 mb-5 mb-sm-0">
                        <div class="title text-center">
                            <h2 class="text-capitalize text-blue text-center font-bold title-border-center">
                                popular polls</h2>
                        </div>
                        @if (isset($popular_polls) && !empty($popular_polls) && count($popular_polls) > 0)
                            <div class="card-grid-container-ctm mt-2 pt-5">
                                @foreach ($popular_polls as $poll)
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
                                                <a href="{{ route('poll.view', $poll->slug) }}"
                                                    class="btn btn-primary">View
                                                    poll</a>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($loop->iteration == 3)
                                        @php break; @endphp
                                    @endif
                                @endforeach
                            </div>
                            @if (count($popular_polls) > 3)
                                <div class="btn-container text-center mt-5">
                                    <a href="{{ route('site.getCategoryView', 'popular') }}" class="btn btn-primary">View
                                        more</a>
                                </div>
                            @endif
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
                                        {{ getCategoryName($category) }}</h2>
                                </div>
                                @if (isset($polls) && !empty($polls) && count($polls) > 0)
                                    <div class="card-grid-container-ctm mt-2 pt-5">
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
                                                        <a href="{{ route('poll.view', $poll->slug) }}"
                                                            class="btn btn-primary">View
                                                            poll</a>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($loop->iteration == 3)
                                                @php break; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                    @if (count($polls) > 3)
                                        <div class="btn-container text-center mt-5">
                                            <a href="{{ route('site.getCategoryView', $category) }}"
                                                class="btn btn-primary">View
                                                more</a>
                                        </div>
                                    @endif
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
@endpush
