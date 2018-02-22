@extends ('layouts.app')
@section('title', ' | Blog')

@section('content')
    <a class="button is-info is-outlined" style="min-width: 200px"
       href="{{ URL::previous() }}">
    <span class="icon">
      <i class="fas fa-chevron-circle-left"></i>
    </span>
        <span>Back to blog</span>
    </a>
    <h2 class="title is-2">{{ $post->title }}</h2>
    <img src="{{ asset('images/' . $post->image) }}" style="display:block; margin: auto; width: 100%">
    <h4 style="margin:auto; font-size:larger; margin-top: 30px; text-align: justify; white-space: pre-line; max-width:700px">{{ $post->body }}</h4>
    <br><span style="margin-top:20px"><strong>Tags:</strong></span>
    @foreach($post->tags as $tag)
        <span class="tag is-dark">{{ $tag->name }}</span>
    @endforeach
    <br>

    @if(count($post->comments)>0)
        <hr>

    @endif


    <div id="app3">
        <div v-if="comments.length > 0">
            <span class="far fa-comments fa-2x"></span><span class="title is-4"> Comments:</span><br>
        </div>
        <div class="title" v-else>No comments</div>
        <div v-for="comment in comments">
            <br>
            <strong>@{{comment.user_name}} </strong><span>@{{getDate(comment.created_at)}} said:</span>
            <p>@{{comment.body}}</p>
        </div>
        <hr>

        <h3 class="title is-4">Add a comment:</h3>
        {{ Form::open(['action'=>'CommentsController@store']) }}
        {{ Form::label('body', 'Comment text:', ['class'=>'label m-t-10']) }}
        {{ Form::textarea('body', null, ['class'=>'control textarea', 'style'=>'max-width:500px; min-width:0', 'v-model'=>'commentBox']) }}
        {{ Form::hidden('post_id', $post->id) }}
        {{ Form::close() }}
        <button class="button" @click.prevent="postComment">Save comment</button>

    </div>


@endsection

@section('scripts')
    <script>
        var app3 = new Vue({
            el: '#app3',
            data: {
                comments: {},
                commentBox: '',
                post: {!! $post->toJson() !!},
                api_token: '{!! Auth::check() ? Auth::user()->api_token : ''!!}',
            },
            mounted() {
                this.getComments();
                this.listen();
                this.$moment.relativeTimeThreshold('s', 59);
                this.$moment.relativeTimeThreshold('ss', 2);
                setInterval(() => {
                    for (let comment in this.comments) {
                        this.comments[comment].created_at = this.$moment(this.comments[comment].created_at);
                    }
                }, 1000)
            },
            methods: {
                getComments() {
                    axios.get('/api/posts/' + this.post.id + '/comments')
                        .then((response) => {
                            this.comments = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                postComment() {
                    axios.post('/api/posts/' + this.post.id + '/comment', {
                        body: this.commentBox,
                        post_id: this.post.id,
                        api_token: this.api_token,
                    })
                        .then((response) => {
                            this.comments.unshift(response.data);
                            this.commentBox = '';
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                },
                getDate(date) {
                    return this.$moment(date).add(2, 'hours').fromNow();
                },
                listen() {
                    Echo.channel('post.' + this.post.id)
                        .listen('NewComment', (comment) => {
                            this.comments.unshift(comment);
                        });
                },
            },
        })
    </script>

@endsection