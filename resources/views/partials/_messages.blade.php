@if(Session::has('success'))
   <div id="app">
       <template>
           <section>
               <b-notification type="is-success" has-icon>
                   <strong>Success: </strong>{{ Session::get('success') }}
               </b-notification>
           </section>
       </template>
   </div>
@endif

@if(count($errors)>0)
    <div id="app">
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

    </div>
@endif