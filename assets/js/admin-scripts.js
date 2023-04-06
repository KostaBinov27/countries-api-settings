jQuery(document).ready(function($){
  async function getCountries(urlLink) {
    let res = await fetch(urlLink, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            query: `{
                countries{
                    name
                    capital
                    currency
                    native
                    emoji
                    languages{
                      name
                    }
                    continent{
                      name
                    }
                }
            }`
        })
    })
    .then((response) => response.json())
    .then((response) => {
      if (response.errors) {
        $(".resposne").removeClass('green');
        $(".resposne").addClass('red');
        $(".resposne").html(response.errors[0].message);
      }
      if (response.data) {
        $(".resposne").removeClass('red');
        $(".resposne").addClass('green');
        $(".resposne").html("OK");
      }
    });
  }

  $(".timelimit").keyup(function(){
    if ($(this).val() < 1){
      alert("No numbers below 1");
      $(this).val('1');
    }
  });

  $("#checkResponse").click(function(){
    let urlApi = $(".apilink").val();
    let checkAPI = getCountries(urlApi);    
    console.log(checkAPI);
  });
    
});