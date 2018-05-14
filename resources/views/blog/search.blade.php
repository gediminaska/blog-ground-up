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
                                    <a class="aa-suggestion" v-html="'<strong>' + result._highlightResult.title.value + ' </strong>' + result._snippetResult.body.value + '...'" :href="result.slug" style="color: inherit"></a>
                                </li>
                                <br>
                            </ul>
                        </template>
                    </ais-results>
                </div>

                <div class="column is-one-quarter">
                    <nav class="panel">
                        <p class="panel-heading">Refine search</p>
                        <div class="panel-block">
                            <ais-input placeholder="Search for posts" :autofocus="true" class="aa-input-search" ></ais-input>
                        </div>
                        <div class="panel-block">
                            <div class="control">
                                <div class="title is-5">Categories</div>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="control">
                                <ais-refinement-list attribute-name="category.name"></ais-refinement-list>
                            </div>
                        </div>

                        <div class="panel-block">
                            <div class="control">
                                <div class="title is-5">Tags</div>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="control">
                                <ais-refinement-list attribute-name="tags.name"></ais-refinement-list>
                            </div>
                        </div>

                        <div class="panel-block">
                            <div class="control">
                                <div class="title is-5">User</div>
                            </div>
                        </div>

                        <div class="panel-block">
                            <div class="control">
                                <ais-refinement-list attribute-name="user.name"></ais-refinement-list>
                            </div>
                        </div>


                        <div class="panel-block">
                            <div class="control">
                                <div class="title is-5">Text length</div>
                            </div>
                        </div>
                        <div class="panel-block">
                            <div class="control">
                                <ais-refinement-list attribute-name="text_length"></ais-refinement-list>
                            </div>
                        </div>

                    </nav>
                 </div>
            </div>

        </ais-index>
        @endsection


    </div>


@section('scripts')
    <script>
        let app = new Vue({
            el: '#app',
            data: {},
            methods: {},
        });
    </script>
@stop