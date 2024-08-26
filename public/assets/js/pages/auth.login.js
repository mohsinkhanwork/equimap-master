"use strict";

jQuery(document).ready(function($){
    var NFT_Auth_Login     = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;
        var validator;

        var handleSubmit    = function(e){
            submitButton.on('click', function(e){
                e.preventDefault();

                // Hide previous errors, show loading and disable button
                form.find('.error').remove();
                submitButton.attr('data-kt-indicator', 'on');
                submitButton.attr('disabled', 'disabled');

                // Submit form
                handleForm();
            });
        }

        var handleForm      = function(){
            axios.post( formUrl, new FormData( form[0] ) ).then(function( response ){
                var response = response;
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
                        didClose: function (){
                            window.location.replace( redirectUrl );
                        }
                    });
                }
                else if ( response.data.status == 302 && response.data.content.redirect_url !== null ){
                    location.href = response.data.content.redirect_url;
                }
                else {
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

        var handleCountryDropdown   = function(){
            var optionFormat = function(item) {
                if ( !item.id ) {
                    return item.text;
                }

                var span = document.createElement('span');
                var imgUrl = item.element.getAttribute('data-kt-select2-country');
                var template = '';

                //template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
                template += item.text;

                span.innerHTML = template;

                return $(span);
            }


            $('#country').select2({
                templateSelection: optionFormat,
                templateResult: optionFormat
            });
        }

        var handleCountrySelection  = function(){
            $('#country').on('change', function(){
                let selected    = $(this).find('option:selected').data('dialing-code');
                $('#dialing_code').val( '+' + selected );
            })
        }

        return {
            init: function () {
                form        = $('#em_login_user');
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_login_user");
                submitButton.disable = true;

                handleCountryDropdown();
                handleCountrySelection();
                handleSubmit();
            }
        }
    }();

    NFT_Auth_Login.init();
});
