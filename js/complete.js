  jQuery(document).ready(function(){
    //$('#annonceform').submit(function(event){
    jQuery('input[name="country"]').keyup(function(){
      var valeur = jQuery('input[name="country"]').val();
      var texte = [];
      if (valeur) {
        console.log(valeur);
        jQuery.ajax({
          url: '../fichiers/pays.php' ,
          method: 'POST',
          data: {
            lettre: valeur
          },
          dataType: 'json',
          success: function(donnees, status, jqXHR){
            console.log("je suis dans le success");
            var nbre_pays = donnees.length ;
            for(i = 0; i < nbre_pays ; i++){
              texte.push(donnees[i]) ;
            }
            $( "#country" ).autocomplete({
              source: texte,
              minLength: 0
            })
          },
          error: function(error){
            console.log('error........');
            console.log(error)
          }
        });
      }else{
        jQuery('#country').html('');
      }
    }); 
 // });
//}); 
  jQuery('input[name="ville"]').keyup(function(){
    var valeur = jQuery('input[name="ville"]').val();
    var texte = [];
    if (valeur) {
      console.log(valeur);
      jQuery.ajax({
        url: '../fichiers/ville.php' ,
        method: 'POST',
        data: {
          nom : valeur
        },
        dataType: 'json',
        success: function(donnees, status, jqXHR){
          var nbre_ville = donnees.length ;
            for(i = 0; i < nbre_ville ; i++){
              texte.push(donnees[i]) ;
            }
          $( "#ville" ).autocomplete({
            source: texte,
            minLength: 1
          });
        }
      });
    }else{
      jQuery('#ville').html('');
    }
  });
  jQuery('input[name="saisieregion"]').keyup(function(){
    var valeur = jQuery('input[name="saisieregion"]').val();
    var texte = [];
    if (valeur) {
      jQuery.ajax({
        url: 'fichiers/region.php' ,
        method: 'POST',
        data: {
          lettre: valeur
        },
        dataType: 'json',
        success: function(donnees, status, jqXHR){
          var nbre_ville = donnees.length ;
          for(i = 0; i < nbre_ville ; i++){
            texte.push(donnees[i]) ;
          }
          console.log(texte);
          $( "#region" ).autocomplete({
            source: texte,
            minLength: 1
          });
        }
      });
    }else{
      jQuery('#ville').html('');
    }
  });
  jQuery('input[name="codep"]').keyup(function(){
    var valeur = jQuery('input[name="codep"]').val();  let texte = [];
    if (valeur) {
      console.log(valeur);
      jQuery.ajax({
        url: '../fichiers/cp.php' ,
        method: 'POST',
        data: {
          chiffre: valeur
        },
        dataType: 'json',
        success: function(donnees, status, jqXHR){
          let nbre_cp = donnees.length ;
          for(let i = 0; i < nbre_cp ; i++){
            texte.push(donnees[i]) ;
            console.log(i);
          }
          
          $( "#codep" ).autocomplete({
            source: texte,
            minLength: 1
          });
        }
      });
    }else{
      jQuery('#codep').html('');
    }
  });
  jQuery('input[name="kws"]').keyup(function(){
    var valeur = jQuery('input[name="kws"]').val();  
    let texte = [];
    if (valeur) {
      console.log(valeur);
      jQuery.ajax({
        url: 'fichiers/kws.php' ,
        method: 'POST',
        data: {
          kws: valeur
        },
        dataType: 'json',
        success: function(donnees, status, jqXHR){
          console.log("je suis dans le success");
          let nbre_kws = donnees.length ;
          for(let i = 0; i < nbre_kws ; i++){
            texte.push(donnees[i]) ;
            console.log(i);
          }
          $( "#kws" ).autocomplete({
            source: texte,
            minLength: 0
          });
          /////////////////////////////
          $( "#kws" ).on('change','select',function(){
              var valeur = jQuery('input[name="kws"]').val();  
              let texte = [];
              if (valeur) {
                console.log(valeur);
                jQuery.ajax({
                  url: 'fichiers/kws.php' ,
                  method: 'POST',
                  data: {
                    mot : valeur
                  },
                  dataType: 'json',
                  success: function(donnees, status, jqXHR){
                    console.log("je suis dans le success 2");
                    let nbre_cp = donnees.length ;
                    for(let i = 0; i < nbre_cp ; i++){
                      texte.push(donnees[i]) ;
                      console.log(i);
                    }
                    $( "#kws" ).autocomplete({
                      source: texte,
                      minLength: 0
                    });
                  }
                });
              }else{
                jQuery('#kws').html('');
              }
            });
          /////////////////////////////
        }
      });
    }else{
      jQuery('#kws').html('');
    }
  });
  $('#addimg').click(function(){
    if($('#addphoto').css('display') == 'none' ){
        $('#addphoto').toggle();
        $('#addimg').text('Annuler');
    }else{
        $('#addphoto').toggle();
         $('#addimg').text('Ajoutez des photos');
    }
 });
});