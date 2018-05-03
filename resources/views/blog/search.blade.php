@extends ('layouts.app')
@section('title', ' | Blog')

@section('content')
    <div id="app">
        <ais-index
                app-id="{{ config('scout.algolia.id') }}"
                api-key="{{ config('scout.algolia.key') }}"
                index-name="posts"
                query="{{ request('q') }}"
        >
            <div class="columns">

                <div class="column">
                    <div class="title">Search results:</div>
                    <ais-results>
                        <template scope="{ result }">
                            <ul>
                                <li>
                                    <a :href="result.slug">
                                        <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                                    </a>
                                    <span class="aa-suggestion" v-html='result._snippetResult.body.value'></span>
                                </li>
                                <br>
                            </ul>
                        </template>
                    </ais-results>
                </div>

                <div class="column is-one-quarter">
                    <ais-input placeholder="Search for posts" :autofocus="true" class="aa-input-search" ></ais-input>
                    <p>Categories</p>
                    <ais-refinement-list attribute-name="category.name"></ais-refinement-list>
                    <p>Tags</p>
                    <ais-refinement-list attribute-name="tags"></ais-refinement-list>
                    <p>User</p>
                    <ais-refinement-list attribute-name="user.name"></ais-refinement-list>
                </div>
            </div>

        </ais-index>
        @endsection


    </div>


@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {},
            methods: {},
        });
    </script>
@stop