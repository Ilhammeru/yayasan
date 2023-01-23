@extends('layouts.base')

@push('styles')
    <style>
        .empty-data-img-home {
            width: 200px;
            height: auto;
        }
    </style>
@endpush

@section('content')
    <div class="block full">
        <div class="block-title">
            <h2>{{ $data->name }}</h2>
            <div style="padding: 0 15px;">
                <a class="btn btn-primary btn-sm" href="{{ route('intitutions.index') }}">{{ __('view.back') }}</a>
            </div>
        </div>

        @if (count($data->classes) > 0)
            <div class="block-header">
                @include('master.intitutions.components.detail_institution_header')
            </div>

            <div class="block-body">
                <div class="row">
                    <div class="col-md-2">
                        {{-- filter --}}
                        @include('master.intitutions.components.detail_institution_filter', ['classes' => $classes, 'institution_id' => $data->id])
                    </div>
                    <div class="col-md-10">
                        <div id="target-detail-data"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <img src="{{ asset('assets/img/empty_data.jpg') }}" class="empty-data-img-home" alt="">
                <p style="margin: 0;">@lang("view.class_doesn't_exist")</p>
                <p>@lang('view.click_to_add_level', ['ins_id' => $data->id])</p>
            </div>
        @endif

    </div>

    {{-- default param --}}
    <form action="" id="default-param">
        <input type="hidden" name="institution_id" id="df_institution_id" value="{{ $default_param['institution_id'] }}">
        <input type="hidden" name="class_id" id="df_class_id" value="{{ $default_param['class_id'] }}">
        <input type="hidden" name="level_id" id="df_level_id" value="{{ $default_param['level_id'] }}">
    </form>

    {{-- modal choose homeroom teacher --}}
    <div class="modal fade" id="modalChooseHomeroom" tabindex="-1" aria-labelledby="modalChooseHomeroomLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalChooseHomeroomLabel">@lang('view.select_homeroom')</h1>
                </div>
                <div class="modal-body">
                    <form action="" id="form-select-homeroom">
                        <div id="target-form"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalChooseHomeroom')">@lang('view.cancel_delete')</button>
                    <button type="button" class="btn btn-primary" onclick="saveHomeroom()">@lang('view.save')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal update --}}
    <div class="modal fade" id="modalIntitution" tabindex="-1" aria-labelledby="modalIntitutionLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-intitution">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalIntitutionLabel"></h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalIntitution')">Close</button>
                        <button type="button" class="btn btn-primary" id="btn-save" onclick="saveItem()">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/intitution.js') }}"></script>
    <script>
        playCountUp("{{ $all_students }}", 'count_all_students', 2);
        playCountUp("{{ $female }}", 'count_female', 2);
        playCountUp("{{ $male }}", 'count_male', 2);
        playCountUp("{{ $all_paid_income }}", 'count_all_paid_income', 3);

        function saveItem() {
            let form = $('#form-intitution');
            let data = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            let classWrapper = $('.class-wrapper');
            for (let a = 0; a < classWrapper.length; a++) {
                let classInput = $(`.class-input-${a}`).val();
                let levelInput = $(`.level-input-${a}`).val();
                if (levelInput != '' && classInput == '') {
                    return showNotif(true, 'Class cannot be empty if level of class is declare');
                }
            }

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    loadingPage(true, i18n.view.saving);
                },
                success: function(res) {
                    console.log('res',res);
                    loadingPage(false);
                    showNotif(false, res.message);
                    closeModal('modalIntitution');
                    window.location.href = base_url + '/intitutions/' + res.data.param.institution_id;
                },
                error: function(err) {
                    loadingPage(false);
                    showNotif(true, err);
                }
            })
        }
    </script>
@endpush