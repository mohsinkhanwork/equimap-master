"use strict";

jQuery(function($){
    var NFT_Trip_Add = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;

        var maskInputs      = function(){
            Inputmask({
                alias: 'numeric',
                allowMinus: false,
                step: 5,
                max: 1000000,
                unmaskAsNumber: true,
                rightAlign: false,
            }).mask('#price');

            Inputmask({
                alias: 'numeric',
                allowMinus: false,
                min: 1,
                max: 500,
                step: 1,
                rightAlign: false,
            }).mask('#capacity');

            Inputmask({
                alias: 'numeric',
                allowMinus: false,
                min: 0,
                step: 1,
                rightAlign: false,
            }).mask('#sort');
        }

        var handleSubmit    = function(e){
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
                            if( key == 'start_time' || key == 'end_time' ){
                                searchKey = "[name='dates']";
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

        var handleDates     = function(){
            $("#dates").daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                },
                timePicker: false,
                showDropdowns: true,
                drops: 'auto',
                autoApply: true,
                linkedCalendars: true,
                startDate: $("#start_date").val(),
                endDate: $("#end_date").val(),
                minDate: moment().startOf("day").add(3, "day"),
                maxDate: moment().startOf("day").add(368, "day")
            }, function(start, end, label) {
                $("#start_date").val( start.format('YYYY-MM-DD') );
                $("#end_date").val( end.format('YYYY-MM-DD') );
            });
        }

        var handleFileUpload    = new Dropzone(".dropzone", {
            url: $("#em_create_trip").attr('action'),
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

        var handleImageUpdate   = function(){
            $('.image-cover-button').on('click', function(e){
                e.preventDefault();
                var imageContainer = $(this);
                axios.post( $(this).attr('href') ).then( function( response ){
                    if( response && response.data.status == 200 ){
                        imageContainer.fadeOut();
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

        return {
            init: function () {
                form        = $("#em_create_trip");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_trip");

                maskInputs();
                handleDates();
                handleImageDelete();
                handleImageUpdate();
                handleSubmit();
            }
        }
    }();

    NFT_Trip_Add.init();
});
