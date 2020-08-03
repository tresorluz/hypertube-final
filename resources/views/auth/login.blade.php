@extends('layoutsLogin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" >{{ __('text.login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                                <label for="email" class="col-form-label" >{{ __('Email address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"  value="{{ old('email') }} " required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group row">
                                <label for="password" class="col-form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Please Enter Your Password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                     @endif
                                </div>
                        </div>

                        <div class="form-group row">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                        </div>        

                        <div class="form-group row">
                                <a id="github-button" class="btn btn-block btn-social btn-github "href="{{ url('socialauth/github') }}">
                                   <i class="fa fa-github fa-2x"></i> Sign in With Github
                                </a>
                        </div> 

                        <div class="form-group row" style="margin:7px 75px 5px 150px">   
                                <a href="{{ url('socialauth/intra') }}" class="MyLink">
                                    <img width="60px" src="./Pictures/42logo.png" /><span class="MyLinkText" style="color:black">Sign in With Intra</span>
                                </a> 
                                <!-- <a class="btn btn-sm " href="{{ url('socialauth/intra') }}">
                                    <div class="left">
                                        <img width="60px" style="margin:7px 75px 0px 100px"  
                                        src="./Pictures/42logo.png" />Sign in with Intra
                                    </div>
                                </a> -->
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
