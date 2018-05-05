
function api() {
    return {
      uri: function() {
        
        if(window.location.host == "localhost"){
            var uri = " http://localhost/aw007/rest/";
        }
        else{
            var uri = "http://appserver.alunos.di.fc.ul.pt/~aw007/rest/";
        }
  
        return uri;
      }
    }
};