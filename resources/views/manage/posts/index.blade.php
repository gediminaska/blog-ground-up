@extends ('layouts.manage')

@section('content')
    <h1 class="title is-2">Posts & Drafts</h1>
    <div id="app">
        <template>
            <section>
                @if(count($published) + count($submitted) + count($drafts) == 0)
                    <h1 class="title">Sorry, you do not have any posts or drafts.</h1>
                @endif
                <b-tabs v-model="activeTab">
                    <b-tab-item label="Published ({{count($published)}})" icon="check">
                        @foreach($published as $post)
                            <div class="columns">
                                <div class="column is-two-thirds">
                                    <span class="title is-5"><a href="{{ route('posts.show', $post->id) }}" style="color: inherit">{{$post->title}}</a></span>
                                    <small> Has {{ $post->comments_count }} {{ str_plural('comment', $post->comments_count) }} </small>
                                    <h4>{{substr(strip_tags($post->body), 0, 300)}}{{ strlen(strip_tags($post->body))>300 ? "..." : ""}}</h4>
                                    <p><strong>Category: </strong> {{ $post->category->name }}</p>
                                </div>
                                <div class="column is-one-third">
                                    <div class="columns">
                                        <div class="column">
                                            {{ Html::linkRoute('posts.show', 'View', [$post->id], ['class'=>'button is-secondary is-fullwidth']) }}
                                        </div>
                                    </div>
                                    <div class="columns">
                                        <div class="column is-half">
                                            {{ Html::linkRoute('posts.edit', 'Edit', [$post->id], ['class'=>'button is-primary is-fullwidth']) }}
                                        </div>
                                        <div class="column is-half">
                                            {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                                            {{ Form::submit('Delete', ['class'=>'button is-danger is-fullwidth', 'style'=>'color: #721c24']) }}
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </b-tab-item>

                    <b-tab-item label="Submitted ({{count($submitted)}})" pack="fas" icon="clock">
                        @foreach($submitted as $post)
                            <div class="columns">
                                <div class="column is-two-thirds">
                                    <span class="title is-5"><a href="{{ route('posts.show', $post->id) }}" style="color: inherit">{{$post->title}}</a></span>
                                    <small> Has {{ $post->comments_count }} {{ str_plural('comment', $post->comments_count) }} </small>
                                    <h4>{{substr(strip_tags($post->body), 0, 300)}}{{ strlen(strip_tags($post->body))>300 ? "..." : ""}}</h4>
                                    <p><strong>Category: </strong> {{ $post->category->name }}</p>
                                </div>
                                <div class="column is-one-third">
                                    <div class="columns">
                                        <div class="column is-half">
                                            {{ Html::linkRoute('posts.edit', Auth::user()->hasPermission('publish-post') ? 'Manage' : 'Edit', [$post->id], ['class'=>'button is-primary is-fullwidth']) }}
                                        </div>
                                        <div class="column is-half">
                                            {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                                            {{ Form::submit('Delete', ['class'=>'button is-danger is-fullwidth', 'style'=>'color: #721c24']) }}
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </b-tab-item>

                    <b-tab-item label="Drafts ({{count($drafts)}})" icon="pencil">
                        @foreach($drafts as $post)
                            <div class="columns">
                                <div class="column is-two-thirds">
                                    <span class="title is-5"><a href="{{ route('posts.show', $post->id) }}" style="color: inherit">{{$post->title}}</a></span>
                                    <small> Has {{ $post->comments_count }} {{ str_plural('comment', $post->comments_count) }} </small>
                                    <h4>{{substr(strip_tags($post->body), 0, 300)}}{{ strlen(strip_tags($post->body))>300 ? "..." : ""}}</h4>
                                    <p><strong>Category: </strong> {{ $post->category->name }}</p>
                                </div>
                                <div class="column is-one-third">
                                    <div class="columns">
                                        <div class="column is-half">
                                            {{ Html::linkRoute('posts.edit', 'Continue writing', [$post->id], ['class'=>'button is-primary is-fullwidth']) }}
                                        </div>
                                        <div class="column is-half">
                                            {{ Form::open(['route'=> ['posts.destroy', $post->id], 'method'=>'DELETE']) }}
                                            {{ Form::submit('Delete draft', ['class'=>'button is-danger is-fullwidth', 'style'=>'color: #721c24']) }}
                                            {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            }
        })
    </script>

@endsection
