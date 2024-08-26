"use strict";

jQuery(function($){
    var NFT_Course_Listing = function(){
        $(document).on('click', '.em_delete_course', function(e){
            e.preventDefault();

            // Get confirmation first
            Swal.fire({
                html: `This action is permanent and data will be deleted forever, all providers utilizing this facility will have it removed.`,
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
                    NFT_Facility_Delete( $(this).closest('form') );
                }
            })
        })
    };

    var NFT_Facility_Delete  = function( form ){
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

    NFT_Course_Listing();
});
