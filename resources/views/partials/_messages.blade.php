@if(Session::has('success'))

       <template>
           <section>
               <b-notification type="is-success" has-icon>
                   <strong>Success: </strong>{{ Session::get('success') }}
               </b-notification>
           </section>
       </template>

@endif
@if (session('status'))

        <template>
            <section>
                <b-notification type="is-success" has-icon>
                    <strong>Success: </strong>{{ session('status') }}
                </b-notification>
            </section>
        </template>

@endif

@if(count($errors)>0)

        <template>
            <section>
                <b-notification type="is-danger" has-icon>
                    <ul>Errors:</ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </b-notification>
            </section>
        </template>


@endif