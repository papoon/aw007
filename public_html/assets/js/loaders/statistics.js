function loadStatisticsPage() {
  $('.sub_main').hide();
  loadTwigTemplate('templates/statistics/index.html', function(template) {
    var uri = api().uri() + 'statistics';

    $.ajax({
      type: "GET",
      url: uri,
      dataType: 'json',
    })
    .done(function(statistics) {
      var output = template.render({
        statistics: statistics
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
