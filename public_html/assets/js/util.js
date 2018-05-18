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

$(document).on('mousedown','#button_recalc_inv_indexes',function(){

  debugger;

  $.ajax({

    url: api().uri() +'home/recalculate/invertedIndexes/0',
    type: 'POST',
    contentType: "application/json",
    dataType: 'json'
  })
  .done(function(result){
    console.log('recalculating inverted indexes');
  })
  .fail(function(jqXHR, textStatus) {
    console.log(jqXHR);
    console.log(textStatus);
  })
  .always(function(){
    console.log('complete');
    setTimeout(function() {
      location.reload();
    },
  1200);

  });

});
