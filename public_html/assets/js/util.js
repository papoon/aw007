function cleanTemplate(html) {
  return html && html.replace(/\{\%.*\%\}/g,'');
}

function getModelValueForPath(model, path) {
  path = path.trim();
  var parts = path.split('.');
  var value = model;
  for (var i = 0; i < parts.length; i++) {
    var part = parts[i];
    value = value[part];
    if (!value) {
      // value not found so we return the path
      console.error('Path [' + path + '] not found in', model);
      return path;
    }
  }

  return value;
}

function applyTemplate(template, model) {
  var replacer = function(match, fieldPath) {
    return getModelValueForPath(model, fieldPath);
  };

  return template.replace(/\{\{([^\}]+)\}\}/g, replacer);
}
