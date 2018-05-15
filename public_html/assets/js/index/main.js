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
            //statistics(href);
            console.log(href);
        } else if(href ==  "documentation"){
          loadStatic('documentation');
        }
        var targetUrl = baseHref;
        if (href) {
          targetUrl += href;
        }

        history.pushState({id:targetUrl}, '', targetUrl);
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
            var replacer = function(match, field) {
              return disease[field.trim()];
            };

            html += template.replace(/\{\{[^\}]+\.([^\}]+)\}\}/g, replacer);
        });

        return html;
    }

    function statistics(endpoint){

        /*$('.sub_main').load('templates/statistics/index.html',function(data){
            //console.log(data);
            var script = $('<div></div>').append(data).find('script')[1].remove();
            console.log(script);
            requestApiStatistics(endpoint)
        });*/
        var data = requestApiStatistics(endpoint);
        $.get("templates/statistics/index.html", function(data) {

            console.log(data);

            var script = $('<div></div>').append(data).find('script')[1].outerHTML.replace("statistics",statistics);
            var temp = $(data);

            //console.log(temp.html());

            console.log(script);
            //console.log(data);
        });
        //console.log(html);

        return false;
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

});

function requestApiStatistics(endpoint){

    var base_url = window.location.protocol + '//' + window.location.hostname + '/' + window.location.pathname.split('/')[1]
    console.log(base_url);
    //var url = "http://localhost/aw007/"+endpoint;
    var uri = api().uri()+endpoint;
    //console.log(uri)

    $.ajax({
        type: "GET",
        url: uri,
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        //console.log(result);
        //var html = constructStatistics(result);
        //console.log(html);

    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    })
    .always(function(){
        console.log('complete');
    });
}
function constructStatistics(statistics){
    var html ='';
    var statistic;

    for (let index = 0; index < statistics.length; index++) {
        const element = statistics[index];
        console.log(element);
    }


    /*statistics.forEach(statistic => {
        console.log(statistic);
        $(canvas).prop('id',index);

        statistic = $('.statistic').html();
        html += statistic;
    });*/


    return html;
  }
