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
                    <div class="col-md-6 d-flex flex-column justify-content-center pr-4">
                        <div class="title-box">
                            <h2 class="text-capitalize font-bold">Lorem Ipsum</h2>
                        </div>
                        <div class="about-info mt-3">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                                cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat.</p>
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
