<script>
    (function() {
        // Push a new state to the history stack
        window.history.pushState(null, "", window.location.href);

        // Listen for the popstate event (back/forward button press)
        window.onpopstate = function() {
            // When back is pressed, push the state again to keep the user on the current page
            window.history.pushState(null, "", window.location.href);
        };
    })();
</script>
