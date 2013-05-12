$(document).ready(function() {

    $("#addElement").click(
        function() {
            ajaxAddField();
        }
    );

    $("#removeElement").click(
        function() {
            removeField();
        }
    );

    // Get value of id - integer appended to dynamic form field names and ids
    var id = $("#id").val();
    console.log(id);

    // Retrieve new element's html from controller
    function ajaxAddField() {
        $.ajax(
            {
                type: "POST",
                url: "/attribute/newfield",
                data: "id=" + id,
                success: function(newElement) {

                    // Insert new element before the Add button
                    $("#addElement-label").before(newElement);

                    // Increment and store id
                    $("#id").val(++id);
                }
            }
        );
    }

    function removeField() {

        // Get the last used id
        var lastId = $("#id").val() - 1;

        // Build the attribute search string.  This will match the last added  dt and dd elements.
        // Specifically, it matches any element where the id begins with 'newName<int>-'.
        searchString = '*[id^=value-' + lastId + '-]';

        // Remove the elements that match the search string.
        $(searchString).remove()

        // Decrement and store id
        $("#id").val(--id);
    }
});