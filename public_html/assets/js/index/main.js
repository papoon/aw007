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

            console.log('»»» link click', $(this).attr('href'));
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
            console.log('href');
            home();
        }
        if(href ==  "diseases"){
            diseases();
            console.log(href);
        }
        if(href ==  "statistics"){
            //statistics(href);
            console.log(href);
        }
        if(href ==  "documentation"){
            console.log(href);
            //$('.page_content').html('documentation/documentation.html');
        }


        var targetUrl = baseHref;
        if (href) {
          targetUrl += href;
        }

        history.pushState({id:targetUrl}, '', targetUrl);


        /*href = 'http://'+window.location.hostname+'/aw007/'+href;

        $.ajax({
            type: "GET",
            url: href,
            dataType: 'html'
        })
        .done(function(result){

            console.log('oi');

            var sub_main_content = $('<div></div>').append(result).find('#main .sub_main').html();
            //console.log($('<div></div>').append(result).find('#main .sub_main').html());

            //insert the new sub_main content
            $('#main .sub_main').html(sub_main_content);

            //footer replaces
            var sub_footer_content = $('<div></div>').append(result).find('.sub_footer').html();
            console.log(sub_footer_content);

            //insert the new sub_main content
            var prevObject = $(sub_footer_content).filter('script');
            console.log(prevObject);

            $('.sub_footer').html('');
            //var scripts = prevObject[prevObject.length -1 ];
            for (let index = 0; index < prevObject.length; index++) {
                const script = prevObject[index];
                $('.sub_footer').append(script);
            }
            //console.log(scripts);
            console.log(prevObject);
            //change url
            history.pushState({id:href}, '', (href == '' ? ''+href : href));
        })
        .fail(function(jqXHR, textStatus) {
            console.log(jqXHR);
        })
        .always(function(){
            console.log('complete');
        });*/
    }

    function home(){
        $('.sub_main').hide();
        $('.sub_main').load('templates/index/index.html', function(data) {
          // No templating needed
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
              console.log(url);
              history.pushState({id:url}, '', (url == '' ? ''+url : url));
          })
          .fail(function(jqXHR, textStatus) {
              console.log(jqXHR);
          })
          .always(function(){
              $('.sub_main').show();
              console.log('complete');
          });

            console.log(data);
        });

        return false;
    }

    function constructDiseases(diseases) {
        var html = '';
        // Remove twig stuff
        var template = $('.disease_template').parent().html().replace(/\{\%.*\%\}/g,'');

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
