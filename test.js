
$.ajax({   
    url: "test.php",   
    type: "GET",   
    dataType: "json",   
    success: function(json) {   
        console.log("flag");
        console.log(json);
      },   
      error: function(xhr, status, error) {
        console.error(error);
      }
});  