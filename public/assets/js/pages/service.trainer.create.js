"use strict";

jQuery(function($){
    var NFT_Service_Add_Trainer = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;

        var handleCheckAll  = function(){
            $('#select_all').on('click', function(e){
                if( this.checked ){
                    $('.check-input:not(:disabled)').prop('checked','checked');
                }
                else{
                    $('.check-input:not(:disabled)').prop('checked','');
                }
            });
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
                            var key     = key.replace('.', '-');
                            var element = $("."+key).first();
                            element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                        });

                        submitButton.attr('data-kt-indicator', 'off');
                        submitButton.removeAttr('disabled');
                    }
                });
            })
        }

        return {
            init: function () {
                form        = $("#em_create_trainers");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_trainers");

                handleCheckAll();
                handleSubmit();
            }
        }
    }();

    NFT_Service_Add_Trainer.init();
});