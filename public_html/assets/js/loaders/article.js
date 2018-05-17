function loadArticlePage(id) {
  console.log('loadArticlePage', id);
  $('.sub_main').hide();
  loadTwigTemplate('templates/articles/article.html', function(template) {
    loadArticleData(template, id);
  });
}

function loadArticleData(template, id) {
  var uri = api().uri() + 'articles/' + id;

  $.ajax({
    type: "GET",
    url: uri+'?terms=true',
    dataType: 'json'
  })
  .done(function(article) {
    var output = template.render({
      article: article,
      articleMERTerms: article.terms
    })

    $('.sub_main').html(output);

    refreshMERTermsAbstract(article, 'diseases/');
    refreshStarRatingArticle();
  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function(){
    $('.sub_main').show();
  });
}

var toggleMerTerms = function() {
  $("#article_mer_terms").toggle();
}

function merTermLike() {
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
}

function merTermDislike(){
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
}

// Event handlers
$(document).on('click', '#btn_term_table_display', toggleMerTerms);
$(document).on('click', '#art_mer_like', merTermLike);
$(document).on('click', '#art_mer_dislike', merTermDislike);
