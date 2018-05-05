$(document).ready(function(){  
  

        var url = window.location.href.split('/');
    
        // url of API getArticleWithTerms
        uri_rest = url[0]+'//'+url[2]+'/'+url[3]+'/rest/'+url[4]+'/'+url[5];
    
        //url of article page - needs id of articles
        uri_article = url[0]+'//'+url[2]+'/'+url[3]+'/'+url[4]+'/';

        //url of diseases page - needs id of disease
        uri_disease = url[0]+'//'+url[2]+'/'+url[3]+'/'+"diseases"+'/';



        $.ajax({
            
            type: "GET",
            url: uri_rest,
            dataType: 'json',
            data:{'terms':'true'}
            
        })
    
        .done(function(result){
                        
            var article = result;
            var abstract = article['abstract'];
            var terms = article.terms;

            var replacementDict = {};

            $(terms).each(function() {

                term = this['term'];
                var disease_id = this['disease_id'];
                var do_id = this['do_id'];
                var pos_start = this['pos_start'];
                var pos_end = this['pos_end'];

                link = '<a href='+ uri_disease + disease_id + '>' + term + '</a>';

                if (term in replacementDict == false){

                    replacementDict[term] = link;
                }

            });

            //console.log(replacementDict);


            $.each(replacementDict,function(key, value){

                var re = new RegExp(key, 'g');
                abstract = abstract.replace(re,value);

            });

            $('.article_abstract').html(abstract);  

            
        })
        .fail(function(jqXHR, textStatus) {
            console.log(jqXHR);
        })
        .always(function(){
            console.log('complete');
        });

        return false; 
    } 
);
