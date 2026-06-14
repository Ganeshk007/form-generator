document.addEventListener("input", function () {
    let preview = document.getElementById("live-preview");
    preview.innerHTML = document.getElementById("fields-container").innerHTML;
});
