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

// Event handlers
$('#main').on('click','#btn_term_table_display', toggleMerTerms)
