$( document ).ready(function() {

    let good_star = $(".good_star");

    good_star.on('click', function(e) {
        let searchParams = new URLSearchParams(window.location.search);
        let $elem = $(this);

        $.ajax({
            url: "/api/flipGoodValue.php",
            data: {experiment_id: searchParams.get("id")}

        }).done(function() {
            let current_text = $elem.text();

            if (current_text === String.fromCharCode(9734)) { // ☆
                $elem.text(String.fromCharCode(9733)); // ★
                $elem.css("color", "orange");

            } else if (current_text === String.fromCharCode(9733)) { // ★
                $elem.text(String.fromCharCode(9734)); // ☆
                $elem.css("color", "gray");
            }
        });
    });

});