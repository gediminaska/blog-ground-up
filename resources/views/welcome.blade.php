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
        <h3 class="title is-5">{{ Html::linkRoute('blog.show', $post->title, $post->slug, ['style'=>'color:inherit']) }}</h3> <h3 class="subtitle is-7">posted {{ $post->created_at->diffForHumans() }} by {{$post->user->name}}</h3>
        <h4>{{ substr($post->body, 0, 200) . (strlen($post->body)>200 ? '...' : '') }}</h4>
        <hr>
    @endforeach

@endsection

@section('panel-right')
<div class="title is-4">Recent activity</div>
    <template>
        <section>
            <b-tabs v-model="activeTab">
                <b-tab-item label="Users">
                    <br>
                    @foreach($users as $user)
                        <span class="far fa-user"> </span><strong> {{ $user->name }}</strong>
                        <h6 style="margin-bottom:0px; margin-top:6px">Joined {{ $user->created_at->diffForHumans() }}</h6>
                        <h6 style="margin-bottom:0px; margin-top:6px">Email:  {{ $user->email }}</h6>
                        <h6 style="margin-bottom:0px; margin-top:6px">Posts created: {{ count($user->posts) }}</h6>
                        <hr>
                    @endforeach
                </b-tab-item>

                <b-tab-item label="Comments">
                    <br>
                    @foreach($comments as $comment)
                        <h5>{{Html::linkRoute('blog.show', 'In post "' . $comment->post->title . '", ' . $comment->created_at->diffForHumans(), $comment->post->slug, ['style'=>'color:inherit'])}}</h5>
                        <span class="far fa-comment"></span><span><strong> {{ $comment->user_name }}: </strong>{{ substr($comment->body, 0, 100) . (strlen($comment->body)>100 ? '...' : '') }}</span>
                        <hr>
                    @endforeach
                </b-tab-item>

            </b-tabs>
        </section>
    </template>
@endsection

@section('scripts')
    <script>
        export default {
            data() {
                return {
                    activeTab: 0
                }
            }
        }
    </script>
@endsection