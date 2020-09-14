$('.notAvailable').on('click', function (){
    let url = $(this).attr('data-target');
    let id = $(this).attr('data-id');
    $('#toast').toast('show');
    $('.product-delete-'+id).remove()
    $.get(url, function (){
        toastr.options.newestOnTop = false;
        toastr.info('Bravo ! Le don est enregistré !')
    }).fail(function (){
        toastr.options.newestOnTop = false;
        toastr.error("Une erreur s'est produite lors de la suppression !")
    })

});