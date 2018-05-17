
function loadStaticTemplatePage(template, onAfterLoad) {
  $('.sub_main').hide();
  loadTwigTemplate('templates/' + template + '/index.html', function(template) {
    var output = template.render({});

    $('.sub_main').html(output);
    if(onAfterLoad) {
      onAfterLoad();
    }
    $('.sub_main').show();
  });
}
