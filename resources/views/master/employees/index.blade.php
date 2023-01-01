@extends('layouts.base')

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ __('view.position_list') }}</h2>
            <div style="padding: 0 15px;">
                <button class="btn btn-primary btn-sm" type="button" onclick="createEmployee(`{{ __('view.create_employee') }}`)">{{ __('view.create') }}</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="table-employee" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr class="text-secondary text-muted">
                        <th class="text-center">
                            <small>#</small>
                        </th>
                        <th>
                            <small>{{ __('view.name') }}</small>
                        </th>
                        <th>
                            <small>{{ __("view.position") }}</small>
                        </th>
                        <th>
                            <small>{{ __("view.intitutions") }}</small>
                        </th>
                        <th>
                            <small>NIP</small>
                        </th>
                        <th>
                            <small>{{ __('view.phone') }}</small>
                        </th>
                        <th>
                            <small>{{ __('view.email') }}</small>
                        </th>
                        <th>
                            <small>{{ __('view.status') }}</small>
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
    <div class="modal fade" id="modalEmployee" tabindex="-1" aria-labelledby="modalEmployeeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="" id="form-employee" class="form-horizontal">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalEmployeeLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalEmployee')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/employee.js') }}"></script> 
@endpush