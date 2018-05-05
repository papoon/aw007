$(document).ready(function(){  
  

        var url = window.location.href.split('/');
    
        // url of API getArticleWithTerms
        uri_rest = url[0]+'//'+url[2]+'/'+url[3]+'/rest/'+url[4]+'/'+url[5];
    
        //url of article page - needs id of articles
        uri_article = url[0]+'//'+url[2]+'/'+url[3]+'/'+url[4]+'/';
               
        
        $.ajax({
            
            type: "GET",
            url: uri_rest,
            dataType: 'json',
            data:{'terms':'true'}
            
        })
    
        .done(function(result){
                        
            var article = result;
            var terms = article.terms;
            
            console.log(terms);

            $(terms).each(function() {
               
                
                
            });

            
            /*
            var disease= result;
            var disease_name = disease.name;
            var disease_abstract_text = disease.abstract;
            

            $('.page_title').html(disease_name);


            $('.abstract_title').html('Abstract');
            $('.abstract_text').html(disease_abstract_text);

            var abstract_text = '<h1 class="text-center abstract_title">Abstract</h1>';
            abstract_text += '<p class="abstract_text">'+disease_abstract_text+'</p><hr>';

            var html_body_content = '';

            html_body_content += abstract_text;
            //articles
            console.log(disease);
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
            console.log("html_tweets");


            //set new html page
            $('.body_content').html(html_body_content);


            //change url
            history.pushState({id:url}, '', (url == '' ? ''+url : url));
            */
            
            
        })
        .fail(function(jqXHR, textStatus) {
            console.log("Uups, something failed");
            console.log(jqXHR);
        })
        .always(function(){
            console.log('complete');
        });

        return false; 
    } 
);
