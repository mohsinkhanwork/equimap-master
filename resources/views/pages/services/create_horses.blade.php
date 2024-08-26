@section('title', 'Services')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.services.index') }}" id="em_create_horses" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.services.store_horses', ['service_id' => $service->id ] ) }}" method="post">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                            <tr class="fw-bold fs-6 text-gray-800">
                                <th class="form-check form-check-custom form-check-solid form-check-sm">
                                    <input class="form-check-input" type="checkbox" id="select_all" />
                                </th>
                                <th>Active</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Level</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach( $horses as $horse )
                                    <tr>
                                        <td class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input {{ in_array( $horse->id, $service_horses ) ? 'checked="checked' : '' }} {{ $horse->active == 0 ? 'disabled="disabled"' : '' }} class="check-input form-check-solid form-check-input" type="checkbox" name="horse[]" value="{{ $horse->id }}" />
                                        </td>
                                        <td>{{ $horse->active == 1 ? 'Yes' : 'No' }}</td>
                                        <td>{{ $horse->name }}</td>
                                        <td>{{ $horse->gender }}</td>
                                        <td>{{ $horse->level }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" id="submit_em_create_horses" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Add Horses' ])
                    </button>
                    @csrf
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
