"use strict";

jQuery(document).ready(function($){
    var NFT_Auth_Verify     = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;
        var formSubtitle;
        var tokenHash;

        var firebaseApp     = firebase.initializeApp( firebaseConfig );
        var firebaseAuth    = firebase.auth( firebaseApp );
        var recaptcha       = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                                'size': 'invisible',
                                'callback': ( response ) => {}
                            }, firebaseApp );

        var handleSubmit    = function(e){
            submitButton.on('click', function(e){
                e.preventDefault();

                // Hide previous errors, show loading and disable button
                form.find('.error').remove();
                submitButton.attr('data-kt-indicator', 'on');
                submitButton.attr('disabled', 'disabled');

                var code = $('.otp-field').map(function(){
                    return this.value;
                }).get().join('');

                window.confirmationResult.confirm( code ).then((result) => {
                    // User signed in successfully.
                    var user = result.user;
                    user.getIdToken().then( function( userToken ){
                        $("[name='token']").val( userToken );
                        handleForm();
                    });
                }).catch( (error) => {
                    Swal.fire({
                        text: error,
                        icon: "error",
                        buttonsStyling: false,
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        showCloseButton: true,
                        closeButtonHtml: '<i class="fs-2 fa-solid fa-xmark"></i>',
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });

                    submitButton.attr('data-kt-indicator', 'off');
                    submitButton.removeAttr('disabled');
                });
            })
        }

        var sendVerificationCode    = function(){
            let phoneNumber = $("[name='login']").val();
            firebaseAuth.signInWithPhoneNumber( phoneNumber, recaptcha )
                .then((confirmationResult) => {
                    // SMS sent. Prompt user to type the code from the message, then sign the
                    // user in with confirmationResult.confirm(code).
                    tokenHash                   = confirmationResult.verificationId;
                    window.confirmationResult   = confirmationResult;

                    submitButton.attr('data-kt-indicator', 'off');
                    submitButton.removeAttr('disabled');
                }).catch((error) => {
                    /*Swal.fire({
                        text: error,
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
                    });*/

                    // append error on screen
                    $('.alert span').text( error.message );
                    $('.alert').removeClass('d-none');

                    // if otp is blocked than disable buttons
                    if( error.code == 'auth/too-many-requests' ){
                        $('.otp-field').attr('disabled','disabled');
                    }
                    else{
                        submitButton.removeAttr('disabled');
                    }

                    submitButton.attr('data-kt-indicator', 'off');
            });
        }

        var handleForm      = function(){
            axios.post( formUrl, new FormData( form[0] ) ).then(function (response) {
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
                        didClose: function () {
                            window.location.replace(redirectUrl);
                        }
                    });
                } else {
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

                    $.each(response.data.content, function (key, value) {
                        var element = $("input[name='" + key + "']");
                        element.parent().append('<div class="error text-danger">' + value[0] + '</div>');
                    });

                    submitButton.attr('data-kt-indicator', 'off');
                    submitButton.removeAttr('disabled');
                }
            });
        }

        var handleType      = function() {
            $('.otp-field').on('keyup', function(e){
                if( $(this).val().length === 1 ){
                    $(this).next('.otp-field').focus();
                }
                else if( $(this).val().length === 0 ){
                    $(this).prev('.otp-field').focus();
                }

                if ($(this).next('.otp-field').length == 0 ){
                    submitButton.trigger('click');
                }
            })
        }

        return {
            init: function () {
                form        = $('#em_verify_user');
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_verify_user");
                submitButton.disable = true;

                handleType();
                handleSubmit();
                sendVerificationCode();
            }
        }
    }();

    NFT_Auth_Verify.init();
});
