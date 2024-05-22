function searchCourses() {
    var input, filter, container, buttons, h5, i, txtValue;
    input = document.getElementById("searchCourse");
    filter = input.value.toUpperCase();
    container = document.getElementById("courseContainer");
    buttons = container.getElementsByTagName("button");

    for (i = 0; i < buttons.length; i++) {
        h5 = buttons[i].getElementsByTagName("h5")[0];
        txtValue = h5.textContent || h5.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            buttons[i].style.display = "";
        } else {
            buttons[i].style.display = "none";
        }
    }
}
