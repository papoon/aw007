function loadDiseasesPage() {
  $('.sub_main').hide();
  loadTwigTemplate('templates/diseases/index.html', function(template) {
    var uri = api().uri() + 'diseases/0';

    $.ajax({
      type: "GET",
      url: uri,
      dataType: 'json',
    })
    .done(function(result) {
      var output = template.render({
        diseases: result
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
