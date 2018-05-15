var baseHref = document.getElementsByTagName('base')[0].href;

$(document).ready(function () {
  window.addEventListener('popstate', function (event) {
    if (history.state && history.state.id === '') {
      loadPage('');
    }
    if (history.state && history.state.id === 'diseases') {
      loadPage('diseases');
    }
    if (history.state && history.state.id === 'statistics') {
      loadPage('statistics');
    }
    if (history.state && history.state.id === 'documentation') {
      loadPage('documentation');

    }

  }, false);

  $('li a').click(function (e) {

    if ($(this).parent().attr('class') != "disabled") {
      if ($(this).attr('href') == "#home") {
        loadPage('');
      }
      if ($(this).attr('href') == "#diseases") {
        loadPage('diseases');
      }
      if ($(this).attr('href') == "#statistics") {
        loadPage('statistics');
      }
      if ($(this).attr('href') == "#documentation") {
        loadPage('documentation');
      }
    }
  });

  function loadPage(href)
  {

    if(href == ""){
      loadStatic('index');
    } else if(href ==  "diseases"){
      diseases();
    } else if(href ==  "statistics"){
      statistics();
    } else if(href ==  "documentation"){
      loadStatic('documentation');
    }
    var targetUrl = baseHref;
    if (href) {
      targetUrl += href;
    }

    history.pushState({id:targetUrl}, '', targetUrl);
  }


  function loadStatic(template) {
    $('.sub_main').hide();
    $.get('templates/' + template + '/index.html', function(data) {
      // No templating needed
      $('.sub_main').html(cleanTemplate(data));
      $('.sub_main').show();
    });
    return false;
  }

  function diseases(){
    $('.sub_main').hide();
    $('.sub_main').load('templates/diseases/index.html',function(data){
      var endpoint = 'diseases/0';
      var uri = api().uri()+endpoint;

      $.ajax({
        type: "GET",
        url: uri,
        dataType: 'json',
      })
      .done(function(result){
        //set new html page
        var html_diseases = constructDiseases(result);
        $('.diseases').html(html_diseases);

        //change url
        var url = baseHref + 'diseases';
        history.pushState({id:url}, '', (url == '' ? ''+url : url));
      })
      .fail(function(jqXHR, textStatus) {
      })
      .always(function(){
        $('.sub_main').show();
      });
    });

    return false;
  }

  function constructDiseases(diseases) {
    var html = '';
    // Remove twig stuff
    var template = cleanTemplate($('.disease_template').parent().html());

    diseases.forEach(disease => {
      html += applyTemplate(template, { disease: disease });
    });

    return html;
  }

  function statistics(){
    $('.sub_main').hide();
    $.get('templates/statistics/index.html',function(template){
      var endpoint = 'statistics';
      var uri = api().uri()+endpoint;

      $.ajax({
        type: "GET",
        url: uri,
        dataType: 'json',
      })
      .done(function(data) {
        //set new html page
        var html = applyStatistics(template, data);
        $('.sub_main').html(html);

        //change url
        var url = baseHref + 'statistics';
        history.pushState({id:url}, '', (url == '' ? ''+url : url));
      })
      .fail(function(jqXHR, textStatus) {
      })
      .always(function(){
        $('.sub_main').show();
      });
    });

    return false;
  }


  function applyStatistics(template, data){
    // apply webservice data to template
    var html = template.replace('{{ statistics|json_encode|raw }}', JSON.stringify(data));
    var statisticTemplateRegex = /\{%[^%]+%\}((?:[^\{]|(\{\{))+)\{% endfor %\}/g;

    html = html.replace(statisticTemplateRegex, function(match, statisticTemplate) {
      var innerHtml = '';
      for(var key in data) {
        var statistic = data[key];

        innerHtml += applyTemplate(statisticTemplate, { key: key, statistic: statistic });
      }

      return innerHtml;
    });

    return html;
  }
});
