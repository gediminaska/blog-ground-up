@extends('layouts.app')

@section('content')
    <p class="field">
        <a href="{{ route('blog.index') }}" class="button is-large is-primary is-fullwidth" style="padding: 40px">
            <span class="icon is-large">
                <i class="fab fa-blogger"></i>
            </span>
            <span>
                Go to Blog
            </span>
        </a>
    </p>

    <h2 class="title is-2">Recent posts</h2>
    @foreach($posts as $post)
        <h3 class="title is-5">{{ Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'color:inherit']) }}</h3>
        <h3 class="subtitle is-7">posted {{ $post->created_at->diffForHumans() }} by {{$post->user->name}}</h3>
        <h4>{{ substr($post->body, 0, 200) . (strlen($post->body)>200 ? '...' : '') }}</h4>
        <hr>
    @endforeach

@endsection

@section('panel-right')
    <div id="app">
        <div class="title is-4">Recent activity</div>
        <template>
            <section>
                <b-tabs v-model="activeTab">
                    <b-tab-item label="Users">
                        <br>
                        @foreach($users as $user)
                            <span class="far fa-user"> </span><strong> {{ $user->name }}</strong>
                            <h6 style="margin-bottom:0px; margin-top:6px">
                                Joined {{ $user->created_at->diffForHumans() }}</h6>
                            <h6 style="margin-bottom:0px; margin-top:6px">Email: {{ $user->email }}</h6>
                            <h6 style="margin-bottom:0px; margin-top:6px">Posts created: {{ count($user->posts) }}</h6>
                            <hr>
                        @endforeach
                    </b-tab-item>

                    <b-tab-item label="Comments">
                        <br>

                        <div v-for="comment in comments">
                            <h5>In post <strong>@{{ comment.post.title }}</strong>, @{{ getDate(comment.created_at) }}
                            </h5>
                            <span class="far fa-comment"></span><span><strong> @{{  comment.user_name }}: </strong>@{{ comment.body }}</span>
                            <hr>
                        </div>
                        {{--@foreach($comments as $comment)--}}
                        {{--<h5>{{Html::linkRoute('blog.show', 'In post "' . $comment->post->title . '", ' . $comment->created_at->diffForHumans(), $comment->post->slug, ['style'=>'color:inherit'])}}</h5>--}}
                        {{--<span class="far fa-comment"></span><span><strong> {{ $comment->user_name }}: </strong>{{ substr($comment->body, 0, 100) . (strlen($comment->body)>100 ? '...' : '') }}</span>--}}
                        {{--<hr>--}}
                        {{--@endforeach--}}
                    </b-tab-item>

                </b-tabs>
            </section>
        </template>
    </div>
@endsection

@section('panel-left')
    <div id="app3">
        <div class="title is-4">Features</div>
        <template>
            <section>
                <b-tabs v-model="activeTab">
                    <b-tab-item label="Back-end">
                        <div class="subtitle">Multilevel authentification</div>
                    </b-tab-item>

                    <b-tab-item label="Front-end">
                        <div class="subtitle">Vue components</div>
                        <p>Laratoaster alerts</p>
                        <p>Chart.js charts in management dashboard</p>
                        <p>Custom made hideable side menu for management panel</p>
                        <p>Custom made post slug generator: title gets slugified, checked in DB if unique, modified if necessary. Ability to edit generated slug.</p>
                        <hr>
                        <div class="subtitle">Pusher service</div>
                        <p>Real time comments in post view and welcome page</p>
                        <p>Comment date difference calculated every second</p>
                        <hr>
                        <div class="subtitle">Pusher service</div>
                        <p>Real time comments</p>
                    </b-tab-item>
                </b-tabs>
            </section>
        </template>

    </div>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                activeTab: 0,
                comments: {},
                secondsPassed: 0,
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
                    axios.get('/api/blog/comments')
                        .then((response) => {
                            this.comments = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },
                listen() {
                    Echo.channel('blog')
                        .listen('NewCommentInBlog', (comment) => {
                            this.comments.unshift(comment);
                            this.comments.pop();
                        })
                },
                getDate(date) {
                    return this.$moment(date).add(2, 'hours').fromNow();
                }

            },
        });
        var app3 = new Vue({
            el: '#app3',
            data: {
                activeTab: 0,
            }
        })
    </script>
@endsection