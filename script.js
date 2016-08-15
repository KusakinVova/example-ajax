$(document).ready(function(){
    $(".ajax_form .submit_data_form").on('click',function(){
        var this_form = $(this).parents("form");
        var data = this_form.serialize();
        $.ajax({
          dataType: "json",
          type: "POST",
          url: "ajax.php",
          data: data,
          success: function(jsondata){
            this_form.trigger('reset'); 
            alert(jsondata); // ответ от сервера
          }
        });
    });
});

