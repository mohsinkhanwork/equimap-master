"use strict";

jQuery(function($){
    var NFT_Provider_Add = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;

        // setup default location data
        var locationData    = $('#map').attr('data-location').split(',');
        var defaultLocation = { lat: parseFloat( locationData[0] ), lng: parseFloat( locationData[1] ) };

        var handleMap = function initAutocomplete(){
            const map = new google.maps.Map(document.getElementById("map"), {
                center          : defaultLocation,
                zoom            : 13,
                mapTypeId       : "roadmap",
                mapTypeControl  : false,
            });

            // Create the search box and link it to the UI element.
            const input     = document.getElementById("geo_loc_search");
            const searchBox = new google.maps.places.SearchBox( input );

            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds( map.getBounds() );
            });

            let markers     = [];

            // Set default market
            markers.push( new google.maps.Marker({
                position: defaultLocation,
                map,
                title: "Equimap",
            }));

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places    = searchBox.getPlaces();

                if( places.length == 0 ){
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap( null );
                });

                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if( place.geometry.viewport ){
                        bounds.union(place.geometry.viewport);
                    }
                    else{
                        bounds.extend(place.geometry.location);
                    }

                    // set data in hidden field
                    let lat     = place.geometry.location.lat().toFixed(8);
                    let lng     = place.geometry.location.lng().toFixed(8);
                    let geo_loc = lat + ',' + lng
                    $("#geo_loc").val( geo_loc );
                });

                map.fitBounds(bounds);
            });
        }

        var handleSubmit        = function(e){
            submitButton.click(function(e){
                e.preventDefault();

                /**
                 * @TODO: Run form validations here before submitting.
                 */

                // Hide previous errors, show loading and disable button
                form.find('.error').remove();
                submitButton.attr('data-kt-indicator', 'on');
                submitButton.attr('disabled', 'disabled');

                // Append image data to form
                var filesData   = handleFileUpload.getAcceptedFiles();
                var restData    = new FormData( form[0] );
                if( filesData ){
                    filesData.forEach( ( item ) => {
                        restData.append( 'gallery[]', item )
                    } );
                }

                // Submit form
                axios.post( formUrl, restData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                } ).then( function( response ){
                    if( response && response.data.status == 200 ){
                        Swal.fire({
                            text: response.data.message,
                            icon: "success",
                            buttonsStyling: false,
                            timer: 1000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            showCloseButton: true,
                            closeButtonHtml: '<i class="fs-2 fa-solid fa-xmark"></i>',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                            didClose: function() {
                                window.location.replace( redirectUrl );
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            text: response.data.message,
                            icon: "error",
                            buttonsStyling: false,
                            timer: 1000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            showCloseButton: true,
                            closeButtonHtml: '<i class="fs-2 fa-solid fa-xmark"></i>',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });

                        $.each( response.data.content, function( key, value ){
                            var searchKey   = "[name='" + key + "']";
                            if( key == 'geo_loc' || key == 'city' || key == 'country' ){
                                searchKey = "[name='geo_loc_search']";
                            }

                            if( key == 'facilities' ){
                                searchKey   = "[name='facilities[]']";
                            }

                            if( key == 'gallery' ){
                                searchKey   = ".dropzone";
                            }

                            var element     = $(searchKey);
                            element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                        });

                        submitButton.attr('data-kt-indicator', 'off');
                        submitButton.removeAttr('disabled');
                    }
                });
            })
        }

        var handleFileUpload    = new Dropzone(".dropzone", {
            url: $("#em_create_provider").attr('action'),
            paramName: "gallery",
            maxFiles: 10,
            maxFilesize: 5,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            autoProcessQueue: false
        });

        var handleImageDelete   = function(){
            $('.image-delete-button').on('click', function(e){
                e.preventDefault();
                var imageContainer = $(this);
                axios.post( $(this).attr('href') ).then( function( response ){
                    if( response && response.data.status == 200 ){
                        imageContainer.closest('.image-input').fadeOut();
                    }
                    else{
                        Swal.fire({
                            text: response.data.message,
                            icon: "error",
                            buttonsStyling: false,
                            showConfirmButton: true,
                            showCloseButton: true,
                            closeButtonHtml: '<i class="fs-2 fa-solid fa-xmark"></i>',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
        }

        var handleFeatured      = function(){
            $('#featured').on('change', function(e){
                e.preventDefault();

                let featured    = $(this);
                let featRanking = $('#featured_ranking').parent();

                if( featured.is(':checked') === true ){
                    featRanking.removeClass('d-none');
                }
                else{
                    featRanking.addClass('d-none');
                }
            });
        }

        return {
            init: function () {
                form        = $("#em_create_provider");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_provider");

                handleFeatured();
                handleMap();
                handleImageDelete();
                handleSubmit();
            }
        }
    }();

    NFT_Provider_Add.init();
});
