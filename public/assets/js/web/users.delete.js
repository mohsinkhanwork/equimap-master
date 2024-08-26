"use strict";

jQuery(document).ready(function($){
    var NFT_User_Delete     = function(){
        var form;
        var submitButton;
        var formUrl;
        var redirectUrl;
        var validator;

        var handleSubmit    = function(e){
            submitButton.on('click', function(e){
                e.preventDefault();

                // Hide previous errors, show loading and disable button
                $('.alert').addClass('d-none');
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
                    submitButton.hide();
                    form.hide();

                    $('.alert span').text( response.data.message );
                    $('.alert')
                        .removeClass('d-none')
                        .removeClass('alert-warning')
                        .addClass('alert-success');
                    $('.alert h4')
                        .removeClass('text-warning-emphasis')
                        .addClass('text-success-emphasis');
                    $('.alert i')
                        .removeClass('text-warning-emphasis')
                        .addClass('text-success-emphasis');
                }
                else {
                    // response.data.message
                    $('.alert span').text( response.data.message );
                    $('.alert').removeClass('d-none');

                    $.each(response.data.content, function (key, value) {
                        var element = $("input[name='" + key + "']");
                        element.parent().after('<div class="d-flex flex-row error text-danger">' + value[0] + '</div>');
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
                form        = $('#em_user_delete');
                formUrl     = form.attr('action');
                redirectUrl = form.attr('data-redirect-url');
                submitButton= $("#submit_em_user_delete");
                submitButton.disable = true;

                handleCountryDropdown();
                handleCountrySelection();
                handleSubmit();
            }
        }
    }();

    NFT_User_Delete.init();
});
