
function refreshMERTermsAbstract(article, uri_disease) {

  /* Function that replaces MER Terms with links of specific disease

      It needs to replace text with links on both title and abstract at the same time since MER function calculates positions on title and abstract as one text only. This function then separates title from abstract and replaces the content on html page on the specific container

      */

  var abstract = article['abstract'];
  var terms = article.terms;
  var title = article.title;
  var start = 0;
  var end = 0;
  var clean_text = '';
  var raw_text = title + abstract;
  var new_title_length = title.length;


  // sorts terms by ascendent position
  terms.sort(function(a, b){
      return a.pos_start - b.pos_start;
  });

  for(var i = 0; i < terms.length; i++) {

      var currentTerm = terms[i];
      var term = currentTerm['term'];

      var isSubstring = false;
      for(var j = 0; j < terms.length; j++) {
        if (j != i) {
          if (terms[j]['term'].indexOf(term) >= 0 && terms[j]['term'].length != term.length) {
            isSubstring = true;
          }
        }
      }

      if(!isSubstring) {

        //debugger;

        var disease_id = currentTerm['disease_id'];
        var do_id = currentTerm['do_id'];
        var pos_start = currentTerm['pos_start'];
        var pos_end = currentTerm['pos_end'];

        if(disease_id == null) {
          link = '<strong>' + term + '</strong>';
        }
        else {
          link = '<strong><a href='+ uri_disease + disease_id + '>' + term + '</a></strong>';
        }

        end = pos_start;
        tmp = raw_text.substring(start,end);

        clean_text += tmp + link;

        //console.log(clean_text);

        // determines size of new title so it can divide the content after
        if (end < title.length) {
            new_title_length += link.length - term.length;
        }

        //console.log(new_title_length);

        start = pos_end;
      }

  }

  // Adds last part of string
  if (start != raw_text.length){
      clean_text += raw_text.substring(start,raw_text.length);
  }

  // building new content
  var new_title = clean_text.substring(0, new_title_length);
  var new_abstract = clean_text.substring(new_title_length, clean_text.length );


  //replacing content of view
  $('.article_title').html(new_title);
  $('.article_abstract').html(new_abstract);

}

$(document).ready(function(){

    if($('.article_abstract').length){

        url = window.location.href.split('/');

        // url of API getArticleWithTerms
        uri_rest = url[0]+'//'+url[2]+'/'+url[3]+'/rest/'+url[4]+'/'+url[5];

        //url of article page - needs id of articles
        uri_article = url[0]+'//'+url[2]+'/'+url[3]+'/'+url[4]+'/';

        //url of diseases page - needs id of disease
        uri_disease = url[0]+'//'+url[2]+'/'+url[3]+'/'+"diseases"+'/';

        $.ajax({

            type: "GET",
            url: uri_rest,
            dataType: 'json',
            data:{'terms':'true'}

        })

        .done(function(result){

          refreshMERTermsAbstract(result, uri_disease);

        })
        .fail(function(jqXHR, textStatus) {
            console.log(jqXHR);
        })
        .always(function(){
            console.log('complete');
        });

        return false;
    }
});


$('#main').on('click','#art_mer_like',function(){

    var raw_data = $(this).val().split(',');
    var span_to_update = $(this).next();

    var article_id = raw_data[0];
    var term = raw_data[1];
    var pos_start = raw_data[2];

    var data = {"article_id": article_id, "pos_start": pos_start, "term" : term, 'type' : 'like'}

    var id_article = $('.id_article').html();

  $.ajax({

        url: api().uri() +'feedback/rating/diseaseinarticle/'+id_article,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json",

    })
    .done(function(result){

        var value = parseInt(span_to_update.html());

        // updating span with nr of likes
        span_to_update.html(value + 1);

    /* ------- SUCCESS / ERROR ALERT ----- */

    $('#alert-success').html(' <strong>Success!</strong> Feedback added.');
    $('#alert-success').show();
      setTimeout(function(){
        $('#alert-success').hide();
      }, 1500);



    })
    /* - end success */
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
        console.log(textStatus);

           $('#alert-fail').html(' <strong>Ups!</strong> Feedback not saved.');
           $('#alert-fail').show();
              setTimeout(function(){
                $('#alert-fail').hide();
              }, 1500);


    })
    .always(function(){
        console.log('complete');
    });


});

$('#main').on('click','#art_mer_dislike',function(){

    var raw_data = $(this).val().split(',');
    var span_to_update = $(this).next();

    var article_id = raw_data[0];
    var term = raw_data[1];
    var pos_start = raw_data[2];

    var data = {"article_id": article_id, "pos_start": pos_start, "term" : term, 'type' : 'dislike'}

    var id_article = $('.id_article').html();

  $.ajax({

        url: api().uri() +'feedback/rating/diseaseinarticle/'+id_article,
        type: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: 'json'


    })
    .done(function(result){

        var value = parseInt(span_to_update.html());

        // updating span with nr of likes
        span_to_update.html(value + 1);

    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
        console.log(textStatus);
    })
    .always(function(){
        console.log('complete');
    });


});
