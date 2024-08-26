"use strict";

jQuery(function($){
    var NFT_User_Listing = function(){
        $(document).on('click', '.em_verify_user', function(e){
            e.preventDefault();

            // Get confirmation first
            Swal.fire({
                html: `Manually verifying users is not a recommended way and users should use available verification methods, use caution when verifying manually.`,
                icon: "question",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "I understand the risks, please continue",
                cancelButtonText: 'Nope, cancel it',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger'
                }
            }).then((result) => {
                if( result.isConfirmed ){
                    NFT_User_Verify( $(this).closest('form') );
                }
            })
        });

        $(document).on('click', '.em_delete_user', function(e){
            e.preventDefault();

            // Get confirmation first
            Swal.fire({
                html: `Removing users will mutate them into anonymous users while keeping their existing bookings and relevant data available, this process is non-recoverable.`,
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
                    NFT_User_Delete( $(this).closest('form') );
                }
            })
        });
    };

    var NFT_User_Verify  = function( form ){
        var formUrl = form.attr('action');

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

    var NFT_User_Delete  = function( form ){
        var formUrl = form.attr('action');

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

    NFT_User_Listing();
});
