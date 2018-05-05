$('a.image_link_disease').on('click',function(e) {

        e.preventDefault();

        console.log('oLA');

        var endpoint = $(this).attr('href');
        console.log( endpoint);

        $('.sub_main').hide();
        $('.sub_main').load('templates/diseases/disease.html',function(data){
            requestApi(endpoint);
        });

        

        return false; 
    } 
);
function requestApi(endpoint){

    var uri = api().uri()+endpoint;

    var url = window.location.href.split('/');
    url = url[0]+'//'+url[2]+'/'+url[3]+'/'+endpoint;
    console.log(url);
    //recebe o id da doença
    $.ajax({
        type: "GET",
        url: uri,
        dataType: 'json',
        data:{'metadata':'true'}
    })
    .done(function(result){
        console.log('oi');
        console.log(result);

        var disease= result;
        var disease_name = disease.name;
        var disease_abstract_text = disease.abstract;
        var articles = disease.articles;
        var photos = disease.photos;
        var tweets = disease.tweets;
        

        /*$('.page_title').html(disease_name);


        $('.abstract_title').html('Abstract');
        $('.abstract_text').html(disease_abstract_text);

        var abstract_text = '<h1 class="text-center abstract_title">Abstract</h1>';
        abstract_text += '<p class="abstract_text">'+disease_abstract_text+'</p><hr>';

        var html_body_content = '';

        html_body_content += abstract_text;
        //articles
        var articles = disease.articles;
        var html_articles = constructDiseasesArticles(articles);
        html_body_content += html_articles;
        //$('.body_content').html(html_articles);
        //console.log(html_articles);

        //photos
        var photos = disease.photos;
        var html_photos = constructDiseasesPhotos(photos);
        html_body_content += html_photos;
        //$('.body_content').append(html_photos);
        //console.log(html_photos);

        //tweets
        var tweets = disease.tweets;
        var html_tweets = constructDiseasesTweets(tweets);
        html_body_content += html_tweets;
        //$('.body_content').append(html_photos);
        //console.log(html_tweets);


        //set new html page
        $('.body_content').html(html_body_content);*/
        $('.page_title').html(disease_name);
        $('.abstract_text').html(disease_abstract_text);
        var html_articles = constructDiseasesArticles(articles);
        $('.articles').html(html_articles);
        var html_photos = constructDiseasesPhotos(photos);
        $('.photos').html(html_photos);
        var html_tweets = constructDiseasesTweets(tweets);
        $('.tweets').html(html_tweets);

        $('.sub_footer').append('<script src="/aw007/assets/js/articles/main.js"></script>');


        //change url
        history.pushState({id:url}, '', (url == '' ? ''+url : url));
    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    })
    .always(function(){
        $('.sub_main').show();
        console.log('complete');
    });
}

function constructDiseasesArticles(articles){

    /*var html ='';

    html += '<h2 class="text-center">Articles (by order of relevance)</h2>';

    html += '<div class="row fix">';
    articles.forEach(element => {
        html += '<div class="col-sm-6 col-md-4">';
            html += '<div class="thumbnail">';
                html += '<p><h5>'+element.title+'</h5></p>';
                html +='<div class="caption">';
                    html +='<p><a href="/aw007/articles/'+element.id+'" class="btn btn-primary article_link" role="button">Ver</a></p>';
                html +='</div>';
            html +='</div>';
        html +='</div>';
    });
    html +='</div>'*/

    var html ='';
    var articles;


    articles.forEach(article => {
        $('.article_title').html(article.title);
        $('.article_name').html('');
        $('.article_link').prop('href',"/aw007/articles/"+article.id);

        articles = $('.article').html();
        html += articles;
    });


    return html;
}
function constructDiseasesPhotos(photos){

    /*var html ='';

    html += '<h2 class="text-center">Photos</h2>';
    

    html += '<div class="row fix">';
    photos.forEach(photo => { 
        html += '<div class="col-md-3">';
        html += '<div class="thumbnail">';
        html +='<img src="'+photo.url+'" alt="'+photo.url+'" class="img-responsive" style="min-height:150px; max-height:150px; width:100%;">';
        html +='<div class="caption">';
        html +='<h3>'+photo.username+'</h3>';
        html +='<p>Likes: '+photo.nr_likes+'</p>';
        html +='<p>Comments: '+photo.nr_comments+'</p>';
        html +='<p>Shares: '+photo.shares+'</p>';
        html +='<p>Published At: '+photo.published_at+'</p>';
        html +='</div>';
        html +='</div>';
        html +='</div>';
    });
    html +='</div>';

    return html;*/

    var html ='';

    photos.forEach(photo => {
        
        $('.photo_url').prop('src',photo.url);
        $('.photo_nr_like').html(photo.nr_likes);
        $('.photo_username').html(photo.username);
        $('.photo_nr_comment').html(photo.nr_comments);
        $('.photo_nr_share').html(photo.shares);
        $('.photo_published_at').html(photo.published_at);


        //articles = $('.photo').html();
        html += $('.photo').html();
    });


    return html;
}

function constructDiseasesTweets(tweets){

    /*var html ='';

    html +='<h2 class="text-center">Tweets (by torder of relevance)</h2>';

    html +='<hr>';

    html +='<div class="row fix">';
    tweets.forEach(tweet => {
        html +='<div class="col-md-4">';
        html +='<div class="thumbnail">';
        html +=''+tweet.html;
                    
        html +='<div class="caption">';
        html +='<h3>'+tweet.username+'</h3>';
        html +='<p>Likes: '+tweet.nr_likes+'</p>';
        html +='<p>Comments: '+tweet.nr_comments+'</p>';
        html +='<p>Shares: '+tweet.shares+'</p>';
        html +='<p>Published At: '+tweet.published_at+'</p>';
        html +='</div>';
        html +='<a href="'+tweet.url+'" target="_blank" class="btn btn-primary" role="button">Tweet</a>';
        html +='</div>';
        html +='</div>';
    });
    html +='</div>';

    return html;*/

    var html ='';

    tweets.forEach(tweet => {
        
        $('.tweet_url').prop('src',tweet.url);
        $('.tweet_html').html(tweet.html);
        $('.tweet_username').html(tweet.username);
        $('.tweet_nr_likes').html(tweet.nr_likes);
        
        $('.tweet_comment').html(tweet.nr_comments);
        $('.tweet_share').html(tweet.shares);
        $('.tweet_published_at').html(tweet.published_at);


        //articles = $('.photo').html();
        html += $('.tweet').html();
    });


    return html;

}