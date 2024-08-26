@section('title', 'Packages')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        @include('pages.packages.cover')

        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
            @cannot('approve package')
                <!--begin::Alert-->
                    @if( $package->approved == 1 && $package->active == 1 )
                        <div class="mb-7 alert alert-dismissible bg-light-success d-flex align-items-center p-5">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-success-emphasis me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success-emphasis">Package is approved.</h4>
                                <span>This package is approved and available for booking, making any changes to the package might put it back in moderation and will not be available for booking until approved.</span>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                    @else
                        @if( $package->active == 1 )
                            <div class="mb-7 alert alert-dismissible bg-light-danger d-flex align-items-center p-5">
                                <i class="fas fa-solid fa-circle-exclamation fs-2hx text-danger-emphasis me-4"></i>
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger-emphasis">Package is pending approval.</h4>
                                    <span>This package is not approved and currently not available for booking.</span>
                                </div>
                                <!--end::Wrapper-->
                            </div>

                            <div class="mb-7 alert alert-dismissible bg-light-warning d-flex align-items-center p-5">
                                <i class="fas fa-solid fa-clipboard fs-2hx text-warning-emphasis me-4"></i>
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-warning-emphasis">Approval Notes</h4>
                                    <span>{{ $package->notes != null ? $package->notes : config('general.package.default_notes') }}</span>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                    @endif
                @endif
                <!--end::Alert-->
            @endcannot

                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.packages.index') }}" id="em_create_package" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.packages.update', ['package_id' => $package->id ]) }}" enctype="multipart/form-data" method="post">
                    @can('approve service')
                        <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                            <input {!! $package->approved == 1 ? 'checked="checked"' : '' !!} name="approved" class="form-check-input" type="checkbox" value="1" id="approved"/>
                            <label class="form-check-label" for="approved">Approved ?</label>
                        </div>

                        <div class="form-floating mb-7">
                            <textarea data-kt-autosize="true" id="notes" name="notes" rows="3" class="form-control form-control-solid" placeholder="Approval Notes">{{ old('notes', $package->notes ) }}</textarea>
                            <label for="notes" class="form-label">Approval Notes</label>
                        </div>
                    @endcan
                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input {!! $package->active == 1 ? 'checked="checked"' : '' !!} name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="{{ old('sort', $package->sort) }}" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Name" value="{{ old('name', $package->name) }}" />
                        <label for="name" class="required form-label">Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="price" name="price" class="form-control form-control-solid" placeholder="Price" value="{{ old('price', $package->price) }}" />
                        <label for="price" class="required form-label">Price</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="quantity" name="quantity" class="form-control form-control-solid" placeholder="Quantity" value="{{ old('quantity', $package->quantity) }}" />
                        <label for="quantity" class="required form-label">Quantity</label>
                    </div>
                    <button type="submit" id="submit_em_create_package" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Package' ])
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
