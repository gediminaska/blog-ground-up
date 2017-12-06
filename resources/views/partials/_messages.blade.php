@if(Session::has('success'))
    <div class="alert alert-success" role ="alert">
        <strong>Success: </strong>{{ Session::get('success') }}
    </div>
@endif

@if(count($errors)>0)
    <div id="app">
        <template>
        <section>
            <b-notification type="is-danger">
                <strong>Errors: </strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </b-notification>
        </section>
        </template>
        <template>
            <section>
                <b-notification>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id fermentum quam. Proin sagittis, nibh id hendrerit imperdiet, elit sapien laoreet elit
                </b-notification>

                <b-notification type="is-info">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id fermentum quam. Proin sagittis, nibh id hendrerit imperdiet, elit sapien laoreet elit
                </b-notification>

                <b-notification type="is-success">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id fermentum quam. Proin sagittis, nibh id hendrerit imperdiet, elit sapien laoreet elit
                </b-notification>

                <b-notification type="is-warning">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id fermentum quam. Proin sagittis, nibh id hendrerit imperdiet, elit sapien laoreet elit
                </b-notification>

                <b-notification type="is-danger">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id fermentum quam. Proin sagittis, nibh id hendrerit imperdiet, elit sapien laoreet elit
                </b-notification>
            </section>
        </template>

    </div>

@endif