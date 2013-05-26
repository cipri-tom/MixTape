function loadFriends() {
   $("#showFriends").remove();
   $(".col2").append( "<div id='bePatient'><h4>Please be patient while we retrieve your contacts."
                    + "<br/>This may take some time... </h4>"
                    + "<img src='images/spinner.gif' style='width:30px; display:inline-block;"
                    + "position:absolute; top:47px; right:30px;' /></div>");
   var size = $('#size').val()
   $('#size').remove();
   $('#size1').remove();
   $(".col2").append( $('<div>').load('functions.php', {'type' : 'left', 'size' : size},
      function(){
         $("a.tracks").each(function(index) {
            var id = $(this).attr('id').split('-')[1];
            $('.col1').append( $('<div>').load('functions.php',
                                 {'type' : 'middle', 'id' : id, 'size' : '5'}));
         $('#bePatient').remove();
         });
      }));
}

function loadTracks(source, name) {
   var id = source.id.split('-')[1];
   $('.col1').empty();
   $('.col1').append("<h3>Retrieving tracks for " + name + "</h3><img src='images/spinner.gif' style='width:30px; display:inline-block;"
                    + "position:absolute; top:0px; right:0px;' />");
   $('.col1').load('functions.php', {'type' : 'middle', 'id' : id, 'size' : '50'},
                   function(responseText, textStatus, request) {
                     if (responseText.length == 0)
                        $('.col1').append("<h3>User " + name + " has no public songs</h3>");
                   });
}

function loadArtists() {
   $(".col2").append( "<h4 id='bePatient'>Please be patient while we search for recommendations."
                    + "<br/>This may take some time... <img src='images/spinner.gif' style='width:30px; display:inline-block;"
                    + "position:absolute; top:47px; right:30px;' /></h4>");
   $('.col2').append( $('<div>').load('functions.php', {'type' : 'artists'},
      function() {
         $('#bePatient').remove();
         $('td').css('text-align', 'center');
      }));
}

function loadRecom(source) {
   $('.col1').empty();
   var img = $('input').is(':checked');
   $('.col1').append(  "<h3>Retrieving recommendations for " + source.id
                     + ".<br/>Please be patient...<img src='images/spinner.gif' style='width:30px; display:inline-block;"
                    + "position:absolute; top:3px; right:0px;' /></h3>");
   $('.col1').load('functions.php', {'type' : 'recom', 'artist' : source.id, 'img' : img},
      function() {
         $('td img').css('max-height', '200px');
         $('td').css('text-align', 'center');
      });
}

$(document).ready(function() {
   page = window.location.href.split('/');
   page = page[page.length - 1];
   if (page == "recom.php" || page == "recom.php#")
      loadArtists();
});
