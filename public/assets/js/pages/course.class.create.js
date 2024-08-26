"use strict";

jQuery(function($){
    var NFT_Course_Delete_Class = function(){
        $(document).on('click', '.em_delete_class', function(e){
            e.preventDefault();

            // Get confirmation first
            Swal.fire({
                html: `This action is permanent and data will be deleted forever.`,
                icon: "question",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Yes, I am sure.",
                cancelButtonText: 'Nope, cancel it',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger'
                }
            }).then((result) => {
                if( result.isConfirmed ){
                    NFT_Class_Delete( $(this).attr('href') );
                }
            })
        })
    }

    var NFT_Class_Delete  = function( formUrl ){
        axios.delete( formUrl ).then( function( response ){
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
                        location.reload();
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
            }
        });
    }


    var NFT_Course_Add_Class = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;

        var maskInputs      = function(){
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

        return {
            init: function () {
                form        = $("#em_create_class");
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_create_class");

                maskInputs();
                handleSubmit();
            }
        }
    }();

    NFT_Course_Add_Class.init();
    NFT_Course_Delete_Class();
});
