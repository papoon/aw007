
$('#main').on('click','#hero-demo',function(){
    var autocmplete = new autoComplete({
        selector: 'input[name="q"]',
        minChars: 2,
        source: function(term, suggest){
    
            try { xhr.abort(); } catch(e){}
            /*var xhr = $.getJSON('http://localhost/aw007/rest/diseases', function(data){
                console.log(data);
                //response(data); 
            });*/
           
            xhr = $.ajax({
                type: "GET",
                url: api().uri()+'diseases',
                dataType: 'json',
                beforeSend: function(xhr){xhr.setRequestHeader('Content-Type', 'application/json');},
            })
            .done(function(result){
                //console.log(result);
                var diseases = [];
                result.forEach(function(entry) {
                    console.log(entry.name);
                    diseases.push([entry.name,entry.id]);
                });
    
                term = term.toLowerCase();
                var choices = diseases;
                var matches = [];
                for (i=0; i<choices.length; i++)
                    if (~choices[i][0].toLowerCase().indexOf(term)){
                        matches.push(choices[i]);
                        
                    }    
                suggest(matches);
                
                
            })
            .fail(function(jqXHR, textStatus) {
                console.log(jqXHR);
            })
            .always(function(){
                console.log('complete');
            });
        },
        renderItem: function (item, search){
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" data-id="'+item[1]+'" data-val="'+search+'"> '+item[0].replace(re, "<b>$1</b>")+'</div>';
        },
        onSelect: function(e, term, item){
            window.location.href="/aw007/diseases/"+item.getAttribute('data-id');
            //console.log(term);
            //console.log(item.getAttribute('data-id'));
        }
    });
});
