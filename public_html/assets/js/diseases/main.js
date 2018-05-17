var baseHref = document.getElementsByTagName('base')[0].href;

$('a.image_link_disease').on('click',function(e) {
  e.preventDefault();

  var tokens = $(this).attr('href').split('/');
  var disease_id = tokens[tokens.length - 1];
  $('.sub_main').hide();
  loadTwigTemplate('templates/diseases/disease.html', function(template) {
    loadDiseaseData(template, disease_id);
  });

  return false;
});

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

    //change url
    var url = "diseases/" + id;
    history.pushState({id:url}, '', (url == '' ? ''+url : url));
  })
  .fail(function(jqXHR, textStatus) {
    console.log(jqXHR);
  })
  .always(function(){
    $('.sub_main').show();
  });
}

$('#main').on('mousedown','#button_open_article',function(){
  var raw_data = $(this)[0].href.split('/');

  var article_id = raw_data[raw_data.length-1];

  $.ajax({

    url: api().uri() +'feedback/implicit/'+article_id,
    type: 'PUT',
    contentType: "application/json",
    dataType: 'json'


  })
  .done(function(result){


  })
  .fail(function(jqXHR, textStatus) {
    console.error(jqXHR, textStatus);
  })
  .always(function(){
    console.log('complete');
  });

});

$('#main').on('mousedown','#button_hide_photo',function(){

  //debugger;
  //console.log('xxx', $(this)[0].href);

  var photo_id = $(this)[0].getAttribute('data-id');

  $.ajax({

    url: api().uri() +'photos/hide/'+photo_id,
    type: 'PUT',
    contentType: "application/json",
    dataType: 'json'


  })
  .done(function(result){


  })
  .fail(function(jqXHR, textStatus) {
    console.log(jqXHR);
    console.log(textStatus);
  })
  .always(function(){
    console.log('complete');
    location.reload();
  });

});

$('#main').on('mousedown','#button_reset_photos',function(){

  //debugger;
  //console.log('xxx', $(this)[0].href);

  var disease_id = $(this)[0].getAttribute('data-id');

  $.ajax({

    url: api().uri() +'disease/photos/reset/'+disease_id,
    type: 'PUT',
    contentType: "application/json",
    dataType: 'json'


  })
  .done(function(result){


  })
  .fail(function(jqXHR, textStatus) {
    console.log(jqXHR);
    console.log(textStatus);
  })
  .always(function(){
    console.log('complete');
    location.reload();
  });

});
