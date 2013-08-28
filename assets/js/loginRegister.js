$(document).ready(function(){
  
  // Login 
  $('#login_form').submit(function(){
    $.post
    (
      $(this).attr('action'),
      $(this).serialize(),
      function(data){
        if (data == "success") {
          window.location.href = "/dashboard/index";
        }
        else{
          console.log(data);
          $('#alert_box1').html(data);
        };
      },
      "json"
    );
    return false;
  });

  // Register User
  $('#register_user_form').submit(function(e){
    $.post
    (
      $(this).attr('action'),
      $(this).serialize(),
      function(data){
        alert("user registered");
        console.log(data);
        $('#alert_box2').html(data);
      },
      "json"
    );
    this.reset();
    return false;
  });

  // Register Org
  $('#register_org_form').submit(function(){
    $.post
    (
      $(this).attr('action'),
      $(this).serialize(),
      function(data){
        console.log(data);
        $('#alert_box3').html(data);
      },
      "json"
    );
    this.reset();
    return false;
  });
});

/* End of file loginRegister.js */
/* Location: ./assets/js/loginRegister.js */