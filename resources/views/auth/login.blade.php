@extends('layouts.master2')

@section('title')
    تسجيل الدخول - برنامج الفواتير
@stop


@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <style>
        body {
            font-size: 16px;
        }

        /* Style for headings */
        h2 {
            font-size: 1.5rem;
            margin-right: 10%;
        }

        h5 {
            font-size: 1.2rem;
            margin-right: 15%;
        }

        /* Font for buttons */
        .btn-social {
            font-weight: bold;
            /* Other styles remain the same */
        }

        /* Font for labels and form fields */
        label {
            font-weight: bold;
        }

        /* Style for the parent container */
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* Style for individual social login buttons */
        .btn-social {
            display: block;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .google-btn {
            background-color: #db4a39 !important;
            /* Google's red */
        }

        .facebook-btn {
            background-color: #1877f2 !important;
            /* Facebook's blue */
        }

        .twitter-btn {
            background-color: #1da1f2 !important;
            /* Twitter's blue */
        }

        /* Style for the "Or" separator */
        .alt-option {
            text-align: center;
            margin-top: 20px;
            position: relative;
        }

        .alt-option span {
            font-weight: bold;
            color: #333;
            background-color: #fff;
            padding: 0 10px;
        }

        /* Add lines above and below the "Or" string */
        .alt-option::before,
        .alt-option::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #333;
        }

        .alt-option::before {
            left: 0;
        }

        .alt-option::after {
            right: 0;
        }

        /* Adjust the position of the lines */
        .alt-option span {
            position: relative;
            z-index: 1;
            background-color: #fff;
            padding: 0 10px;
        }
    </style>
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The image half -->
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        @include('flash::message')

                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2 style="margin-right:40%">مرحبا بك</h2>
                                            <h5 class="font-weight-semibold mb-4" style="margin-right:15%"> بامكانك تسجيل
                                                الدخول من خلال حساباتك التالية</h5>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <a class="btn btn-social google-btn"
                                                        href="{{ route('auth.socialite.redirect', 'google') }}">
                                                        <i class="lni lni-google"></i> Google
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <a class="btn btn-social facebook-btn"
                                                        href="{{ route('auth.socialite.redirect', 'facebook') }}">
                                                        <i class="lni lni-facebook"></i> Facebook
                                                    </a>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-12">
                                                    <a class="btn btn-social twitter-btn"
                                                        href="">
                                                        <i class="lni lni-twitter"></i> Twitter
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="alt-option">
                                                <span>أو</span>
                                            </div>


                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label>البريد الالكتروني</label>
                                                    <input id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email" autofocus>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label>كلمة المرور</label>

                                                    <input id="password" type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" required autocomplete="current-password">

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <div class="form-group row">
                                                        <div class="col-md-6 offset-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="remember" id="remember"
                                                                    {{ old('remember') ? 'checked' : '' }}>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <label class="form-check-label" for="remember">
                                                                    {{ __('تذكرني') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-main-primary btn-block">
                                                    {{ __('تسجيل الدخول') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->

            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                <div class="row wd-100p mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                        <img src="{{ asset('assets/img/media/aliphia-screen-icon-ar-1.png') }}"
                            class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
@endsection
