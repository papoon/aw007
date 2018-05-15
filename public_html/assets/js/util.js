var twigTemplateCache = {};

function loadTwigTemplate(url, callback) {
  var template = twigTemplateCache[url];

  if (template) {
    callback(template);
  } else {
    $.get(url, function(templateData) {
      var template = Twig.twig({
        id: url,
        data: templateData
      });

      twigTemplateCache[url] = template;

      callback(template);
    });
  }
}
