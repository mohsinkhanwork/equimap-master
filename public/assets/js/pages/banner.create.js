"use strict";

jQuery(function($){
    var NFT_Banner_Add = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;
        var editor;

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
                            var element = $("input[name='" + key + "']");
                            element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                        });

                        submitButton.attr('data-kt-indicator', 'off');
                        submitButton.removeAttr('disabled');
                    }
                });
            })
        }

        var handleType      = function(){
            $('#type').on('change', function(e){
                e.preventDefault();

                $('.link-type').each(function(){
                    $(this).addClass('d-none');
                });
console.log('#link-' + $(this).val() + '-container');
                $('#link-' + $(this).val() + '-container').removeClass('d-none');

            })
        }

        var handleTags      = function(){
            new Tagify( $('#params')[0] );
        }

        return {
            init: function () {
                form        = $("#em_create_banner");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_banner");

                handleType();
                handleTags();
                handleSubmit();
            }
        }
    }();

    NFT_Banner_Add.init();
});
