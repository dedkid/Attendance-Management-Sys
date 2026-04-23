$(document).ready(function () {
    var currentSlide = 0;
    var totalSlides = $(".carousel-item").length;

    // Hide/show the prev/next buttons based on the current slide
    if (currentSlide === 0) {
        $(".pagination a.prev").hide();
    }

    // Handle next button click
    $(".pagination a.next").click(function () {
        currentSlide++;
        if (currentSlide >= totalSlides) {
            currentSlide = totalSlides - 1;
        }
        updateCarousel();
        return false;
    });

    // Handle previous button click
    $(".pagination a.prev").click(function () {
        currentSlide--;
        if (currentSlide < 0) {
            currentSlide = 0;
        }
        updateCarousel();
        return false;
    });

    // Function to update the carousel display
    function updateCarousel() {
        $(".carousel-item").hide();
        $(".carousel-item:eq(" + currentSlide + ")").fadeIn();
        // Hide/show the prev/next buttons based on the current slide
        if (currentSlide === 0) {
            $(".pagination a.prev").hide();
        } else if (currentSlide === totalSlides - 1) {
            $(".pagination a.next").hide();
        } else {
            $(".pagination a.prev").show();
            $(".pagination a.next").show();
        }
    }

    // Initial update of the carousel
    updateCarousel();
});