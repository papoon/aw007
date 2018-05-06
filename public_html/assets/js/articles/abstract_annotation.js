$(document).ready(function(){

        url = window.location.href.split('/');
    
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
                        

            /* Function that replaces MER Terms with links of specific disease

               It needs to replace text with links on both title and abstract at the same time since MER function calculates positions on title and abstract as one text only. This function then separates title from abstract and replaces the content on html page on the specific container

             */

            var article = result;
            var abstract = article['abstract'];
            var terms = article.terms;
            var title = article.title;
            var start = 0;
            var end = 0;
            var clean_text = '';
            var raw_text = title + abstract;
            var new_title_length = title.length;


            // sorts terms by ascendent position
            terms.sort(function(a, b){
              return a.pos_start - b.pos_start;
            });

            $(terms).each(function() {

                var term = this['term'];
                var disease_id = this['disease_id'];
                var do_id = this['do_id'];
                var pos_start = this['pos_start'];
                var pos_end = this['pos_end'];

                link = '<a href='+ uri_disease + disease_id + '>' + term + '</a>';

                end = pos_start;
                tmp = raw_text.substring(start,end);
                clean_text += tmp + link;

                // debugg console.log(" "+ start +" ->  " + end + " ** " + term + "( " + term.length + ") ** text s:e " + tmp );

                // determines size of new title so it can divide the content after

                  if (end <= title.length) {
                    new_title_length += link.length - term.length;

                }

                start = pos_end;

            });

            // Adds last part of string
            if (start != raw_text.length){
                clean_text += raw_text.substring(start,raw_text.length);
            }

            // building new content
            var new_title = clean_text.substring(0, new_title_length);
            var new_abstract = clean_text.substring(new_title_length, clean_text.length );


            //replacing content of view
            $('.article_title').html(new_title);
            $('.article_abstract').html(new_abstract);

            
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

