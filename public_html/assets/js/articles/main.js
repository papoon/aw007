$('a.article_link').on('click',function(e) {

    e.preventDefault();

    console.log('oi');

    /*var endpoint = $(this).attr('href').split('/');
    endpoint = endpoint[2] +'/'+ endpoint[3];
    console.log( endpoint);

    var uri = api().uri()+endpoint;*/

    //var endpoint = $(this).attr('href').split('/');

    /*var url = window.location.href.split('/');
    url = url[0]+'//'+url[2]+'/'+url[3]+'/'+endpoint;
    console.log(url);*/

    /*$.ajax({
        type: "GET",
        url: uri+'?terms=true',
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        console.log(result);

        var article = result;
        var terms = article.terms;

        var html_article = '';

        html_article += backContent();

        html_article += abstractBodyContent(article);

        html_article += merContent(terms);

        html_article += ratingContent();

        html_article += feedbackContent();

        $('.page_content').html(html_article);

        //change url
        history.pushState({id:url}, '', (url == '' ? ''+url : url));
    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    })
    .always(function(){
        console.log('complete');
    });*/


    return false; 

});

function backContent(){

    var html = '';
    html+='<div class="row">';
    html+='<div class="col-md-12">';
    html+='<a href="/aw007/articles" class="btn btn-info article_link" role="button">< Back</a>';
    html+='</div>';
    html+='</div>';

    return html;

}

function abstractBodyContent(article){

    var html = '';
    html +='<div class="row">';
    html +='<div class="col-md-12">';
    html +='<h2 class="text-center">'+article.title+'</h2>';

    html +='<p>'+article.abstract+'</p>';

    html +='<address>'
    html +='<strong>Article Id: '+article.article_id+'</strong><br>';
    html +='<strong>Journal Id: '+article.journal_id+'</strong><br>';
    html +='<strong><small>Authors: <em>'+article.authors+'</em></small></strong><br>';
    html +='<strong><small>Published At: +'+article.published_at+'</small></strong><br>';
    html +='</address>';

    html +='</div>';
    html +=' </div>';

    return html;
}
function merContent(articleMERTerms){

    var html = '';
    html+='<div class="row">';
    html+='<div class="col-md-12">';
    html+='<h4>Article MER Terms</h4>';

            if(articleMERTerms.length > 0)
            {

                html+='<table class="table table-bordered">';
                html+='<tr>';
                html+='<th>Term/Entity</th>';
                html+='<th>Start Position</th>';
                html+='<th>End Position</th>';
                html+='</tr>';

                articleMERTerms.forEach(term => {
                    html+='<tr>';
                    html+='<td>'+term.term+'</td>';
                    html+='<td>'+term.pos_start+'</td>';
                    html+='<td>'+term.pos_end+'</td>';
                    html+='</tr>';
                });
                html+='</table>';
            }
            else
            {
                html+='<p>No terms/entities found.</p>';
            }

            html+='</div>';
            html+='</div>';

    return html;

}
function ratingContent(){
    var html = "";

    html+='<div class="row">';
    html+='<h2 class="text-center">Star Rating c</h2>';
    html+='<div class="col-sm-6 col-sm-offset-2">';
        
    html+='<div class="rating">';
    html+='<span class="star_rating" value="-1">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<span class="star_rating">☆</span>';
    html+='<br>';
    html+='<span>5</span>';
    html+='<span>4</span>';
    html+='<span>3</span>';
    html+='<span>2</span>';
    html+='<span>1</span>';
    html+='<span>0</span>';
    html+='<span>1-</span>';
    html+='<br>';
    html+='</div>';
    html+='</div>';
    html+='</div>';

    return html;
}
function feedbackContent(){
    var html = '';

    html+='<div class="row">';
    html+='<div class="col-sm-8 col-sm-offset-2">';
    html+='<label for="article_comment">Give us your feedback about this article:</label>';
    html+='<textarea class="form-control" rows="5"></textarea>';
    html+='</div>';
    html+=' </div>';

    return html;
}