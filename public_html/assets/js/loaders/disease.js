function loadDiseasePage(id) {
  $('.sub_main').hide();
  loadTwigTemplate('templates/diseases/disease.html', function(template) {
    loadDiseaseData(template, id);
  });
}

function loadDiseaseData(template, id) {
  var uri = api().uri() + 'diseases/' + id;

  // recebe o id da doença
  $.ajax({
    type: "GET",
    url: uri,
    dataType: 'json',
    data:{'metadata':'true'}
  })
  .done(function(disease){
    var output = template.render({
      data: {
        articlesDisease: disease.articles,
        disease: disease,
        photosDisease: disease.photos,
        similarDisease: disease.similarDiseases,
        tweetsDisease: disease.tweets,
      },
    })

    $('.sub_main').html(output);
    refreshStarRatingDisease();
  })
  .fail(function(jqXHR, textStatus) {
    console.log(jqXHR);
  })
  .always(function(){
    $('.sub_main').show();
  });
}

// Event handling for 'diseases' page
$(document).on('click','#button_open_article',function(){
  var raw_data = $(this)[0].href.split('/');

  var article_id = raw_data[raw_data.length-1];

  $.ajax({

    url: api().uri() +'feedback/implicit/'+article_id,
    type: 'POST',
    contentType: "application/json",
    dataType: 'json'
  })
  .done(function(result){

  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function(){
  });

});

$(document).on('click','#button_hide_photo',function(){
  var photo_id = $(this)[0].getAttribute('data-id');

  $.ajax({

    url: api().uri() +'photos/hide/'+photo_id,
    type: 'POST',
    contentType: "application/json",
    dataType: 'json'
  })
  .done(function(result){
    var tokens = location.href.split('/');
    var id = tokens[tokens.length - 1];
    loadDiseasePage(id);
  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function(){
  });

});

$(document).on('click','#button_reset_photos',function(){
  var disease_id = $(this)[0].getAttribute('data-id');

  $.ajax({

    url: api().uri() +'disease/photos/reset/'+disease_id,
    type: 'POST',
    contentType: "application/json",
    dataType: 'json'


  })
  .done(function(result){
    var tokens = location.href.split('/');
    var id = tokens[tokens.length - 1];
    loadDiseasePage(id);
  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function() {
  });
});
