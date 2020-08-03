@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                @if (isset($movie))
                    <video id='video' width="100%" height="450px" controls controlsList="nodownload" preload="none" crossOrigin="anonymous" style="border:2px solid; color:#FF6347">
                        <source src="http://127.0.0.1:3000/stream/{{ $movie->hash }}" type="video/mp4">
                          @if (Auth::user()->language == 'fr')
                              <track src="http://127.0.0.1:3000/subtitles/{{ $movie->imdb }}/fr/{{ $movie->ses }}/{{ $movie->ep }}" kind="subtitles" srclang="fr" label="French" default>
                          @else
                            <track src="http://127.0.0.1:3000/subtitles/{{ $movie->imdb }}/fr/{{ $movie->ses }}/{{ $movie->ep }}" kind="subtitles" srclang="fr" label="French">
                        @endif

                          <track src="http://127.0.0.1:3000/subtitles/{{ $movie->imdb }}/en/{{ $movie->ses }}/{{ $movie->ep }}" kind="subtitles" srclang="en" label="English">


                        {{ __(('text.nosupport')) }}
                    </video>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-muted">{{ $movie->year }}</strong>
                            <strong class="text-muted">{{ $movie->length }}</strong>
                            <strong class="text-muted">{{ $movie->rating }}/10 IMDb</strong>
                        </div>
                        <br />
                        <h4 class="card-text" style="text-align: center; color:#E6E6FA">
                            {{ $movie->title }}
                        </h4>
                          <p class="card-text" style="font-size: 17px; color:#E6E6FA">
                              {{ $movie->plot }}
                          </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span class="font-weight-bold">{{ __(('text.actors')) }}</span >
                                {{ $movie->actors }}
                            </li>
                            <li class="list-group-item"><span class="font-weight-bold">{{ __(('text.directors')) }}</span >
                                {{ $movie->director }}
                            </li>
                            <li class="list-group-item"><span class="font-weight-bold">{{ __(('text.writers')) }}</span >
                                {{ $movie->writer }}
                            </li>
                        </ul>
                    </div>
                @endif


                <div class="card-body">
                <ul class="list-unstyled">
                  @foreach ($comment as $comments)

                  <li class="media my-4">
                    <div class="media-body">
                      <h5 class="mt-0 mb-1"><a href="{{url('UserProfile/' . $comments->user_id)}}">{{$comments->name}}</a></h5>
                      {{$comments->content}}
                      <div class="d-flex justify-content-between align-items-center">
                          <small class="text-muted">{{ $comments->created_at }}</small>
                        </div>
                      </div>
                  </li>

                  @endforeach
                  <li class="media">

                    <div class="media-body">
                      <form action="{{ route('comment.store')}}" method="post">
                        @csrf
                        <!-- @method('PUT') -->
                        <input type="hidden" name="id" value="{{ $movie->id}}">
                        <textarea name="content" cols="25" rows="5" placeholder="{{ __(('text.comment')) }}"></textarea>
                        <!-- <input type="text" name="content" value="" col=5 row=15 placeholder="Your comment"> -->
                        <button class="button is-danger" type="submit">{{ __(('text.send')) }}</button>
                      </form>
                    </div>
                  </li>
                </ul>
              </div>
              
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
          $('#video').on('play', function(e) {
            $.ajaxSetup({
           headers: {
           'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
              }
            });

            $.ajax({
           url: '/public/viewed',
                type: 'POST',
                data: {
                  id_movie: {{$movie->id}},
                  hash: '{{$movie->hash}}',
                  title: '{{$movie->title}}',
                  id_user: {{auth()->id()}}
                },

           dataType: 'JSON'
         });
          })
      })
</script>
@endsection
