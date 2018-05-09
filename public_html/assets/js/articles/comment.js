
//get rating if exists
$(document).ready(function(){

    //check if element exists
    if($('.article_comments').length){
        
        console.log('ready comments');
        var user = $('#data-user').data('user');
        var id_article = $('.id_article').html();
        var user_id = user.id;

        var data = {"client_id":user_id};

        //api to save rating
        $.ajax({
            url: api().uri()+'feedback/comment/article/'+id_article,
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
                    const article_id = result[index].article_id;
                    const client_id = result[index].client_id;
                    const comment = result[index].comment;
                    const created_at = result[index].created_at;

                    console.log(comment);


                    $('.comment').html('<b>'+comment+'</b>');
                    $('.publicated_at').html('Publicated at :' + created_at);
                    comment_html += $('.article_comments').html();
                    
                }
                $('.article_comments').html(comment_html);

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

$('#main').on('click','#feedback-submit',function(){

    console.log('comment article');
    var user = $('#data-user').data('user');
    var article_id = $('.article_id').html();
    var id_article = $('.id_article').html();
    console.log('article_id: '+article_id);
    console.log('id_article: '+id_article);
    console.log('user.id: '+user.id);
    var user_id = user.id;

    var comment = $("#comment_article_textarea").val();
    

    var data = {"client_id":user_id,"comment":comment};

    $.ajax({
        url: api().uri()+'feedback/comment/article/'+id_article,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        console.log(result);

        if(result){

            var html = $('.article_comment').get(0);

            $(html).find('.comment').html('<b>'+comment+'</b>');
            $(html).find('.publicated_at').html('Publicated at :' + new Date().toLocaleString());
            var comment_html = $(html).html();

            $('.article_comments').append(comment_html);
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