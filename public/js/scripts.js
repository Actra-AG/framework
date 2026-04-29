document.addEventListener("DOMContentLoaded", function() {
    const printBtn = document.getElementById('print-link');

    if (printBtn) {
        printBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Verhindert, dass die Seite nach oben springt (#)
            window.print();
        });
    }
});