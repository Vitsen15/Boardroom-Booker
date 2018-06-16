confirmDelete();

function confirmDelete() {
    var deleteLinks = document.getElementsByClassName("delete-employee");

    for (var i = 0; i < deleteLinks.length; i++) {
        deleteLinks[i].onclick = function (event) {
            var response = window.confirm("Are you sure you want to delete this contact?");

            if (!response) {
                event.preventDefault();
            }
        };
    }

}
