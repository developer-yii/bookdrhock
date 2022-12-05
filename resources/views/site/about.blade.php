@extends('layouts.site')
@section('title', 'About')

@section('content')
    <section id="wrapper" class="about">
        <div class="banner d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <h1 class="font-bold text-white text-capitalize">About</h1>
                </div>
            </div>
        </div>
        <div class="category-poll-div">
            <div class="container py-5 my-5">
                <div class="row">
                    <div class="col-md-6 d-flex flex-column justify-content-center pr-5">
                        <div class="title-box">
                            <h2 class="text-capitalize font-bold">About Us</h2>
                        </div>
                        <div class="about-info mt-3">
                            <p>Fandomz.org is a survey/poll hosting and publishing service. The platform was established for
                                publishers to create polls and surveys and for users to easily and securely vote on any kind
                                of poll or survey on the internet.</p>
                            <p>The platform was built to focus on fulfilling the needs of carrying out a survey/poll
                                competition on the internet in whatever sector is needed be it politics, sports
                                entertainment and more.</p>
                            <p>With adequate security for both publishers and voters, Fandomz can be said to be the most
                                reasonable poll hosting and publishing service right now.</p>
                        </div>
                        <div class="button-container my-2">
                            <a href="{{ route('site.contact') }}" class="btn btn-primary">Contact us</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ @asset('assets/images/about-info.jpg') }}" alt="About" class="w-100">
                    </div>
                </div>
            </div>
            <div class="container py-5 my-5">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ @asset('assets/images/about-info.jpg') }}" alt="About" class="w-100">
                    </div>
                    <div class="col-md-6 d-flex flex-column justify-content-center pl-5">
                        <div class="title-box">
                            <h2 class="text-capitalize font-bold">polls/survey publishing:</h2>
                        </div>
                        <div class="about-info mt-3">
                            <p>Fandomz.org host and publish polls on several topics based on the request of users on the
                                website, the charge for this is between 0-5 dollars (one-time fee). The poll will only be
                                available on our website only. Links can be shared to your audience on the internet to vote.
                            </p>
                        </div>
                        <div class="button-container my-2">
                            <a href="{{ route('site.contact') }}" class="btn btn-primary">Contact us</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container py-5 my-5">
                <div class="row">
                    <div class="col-md-6 d-flex flex-column justify-content-center pr-5">
                        <div class="title-box">
                            <h2 class="text-capitalize font-bold">premium survey/polls hosting:</h2>
                        </div>
                        <div class="about-info mt-3">
                            <p>This option is for professional survey and poll organisers on the internet who wants to embed
                                polls on their website, fully hosted by us with the best security there is. Fandomz.org
                                polls are protected by several firewalls, and security validation on polls e.g Google
                                Recaptcha, Maths Captcha, GeeTest and many more.</p>
                            <p>Pricing for premium accounts starts at $99 per month.</p>
                        </div>
                        <div class="button-container my-2">
                            <a href="{{ route('site.contact') }}" class="btn btn-primary">Contact us</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ @asset('assets/images/about-info.jpg') }}" alt="About" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
