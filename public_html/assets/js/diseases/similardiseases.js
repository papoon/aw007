$(document).ready(function(){


    // Replaces name for each similar disease
    if($('.sim_dis').length){

        $('.sim_dis').each(function(i, obj) {

            var id_disease = $(this).text();
            var disease_field = $(this);

            var base_url = window.location.protocol + '//' + window.location.hostname + '/' + window.location.pathname.split('/')[1]

            var url = base_url+ '/rest/diseases/'+id_disease;

            $.ajax({
                url:url,
                type: 'GET',
                contentType: "application/json",
                dataType: 'json'
            })
            .done(function(result){

                disease_field.text(result['name']);

            })

            .fail(function(jqXHR, textStatus) {
                console.log(jqXHR);
                console.log(textStatus);
            })
            .always(function(){
                console.log('complete similar diseases');
            });
      });
    }


});