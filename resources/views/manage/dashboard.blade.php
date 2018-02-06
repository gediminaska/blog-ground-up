@extends('layouts.manage')

@section('content')
    <div id="app">
    <activity-graph></activity-graph>
    </div>
@endsection

@section('scripts')
    <script>

        const app = new Vue({
            el: '#app',
            data: {
                api_token: '{{Auth::user()->api_token}}'
            }
        });

    </script>
@endsection