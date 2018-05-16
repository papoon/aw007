function refreshStarRatingArticle() {

  //check if element exists
  if($('#star-rating-article').length){

      console.log('ready');
      var user = $('#data-user').data('user');
      var id_article = $('.id_article').html();
      var user_id = user.id;

      var data = {"client_id":user_id};

      //api to save rating
      $.ajax({
          url: api().uri()+'feedback/rating/article/'+id_article,
          type: 'GET',
          data: data,
          contentType: "application/json",
          dataType: 'json'
      })
      .done(function(result){

        console.log('oi get');
        console.log(result);

        if(result.length > 0){
            var rating = result[0].rating;

            $('#star-rating-article').children('span.star_rating').each(function () {
                let rating_value = ($(this).attr('value'));
                if(rating_value == rating){
                    $(this).addClass('selected');
                }
            });
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
}


//get rating if exists
$(document).ready(function(){

  refreshStarRatingArticle();

});

//click in star rating

$('#main').on('click','#star-rating-article > .star_rating',function(){


    var user = $('#data-user').data('user');
    var article_id = $('.article_id').html();
    var id_article = $('.id_article').html();
    console.log('article_id: '+article_id);
    console.log('id_article: '+id_article);
    console.log('user.id: '+user.id);
    var user_id = user.id;

    var star = $(this);
    var valor = star.attr('value');
    //console.log(valor);

    var star_color = $(this).css('color');
    //console.log(star_color);

    var div_rating = star.parent();
    //console.log(rating);

    var selected = false;
    div_rating.children('span.star_rating').each(function () {
        var rating_class = ($(this).prop('class'));
        if(rating_class.indexOf('selected') !== -1){
            selected = true;
            $(this).removeClass('selected');
        }

    });

    console.log(selected);


    if(selected === true){
        var type = 'PUT';
    }
    else{
        var type = 'POST';
    }

    var data = {"client_id":user_id,"rating":valor};

    $.ajax({
        url: api().uri()+'feedback/rating/article/'+id_article,
        type: type,
        data: JSON.stringify(data),
        contentType: "application/json",
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

            star.addClass('selected');
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
