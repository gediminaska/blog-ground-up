@extends('layouts.app')

@section('content')
<div id="app3">
    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h4 class="title">My account details</h4>
            </div>
        </div>
        <hr class="m-t-0">
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label for="name" class="label">Name</label>
                    <pre>{{$user->name}}</pre>
                </div>
                <div class="field">
                    <label for="email" class="label">Email</label>
                    <pre>{{$user->email}}</pre>
                </div>

                <div class="field">
                    <label for="roles" class="label">Roles</label>
                    <ul>
                        {{$user->roles->count() == 0 ? 'This user has no roles': ''}}
                        @foreach($user->roles as $role)
                            <li>{{$role->display_name}} ({{$role->description}})</li>
                            <template>
                                <section>
                                    <b-collapse :open="false">
                                        <button class="button is-secondary" slot="trigger">Show permissions</button>
                                        <div class="notification">
                                            <div class="content">
                                                @foreach($role->permissions as $permission)
                                                    <p>{{ $permission->display_name }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </b-collapse>
                                </section>
                            </template>
                        @endforeach
                    </ul>
                </div>
                <div class="field">
                    <label for="permissions" class="label">Additional permissions</label>
                    <ul>
                        {{$user->permissions->count() == 0 ? 'This user has no additional permissions': ''}}
                        @foreach($user->permissions as $permission)
                            <li>{{$permission->display_name}} ({{$permission->description}})</li>
                        @endforeach
                    </ul>
                </div>
                <div class="field">
                    <label for="socialLinks" class="label">Social media links</label>
                        {{$user->socialLinks->count() == 0 ? 'This user has no social media links': ''}}
                    <form action={{ route('delete.link') }} method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}

                        @foreach($user->socialLinks as $socialLink)
                            <a href="http://{{ $socialLink->link }}">
                                <i class="{{ array_flip(\App\SocialLinks::socialMediaSites)[$socialLink->site_name] }} fa-2x m-r-10"></i>
                            </a>
                            <div v-if="deleteLinks" v-cloak style="display: inline-block; margin-left: -13px">
                                <button type="submit" name="link_id" value={{ $socialLink->id }}><i class="fas fa-times"></i></button>
                            </div>
                        @endforeach
                    </form>
                                    <b-switch v-model="deleteLinks" type="is-danger">Delete links
                                    </b-switch>
                </div>
                <div class="filed">
                    <label for="socialLinks" class="label">Add links</label>
                    <form action="{{ route('my.account.update') }}" method="POST">
                        {{ csrf_field() }}
                        <select name="socialSite" id="">
                            @foreach($socialMediaSites as $socialMediaSite)
                                <option value="{{ $socialMediaSite }}">{{ $socialMediaSite }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="socialLink" value="">
                        <button type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>

        var app3 = new Vue({
            el: '#app3',
            data: {
                deleteLinks: false,
            },
            methods: {
            }
        });

    </script>
@endsection