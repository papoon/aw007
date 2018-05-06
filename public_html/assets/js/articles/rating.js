//click in star rating

$('.star_rating').on('click',function(){

    
    var user = $('#data-user').data('user');
    var article_id = $('.article_id').html();
    var id_article = $('.id_article').html();
    console.log('article_id: '+article_id);
    console.log('id_article: '+id_article);
    console.log('user.id: '+user.id);
    var user_id = user.id;
    
    var star = $(this);
    var valor = star.attr('value');
    console.log(valor);

    

    var star_color = $(this).css('color');
    console.log(star_color);



    var data = {"client_id":user_id,"article_id":id_article,"rating":valor};


    //api to save rating
    $.ajax({
        url: api().uri()+'feedback',
        type: 'POST',
        data: data,
        contentType: "application/x-www-form-urlencoded",
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        console.log(result);

        if(result){

            

            if(star_color != "rgb(255, 255, 0)"){
                star.css({'color':'rgb(255, 255, 0)'});
            }
            else{
                star.css({'color':'rgb(51, 51, 51)'})
            }
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