@extends('layouts.base')

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.role_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createRole(`{{ __('view.create_role') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-roles" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-secondary text-muted">
                        <th class="text-center">
                            <small>#</small>
                        </th>
                        <th>
                            <small>Name</small>
                        </th>
                        <th class="text-center">
                            <small>Action</small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalRole" tabindex="-1" aria-labelledby="modalRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-role">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalRoleLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-save" onclick="closeModal('modalRole')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-cancel" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/role.js') }}"></script> 
@endpush