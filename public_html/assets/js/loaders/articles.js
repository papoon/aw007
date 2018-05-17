function loadArticlesPage() {
  $('.sub_main').hide();
  loadTwigTemplate('templates/articles/index.html', function(template) {
    var uri = api().uri() + 'articles/0';

    $.ajax({
      type: "GET",
      url: uri,
      dataType: 'json',
    })
    .done(function(articles) {
      var output = template.render({
        articles: articles
      });

      $('.sub_main').html(output);
    })
    .fail(function(jqXHR, textStatus) {
      console.error(jqXHR, textStatus);
    })
    .always(function(){
      $('.sub_main').show();
    });
  });
}
