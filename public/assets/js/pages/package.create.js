"use strict";

jQuery(function($){
    var NFT_Package_Add = function(){
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
                max: 50,
                step: 1,
                rightAlign: false,
            }).mask('#quantity');

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
                            var element = $("label[for='"+key+"']").first();

                            element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                        });

                        submitButton.attr('data-kt-indicator', 'off');
                        submitButton.removeAttr('disabled');
                    }
                });
            })
        }

        var handlePackageType = function(e){
            $('#packageable_type').on('change', function(e){
                let redirectUrl = $(this).find(":selected").attr('data-redirect-url');
                window.location.replace( redirectUrl );
            })
        }

        return {
            init: function () {
                form        = $("#em_create_package");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_package");

                handlePackageType();
                maskInputs();
                handleSubmit();
            }
        }
    }();

    NFT_Package_Add.init();
});