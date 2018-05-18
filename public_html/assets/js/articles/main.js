var baseHref = document.getElementsByTagName('base')[0].href;

var toggleMerTerms = function() {
  $("#article_mer_terms").toggle();
}

/* ---------- CLICK EVENTS ---------- */
$(document).ready(function() {

  /* Button that displays mer terms table */
  $("#btn_term_table_display").click(toggleMerTerms);

  /* Button that displays articles */
  $('a.article_link').on('click',function(e) {
    e.preventDefault();

    var tokens = $(this).attr('href').split('/');
    var article_id = tokens[tokens.length - 1];
    $('.sub_main').hide();
    loadTwigTemplate('templates/articles/article.html', function(template) {
      loadArticleData(template, article_id);
    });

    return false;
  });
});

function loadArticleData(template, id){
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

    /* since the html was replaced, we need to register the event again */
    $("#btn_term_table_display").click(toggleMerTerms);

    refreshMERTermsAbstract(article, 'diseases/');
    refreshStarRatingArticle();

    //change url
    var url = "articles/" + id;
    history.pushState({id:url}, '', (url == '' ? ''+url : url));
  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function(){
    $('.sub_main').show();
  });
}
