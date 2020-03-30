$( document ).ready(function() {

    $("body").on("mousedown", ".clickable", function (e) {
        e.preventDefault();
        let $this = $(this);
        let href = $this.data("href");

        let leftClick = e.which == 1;
        let middleClick = e.which == 2;
        let leftCtrlClick = leftClick && e.ctrlKey;

        if (leftClick && !leftCtrlClick) {
            window.location.href = href;

        } else if (middleClick || leftCtrlClick) {
            openInNewTab(href);
        }
    });

    function openInNewTab(url) {
        let new_window = window.open(url);
        new_window.blur();
        window.focus();
    }
});