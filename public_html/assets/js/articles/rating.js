//click in star rating

$('.star_rating').on('click',function(){

    
    var user = $('#data-user').data('user');
    var article_id = $('.article_id').html();
    console.log('article_id: '+article_id);
    console.log(user);

    var id = $(this).attr('id');
    var valor = $(this).attr('value');
    console.log(id);
    console.log(valor);

    var star_color = $(this).css('color');
    console.log(star_color);
    if(star_color != "rgb(255, 255, 0)"){
        $(this).css({'color':'rgb(255, 255, 0)'});
    }
    else{
        $(this).css({'color':'rgb(51, 51, 51)'})
    }

    



   
});