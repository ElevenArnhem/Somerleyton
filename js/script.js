$(document).ready(function($) {
    $("input[name='enable']").click(function(){
        if ($(this).is(':checked')) {
            $('#textbox').attr('disabled', 'disabled');
            $('#textbox').removeAttr("name", false);

            $('input.textbox:text').attr("disabled", false);
            $('input.textbox:text').removeAttr('disabled');
            $('input.textbox:text').attr("name", "LATINNAME");
        }
        else if ($(this).not(':checked')) {
                var remove = '';
                $('#textbox').removeAttr('disabled');
                $('#textbox').attr("name", "LATINNAME");

                $('input.textbox:text').attr ('value', remove);
                $('input.textbox:text').removeAttr("name", false);
                $('input.textbox:text').attr("disabled", true);
            }
    }); });