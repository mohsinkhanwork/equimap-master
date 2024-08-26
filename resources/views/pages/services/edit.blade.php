@section('title', 'Services')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Card widget-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                @cannot('approve service')
                    <!--begin::Alert-->
                    @if( $service->approved == 1 && $service->active == 1 )
                        <div class="mb-7 alert alert-dismissible bg-light-success d-flex align-items-center p-5">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-success-emphasis me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success-emphasis">Service is approved.</h4>
                                <span>This service is approved and available for booking, making any changes to the service might put it back in moderation and will not be available for booking until approved.</span>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                    @else
                        @if( $service->active == 1 )
                            <div class="mb-7 alert alert-dismissible bg-light-danger d-flex align-items-center p-5">
                                <i class="fas fa-solid fa-circle-exclamation fs-2hx text-danger-emphasis me-4"></i>
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger-emphasis">Service is pending approval.</h4>
                                    <span>This service is not approved and currently not available for booking.</span>
                                </div>
                                <!--end::Wrapper-->
                            </div>

                            <div class="mb-7 alert alert-dismissible bg-light-warning d-flex align-items-center p-5">
                                <i class="fas fa-solid fa-clipboard fs-2hx text-warning-emphasis me-4"></i>
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-warning-emphasis">Approval Notes</h4>
                                    <span>{{ $service->notes != null ? $service->notes : config('general.service.default_notes') }}</span>
                                </div>
                                <!--end::Wrapper-->
                            </div>
                        @endif
                    @endif
                    <!--end::Alert-->
                @endcannot

                <!--begin::Form-->
                <form autocomplete="off" data-redirect-url="{{ route('acp.services.index') }}" id="em_create_service" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('acp.services.update', [ 'service_id' => $service->id ] ) }}" method="post">
                    @can('approve service')
                        <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                            <input {!! $service->approved == 1 ? 'checked="checked"' : '' !!} name="approved" class="form-check-input" type="checkbox" value="1" id="approved"/>
                            <label class="form-check-label" for="approved">Approved ?</label>
                        </div>

                        <div class="form-floating mb-7">
                            <textarea data-kt-autosize="true" id="notes" name="notes" rows="3" class="form-control form-control-solid" placeholder="Approval Notes">{{ old('notes', $service->notes ) }}</textarea>
                            <label for="notes" class="form-label">Approval Notes</label>
                        </div>
                    @endcan

                    <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                        <input {!! $service->active == 1 ? 'checked="checked"' : '' !!} name="active" class="form-check-input" type="checkbox" value="1" id="active"/>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="sort" name="sort" class="form-control form-control-solid" placeholder="Sort" value="{{ old('sort', $service->sort ) }}" />
                        <label for="sort" class="form-label">Sort</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="provider_id" class="form-select form-select-solid" id="provider_id" aria-label="Provider" data-control="select2" data-close-on-select="true" data-placeholder="Select provider" data-allow-clear="true">
                            <option></option>
                            @foreach( $providers as $provider )
                                <option {!! $provider->id == $service->provider_id ? 'selected="selected"' : ''  !!} value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        <label for="provider_id" class="required form-label">Provider</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="category_id" class="form-select form-select-solid" id="category_id" aria-label="Category" data-control="select2" data-close-on-select="true" data-placeholder="Select category" data-allow-clear="true">
                            <option></option>
                            @foreach( $categories as $category )
                                <option {!! $category->id == $service->category_id ? 'selected="selected"' : ''  !!} value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="category_id" class="required form-label">Category</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Service Name" value="{{ old('name', $service->name ) }}" />
                        <label for="name" class="required form-label">Service Name</label>
                    </div>
                    <div class="form-floating mb-7">
                        <textarea data-kt-autosize="true" id="description" name="description" rows="3" class="form-control form-control-solid" placeholder="Description">{{ old('description', $service->description ) }}</textarea>
                        <label for="description" class="required form-label">Description</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="price" name="price" class="form-control form-control-solid" placeholder="Service Price" value="{{ old('price', $service->price ) }}" />
                        <label for="price" class="required form-label">Service Price</label>
                    </div>
                    <div class="form-floating mb-7">
                        <select name="unit" class="form-select form-select-solid" id="unit" aria-label="Scheduling Unit" data-control="select2" data-close-on-select="true" data-placeholder="Select level" data-allow-clear="true">
                            <option></option>
                            @foreach( $units as $unit => $title )
                                <option {!! $unit == $service->unit ? 'selected="selected"' : ''  !!} value="{{ $unit }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        <label for="unit" class="required form-label">Scheduling Unit</label>
                    </div>
                    <div class="form-floating mb-7">
                        <input type="text" id="capacity" name="capacity" class="form-control form-control-solid" placeholder="Service Capacity" value="{{ old('capacity', $service->capacity ) }}" />
                        <label for="capacity" class="required form-label">Service Capacity</label>
                    </div>

                    <button type="submit" id="submit_em_create_service" class="btn btn-primary">
                        @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Service' ])
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
