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
      loadStatic('index', initAutoComplete);
    } else if(href ==  "diseases"){
      diseases();
    } else if(href ==  "statistics"){
      statistics();
    } else if(href ==  "documentation"){
      loadStatic('documentation', initAccordion);
    }

    var targetUrl = baseHref;
    if (href) {
      targetUrl += href;
    }

    history.pushState({id:targetUrl}, '', targetUrl);
  }

  function loadStatic(template, onAfterLoad) {
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

  function diseases() {
    $('.sub_main').hide();
    loadTwigTemplate('templates/diseases/index.html', loadDiseasesData);
  }

  function loadDiseasesData(template) {
    var endpoint = 'diseases/0';
    var uri = api().uri()+endpoint;

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
  }

  function statistics(){
    $('.sub_main').hide();
    loadTwigTemplate('templates/statistics/index.html', loadStatisticsData);
  }

  function loadStatisticsData(template) {
    var endpoint = 'statistics';
    var uri = api().uri()+endpoint;

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
  }
});
