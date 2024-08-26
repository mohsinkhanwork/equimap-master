"use strict";

jQuery(function($){
    var NFT_Service_Add = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;

        var maskInputs      = function(){
            Inputmask({
                alias: 'numeric',
                allowMinus: false,
                min: 0,
                max: 10000,
                rightAlign: false,
            }).mask('.price-selection');
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

                // Submit form
                axios.post( formUrl, new FormData( form[0] ) ).then( function( response ){
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
                            var key     = key.replaceAll('.', '-');
                            var element = $("."+key).first();
                            element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                        });

                        submitButton.attr('data-kt-indicator', 'off');
                        submitButton.removeAttr('disabled');
                    }
                });
            })
        }

        var handleDayActive = function(){
            $('.day-active').change( function(){
                var hours_container     = $(this).closest('.day-container').find('.day-hours-container');
                var repeater_container  = $(this).closest('.day-container').find('.day-hours-repeat');
                if( $(this).is(":checked") ){
                    hours_container.removeClass('d-none');
                    repeater_container.removeClass('d-none');
                }
                else{
                    hours_container.addClass('d-none');
                    repeater_container.addClass('d-none');
                }
            })
        }

        var handleTimeSelector  = function(){
            $('.hours-selection').on('focus',function(e){
                new tempusDominus.TempusDominus( this, {
                    stepping: 15,
                    display: {
                        viewMode: "clock",
                        components: {
                            decades: false,
                            year: false,
                            month: false,
                            date: false,
                            hours: true,
                            minutes: true,
                            seconds: false,
                        }
                    },
                    localization: {
                        hourCycle: "h23",
                        format: "LTS",
                        dateFormats: {
                            "LTS" : "HH:mm"
                        }
                    }
                });
            });
        }

        return {
            init: function () {
                form        = $("#em_create_schedule");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_schedule");

                maskInputs();
                handleSubmit();
                handleDayActive();
                handleTimeSelector();
            }
        }
    }();

    NFT_Service_Add.init();
});
