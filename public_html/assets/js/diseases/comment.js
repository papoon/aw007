//get rating if exists
$(document).ready(function(){

    //check if element exists
    if($('.disease_comments').length){

        console.log('ready comments');
        var user = $('#data-user').data('user');
        var id_disease = $('.id_disease').html();
        var user_id = user.id;

        var data = {"client_id":user_id};

        //api to save rating
        $.ajax({
            url: api().uri()+'feedback/comment/disease/'+id_disease,
            type: 'GET',
            data: data,
            contentType: "application/json",
            dataType: 'json'
        })
        .done(function(result){

            console.log('oi get');
            console.log(result);

            var comment_html = '';
            if(result.length > 0){

                for (let index = 0; index < result.length; index++) {
                    const comment_id = result[index].id;
                    const disease_id = result[index].disease_id;
                    const client_id = result[index].client_id;
                    const comment = result[index].comment;
                    const created_at = result[index].created_at;

                    console.log(comment);


                    $('.comment').html('<b>'+comment+'</b>');
                    $('.publicated_at').html('Publicated at :' + created_at);
                    comment_html += $('.disease_comments').html();

                }
                $('.disease_comments').html(comment_html);

            }



        })
        .fail(function(jqXHR, textStatus) {
            console.log(jqXHR);
            console.log(textStatus);
        })
        .always(function(){
            console.log('complete');
        });
    }

    

});

//click in star rating

$('#main').on('click','#feedback-submit-disease',function(){

    console.log('comment article');
    var user = $('#data-user').data('user');
    //var disease_id = $('.disease_id').html();
    var id_disease = $('.id_disease').html();
    //console.log('disease_id: '+disease_id);
    console.log('id_disease: '+id_disease);
    console.log('user.id: '+user.id);
    var user_id = user.id;

    var comment = $("#comment_disease_textarea").val();
    

    var data = {"client_id":user_id,"comment":comment};

    $.ajax({
        url: api().uri()+'feedback/comment/disease/'+id_disease,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        console.log(result);

        if(result){

            var html = $('.disease_comment').last().clone();

            $(html).find('.comment').html('<b>'+comment+'</b>');
            $(html).find('.publicated_at').html('Publicated at :' + new Date().toLocaleString());
            //var comment_html = $(html).html();

            $('.disease_comments').prepend(html);
            //limpar textarea
            $("#comment_disease_textarea").val('');

            $('.comment_feedback_response').html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Comment published with <b>success!</b>.</div>');
        }

    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
        console.log(textStatus);
    })
    .always(function(){
        console.log('complete');
    });



   
});