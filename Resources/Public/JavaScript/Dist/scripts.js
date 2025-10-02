$(document).ready(function() {
    $("#search_open").click(function() {
        $("h1").hide();
        $("#mobilemenu_opener").hide();
        $("#search_close").show();
        $("#searchfield").show();
        $("#searchfield").focus();
    });
    $("#search_close").click(function() {
        $("h1").show();
        $("#mobilemenu_opener").show();
        $("#search_close").hide();
        $("#searchfield").hide();
    });
});