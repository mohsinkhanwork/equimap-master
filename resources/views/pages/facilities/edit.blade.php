@section('title', 'Facilities')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.facilities.index') }}" id="em_create_facility" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.facilities.update', [ 'facility_id' => $facility->id ]) }}" method="post">
                    <div class="form-floating mb-7">
                        <input type="text" id="em_facility_name" name="name" class="form-control form-control-solid" placeholder="Name" value="{{ old('name', $facility->name ) }}" />
                        <label for="name" class="required form-label">Facility Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="number" id="em_facility_sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="{{ old('name', $facility->sort ) }}" />
                        <label for="sort" class="required form-label">Sorting Order</label>
                    </div>
                    <div class="d-flex flex-row card-rounded bg-light mb-7">
                        @if( isset( $facility->icon ) )
                            <div class="d-flex symbol m-5 bg-white p-3">
                                <img src="{{ $facility->icon['url'] }}" class="w-50px h-auto" alt="" />
                            </div>
                        @endif
                        <div class="d-flex form-floating">
                            <input type="file" id="icon" name="icon" class="form-control form-control-solid" placeholder="Icon" />
                            <label for="icon" class="form-label">Icon</label>
                        </div>
                    </div>

                    <button type="submit" id="submit_em_create_facility" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Facility' ])
                    </button>

                    @csrf
                    @method('PATCH')
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
