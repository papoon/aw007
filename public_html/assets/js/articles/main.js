/* ---------- CLICK EVENTS ---------- */

$(document).ready(function(){



    /* Button that displays mer terms table */
    $("#btn_term_table_display").click(function() {

        $("#article_mer_terms").toggle();

    });

    /* Button that displays articles */
    $('a.article_link').on('click',function(e) {

        e.preventDefault();


        var id_article = $(this).attr('id');

        var endpoint = 'articles/'+id_article;
        console.log( endpoint);


        //$('.sub_main').hide();
        $('.sub_main').load('../templates/articles/article.html',function(data){

            requestApiArticle(endpoint);
        });

        return false;

    });


});


function requestApiArticle(endpoint){

    var base_url = window.location.protocol + '//' + window.location.hostname + '/' + window.location.pathname.split('/')[1]
    console.log(base_url);
    var url = "http://localhost/aw007/"+endpoint;
    var uri = api().uri()+endpoint;
    console.log(uri);

    $.ajax({
        type: "GET",
        url: uri+'?terms=true',
        dataType: 'json'
    })
    .done(function(result){

        console.log('oi');
        //console.log(result);

        var article = result;
        var terms = article.terms;
        var article_title = article.title;
        var article_abstract = article.abstract;
        var article_id = article.article_id;
        var article_journal_id = article.journal_id;
        var article_authors = article.authors;
        var article_published_at = article.published_at;

        $('.article_title').html(article_title);
        $('.id_article').html(article.id);
        $('.article_abstract').html(article_abstract);
        $('.article_id').html(article_id);
        $('.article_journal_id').html(article_journal_id);
        $('.article_authors').html(article_authors);
        $('.article_published_At').html(article_published_at);

        console.log('oi');

        if(terms.length>0){

            var terms_table_html = merContent(terms);
            $('.term_table').html(terms_table_html);
        }
        else{
            $('.term_table').html('<p class="article_no_terms">No terms/entities found.</p>')
        }

        $('.sub_footer').append('<script src="/aw007/assets/js/articles/comment.js"></script>');
        $('.sub_footer').append('<script src="/aw007/assets/js/articles/rating.js"></script>');

        //change url
        history.pushState({id:url}, '', (url == '' ? ''+url : url));
    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    })
    .always(function(){
        console.log('complete');
    });
}
function merContent(articleMERTerms){

    var html = '';

    var term_t = '';
    console.log(articleMERTerms);
    articleMERTerms.forEach(term => {
        $('.article_term').html(term.term);
        $('.article_term_pos_start').html(term.pos_start);
        $('.article_term_pos_end').html(term.pos_end);
        $('.article_do_id').html(term.do_id);
        $('.article_feedback_likes').html(term.article_feedback_likes);
        $('.article_feedback_dislikes').html(term.article_feedback_dislikes);


        html += $('.articles_terms_table').html();
    });

    var header_table = $('.articles_terms_table tr').html();
    var table_tr = "";
    $($.parseHTML(html)).find('tr').each(function(i){
        if(i%2 != 0){
            table_tr += '<tr>'+$(this).html()+'</tr>';
        }
    });

    $('.articles_terms_table').find('tr').eq(0).html(header_table);
    $('.articles_terms_table').find('tr').eq(1).html(table_tr);
    //var table_html = $('.articles_terms_table').html();
    var table_html = $('.articles_terms_table').html();

    table_html = $('.term_table table')[0].outerHTML;

    return table_html;

}


