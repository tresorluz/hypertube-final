@extends('layoutsProfile.app')
@section('content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">{{ __('text.txt_pic_up')}}</p>
        </header>
        <div class="card-content" style="text-align: center;">
            <div class="content">
                <form action="{{ route('myprofile.store', $users->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <label class="label">{{ __('text.profilepic')}}</label>
                        <div class="control">
                          <input id="image" type="file" class="input @error('image') is-danger @enderror"  name="image" accept="image/*" placeholder="Your image">
                        </div>
                        @error('title')
                            <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="control">
                          <button class="button is-link">{{ __('text.send')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
