@extends('layouts.manage')

@section('content')

    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">Create new permission</h1>
            </div>
        </div>
        <hr class="m-t-0">
        <form action="{{route('permissions.store')}}" method="POST">
            {{csrf_field()}}
            <div class="block">
                <b-radio name="permission_type" native-value="basic" v-model="permissionType">Basic Permission</b-radio>
                <b-radio name="permission_type" native-value="crud" v-model="permissionType">CRUD Permissions</b-radio>
            </div>
            <div class="field" v-if="permissionType == 'basic'">
                <label for="display_name" class="label">Name (Display Name)</label>
                <p class="control">
                    <input type="text" class="input" id="display_name" name="display_name">
                </p>
            </div>

            <div class="field" v-if="permissionType == 'basic'">
                <label for="name" class="label">Slug</label>
                <p class="control">
                    <input type="text" class="input" id="name" name="name">
                </p>
            </div>

            <div class="field" v-if="permissionType == 'basic'">
                <label for="descritpion" class="label">Description</label>
                <p class="control">
                    <input type="text" class="input" id="descritpion" name="description" placeholder="Describe what this permission does">
                </p>
            </div>

            <div class="field" v-if="permissionType == 'crud'">
                <label for="resource" class="label">Resource</label>
                <p class="control">
                    <input type="text" class="input" id="resource" name="resource" v-model="resource" placeholder="Name of the resource">
                </p>
            </div>
            <div class="columns" v-if="permissionType == 'crud'">
                <div class="column is-one-quarter">
                    <div class="field">
                        <b-checkbox v-model="crudSelected" native-value="create">Create</b-checkbox>
                    </div>
                    <div class="field">
                        <b-checkbox v-model="crudSelected" native-value="read">Read</b-checkbox>
                    </div>
                    <div class="field">
                        <b-checkbox v-model="crudSelected" native-value="update">Update</b-checkbox>
                    </div>
                    <div class="field">
                        <b-checkbox v-model="crudSelected" native-value="delete">Delete</b-checkbox>
                    </div>
                    <input type="hidden" name="crud_selected" :value="crudSelected">
                </div>
                <div class="column">
                    <table class="table" v-if="resource.length >= 3">
                        <thead>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                        </thead>
                        <tbody>
                        <tr v-for="item in crudSelected">
                            <td v-text="crudName(item)"></td>
                            <td v-text="crudSlug(item)"></td>
                            <td v-text="crudDescription(item)"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button class="button is-success">Create Permission</button>
        </form>
    </div>

@endsection
