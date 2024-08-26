@section('title', '')
<x-default-layout>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        @include('pages.providers.cover')
        <!--begin::Card widget-->
        <div class="card card-flush mt-5 h-md-50 mb-5 mb-xl-10">
            <!--begin::Card body-->
            <div class="card-body d-flex flex-column justify-content-end p-5">
                @if( request()->get('tab') == 'services' )
                    @include('pages.providers.services')
                @elseif( request()->get('tab') == 'trips' )
                    @include('pages.providers.trips')
                @elseif( request()->get('tab') == 'horses' )
                    @include('pages.providers.horses')
                @elseif( request()->get('tab') == 'trainers' )
                    @include('pages.providers.trainers')
                @elseif( request()->get('tab') == 'courses' )
                    @include('pages.providers.courses')
                @else
                    <!--begin::Form-->
                    <form autocomplete="off" data-redirect-url="{{ route('acp.providers.index') }}" id="em_create_provider" class="form fv-plugins-bootstrap5 fv-plugins-framework" enctype="multipart/form-data" action="{{ route('acp.providers.update', ['provider_id' => $provider->id ]) }}" method="post">
                        <div class="form-floating mb-7">
                            <input type="text" id="em_provider_name" name="name" class="form-control form-control-solid" placeholder="Name" value="{{ old('name', $provider->name ) }}" />
                            <label for="em_provider_name" class="required form-label">Provider Name</label>
                        </div>
                        @can('edit provider featured')
                            <div class="mb-7 form-check form-switch form-check-custom form-check-solid">
                                <input {!! $provider->featured == 1 ? 'checked="checked"' : '' !!} name="featured" class="form-check-input" type="checkbox" id="featured" value="1" />
                                <label class="form-check-label" for="featured">Featured ?</label>
                            </div>
                            <div class="form-floating mb-7 {!! $provider->featured != 1 ? 'd-none' : '' !!}">
                                <input type="text" id="featured_ranking" name="featured_ranking" class="form-control form-control-solid" placeholder="Featured Ranking" value="{{ old('featured_ranking', $provider->featured_ranking ) }}" />
                                <label for="featured_ranking" class="form-label">Featured Ranking ?</label>
                            </div>
                        @endcan
                        <div class="form-floating mb-7">
                            <textarea data-kt-autosize="true" id="em_provider_address" name="address" rows="3" class="form-control form-control-solid" placeholder="Address">{{ old('address', $provider->address ) }}</textarea>
                            <label for="em_provider_address" class="required form-label">Address</label>
                        </div>
                        <div class="form-floating mb-7">
                            <textarea data-kt-autosize="true" id="em_provider_description" name="description" rows="3" class="form-control form-control-solid" placeholder="Description">{{ old('description', $provider->description ) }}</textarea>
                            <label for="em_provider_description" class="required form-label">Description</label>
                        </div>

                        <div class="form-floating mb-7">
                            <select name="facilities[]" class="form-select form-select-solid" data-control="select2" data-close-on-select="false" data-placeholder="Select available facilities" data-allow-clear="true" multiple="multiple">
                                <option></option>
                                @foreach( $facilities as $facility )
                                    <option {!! in_array( $facility->id, $provider->facilities ) ? 'selected="selected"' : '' !!} value="{{ $facility->id }}">{{ $facility->name }}</option>
                                @endforeach
                            </select>
                            <label for="facilities[]" class="required">Facilities</label>
                        </div>

                        <div class="d-flex flex-row card-rounded bg-light mb-7">
                            @if( isset( $provider->cover ) )
                                <div class="d-flex symbol m-5 bg-white p-3">
                                    <img src="{{ storage_url( $provider->cover->path ) }}" class="w-100px h-auto" alt="" />
                                </div>
                            @endif
                            <div class="d-flex form-floating">
                                <input type="file" id="cover" name="cover" class="form-control form-control-solid" placeholder="Icon" />
                                <label for="cover" class="form-label">Cover Image</label>
                            </div>
                        </div>

                        <!--begin::Dropzone-->
                        <div class="card-rounded bg-gray-100 p-3 mb-7">
                            <div class="dropzone bg-gray-100 border-0" id="em_dropzone">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <i class="fas fs-2 fa-solid fa-image"></i>

                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bold text-gray-900 mb-1">Gallery images</h3>
                                        <span class="fs-7 fw-semibold text-gray-400 dz-error-append">Drag or click to upload upto 10 files.</span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <div class="d-flex flex-row card-rounded mt-3 pt-3 overflow-x-scroll">
                                @if( isset( $provider->images ) )
                                    @foreach( $provider->images as $image )
                                        <div class="mx-3 image-input image-input-outline" style="background-image: url( {{ image('svg/avatars/blank.svg') }} )">
                                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $image->url }})"></div>

                                            <!--begin::Remove button-->
                                            <a href="{{ route('acp.providers.destroy_image', [ '_token' => csrf_token(), '_method' => 'DELETE', 'provider_id' => $provider->id, 'image_id' => $image->id ]) }}" class="image-delete-button btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-bs-toggle="tooltip" title="Delete image">
                                                <i class="ki-outline ki-cross fs-3"></i>
                                            </a>
                                            <!--end::Remove button-->
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <!--end::Dropzone-->

                        <div class="form-floating mb-7">
                            <input type="text" id="geo_loc_search" name="geo_loc_search" class="form-control form-control-solid" placeholder="Location" value="{{ old('location', $provider->address ) }}" />
                            <label for="geo_loc_search" class="required form-label">Location</label>
                        </div>
                        <div class="d-flex h-250px mb-7 z-index-2000" id="map" data-location="{{ $provider->geo_loc }}"></div>

                        <button type="submit" id="submit_em_create_provider" class="btn btn-primary">
                            @include('partials/general/_button-indicator', ['label' => '<i class="fs-2 fa-solid fa-plus"></i> Update Provider' ])
                        </button>

                        <input type="hidden" id="geo_loc" name="geo_loc" value="{{ old('geo_loc', $provider->geo_loc ) }}" />

                        @csrf
                        @method('PATCH')
                    </form>
                    <!--end::Form-->
                @endif
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget-->
    </div>
    <!--end::Row-->
</x-default-layout>
