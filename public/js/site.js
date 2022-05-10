$(document).ready(function () {
    $("#login").submit(function( event ) {
        event.preventDefault();
        this.submit();
        clearForm()
    });
    function clearForm()
    {
        $("#loginUsername").val(null);
        $("#loginPassword").val(null);
    }

    $('#listSort').change(function (event) {
        window.location.replace("?sort=" + $( "#listSort option:selected" ).val());
    })
});

