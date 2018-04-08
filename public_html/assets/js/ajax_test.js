    $.ajax({
        type: "GET",
        url: 'http://localhost/aw007/rest/diseases',
        dataType: 'json',
        beforeSend: function(xhr){xhr.setRequestHeader('Content-Type', 'application/json');},
    })
    .done(function(result){
        //console.log(result);
        var diseases = [];
        result.forEach(function(entry) {
            console.log(entry.name);
            diseases.push(entry.name);
        });
        
    })
    .fail(function(jqXHR, textStatus) {
        console.log(jqXHR);
    })
    .always(function(){
        console.log('complete');
    });
