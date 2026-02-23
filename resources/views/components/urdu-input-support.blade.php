{{-- Urdu Input Support - Robust Phonetic Keyboard and RTL Styling --}}
<style>
    /* Global RTL Support for Inputs in RTL contexts */
    [dir="rtl"] input[type="text"],
    [dir="rtl"] input[type="search"],
    [dir="rtl"] input[type="tel"],
    [dir="rtl"] textarea,
    [dir="rtl"] select,
    input[data-urdu="true"],
    textarea[data-urdu="true"],
    .urdu-text {
        direction: rtl !important;
        text-align: right !important;
        font-family: "Noto Nastaliq Urdu", "Jameel Noori Nastaleeq", "Segoe UI", Tahoma, sans-serif !important;
        unicode-bidi: plaintext;
    }
    
    /* Numbers and Dates should remain LTR */
    [dir="rtl"] input[type="number"],
    [dir="rtl"] input[type="date"],
    [dir="rtl"] input[type="time"],
    input[data-no-urdu="true"] {
        direction: ltr !important;
        text-align: left !important;
        font-family: inherit !important;
    }

</style>

<script>
(function() {
    var urduKeyMap = {
        'q': 'ق', 'w': 'و', 'e': 'ع', 'r': 'ر', 't': 'ت', 'y': 'ے', 'u': 'ء', 'i': 'ی', 'o': 'ہ', 'p': 'پ',
        'a': 'ا', 's': 'س', 'd': 'د', 'f': 'ف', 'g': 'گ', 'h': 'ح', 'j': 'ج', 'k': 'ک', 'l': 'ل',
        'z': 'ز', 'x': 'ش', 'c': 'چ', 'v': 'ط', 'b': 'ب', 'n': 'ن', 'm': 'م',
        'Q': 'ْ', 'W': 'ّ', 'E': 'ٰ', 'R': 'ڑ', 'T': 'ٹ', 'Y': 'َ', 'U': 'ئ', 'I': 'ِ', 'O': 'ۃ', 'P': 'ُ',
        'A': 'آ', 'S': 'ص', 'D': 'ڈ', 'F': 'ف', 'G': 'غ', 'H': 'ھ', 'J': 'ض', 'K': 'خ', 'L': 'ل',
        'Z': 'ذ', 'X': 'ژ', 'C': 'ث', 'V': 'ظ', 'B': 'ؓ', 'N': 'ں', 'M': 'م',
        ',': '،', '.': '۔', '?': '؟', ';': '؛', "'": '‘', '"': '“',
        '[': ']', ']': '[', '{': '}', '}': '{', '(': ')', ')': '(',
        '/': '؟', '-': '-', '=': '='
    };

    function insertAtCursor(input, text) {
        var start = input.selectionStart;
        var end = input.selectionEnd;
        var val = input.value;
        input.value = val.substring(0, start) + text + val.substring(end);
        input.selectionStart = input.selectionEnd = start + text.length;
        
        // Trigger both input and change for maximal compatibility
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function isUrduEnabled(el) {
        if (!el || (el.tagName !== 'INPUT' && el.tagName !== 'TEXTAREA')) return false;
        
        // Explicit Exclusion
        if (el.dataset.noUrdu === "true") return false;
        
        // Technical types
        const skipTypes = ['number', 'date', 'password', 'file', 'checkbox', 'radio', 'range', 'color'];
        if (el.tagName === 'INPUT' && skipTypes.includes(el.type)) return false;
        
        // Broad inclusion check
        const isRtl = document.documentElement.dir === 'rtl' || 
                     document.documentElement.getAttribute('dir') === 'rtl' ||
                     getComputedStyle(el).direction === 'rtl';
                     
        const hasUrduMarker = el.dataset.urdu === "true" || 
                             el.classList.contains('urdu-text') || 
                             el.classList.contains('urdu-input') ||
                             el.name?.toLowerCase().includes('urdu') ||
                             el.id?.toLowerCase().includes('urdu') ||
                             el.placeholder?.match(/[\u0600-\u06FF]/);
                             
        return isRtl || hasUrduMarker;
    }

    function initializeField(el) {
        if (el.dataset && el.dataset.urduInitialized) return;
        if (el.dataset) {
            el.dataset.urduInitialized = "true";
        }
        
        // Force RTL if not already set by CSS
        el.style.direction = 'rtl';
        el.style.textAlign = 'right';
        
        // Visual Urdu badge intentionally removed to keep input fields clean.
    }

    // Global Event Delegation with Capture Phase
    document.addEventListener('keydown', function(e) {
        var el = e.target;
        if (!isUrduEnabled(el)) return;
        
        // Allow common control shortcuts (Ctrl+A, Ctrl+C, etc.)
        if (e.ctrlKey || e.altKey || e.metaKey) return;
        
        // Only intercept single character keys
        if (!e.key || e.key.length !== 1) return;

        var urduChar = urduKeyMap[e.key];
        if (urduChar) {
            e.preventDefault();
            e.stopPropagation();
            insertAtCursor(el, urduChar);
        }
    }, true);

    // Styling/Badge initialization on focus
    document.addEventListener('focusin', function(e) {
        var el = e.target;
        if (isUrduEnabled(el)) {
            initializeField(el);
        }
    });

    // Handle initial elements or those added without focus
    function initAll() {
        var nodes = document.querySelectorAll('input, textarea');
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (isUrduEnabled(el)) {
                initializeField(el);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Expose for dynamic content added via JS
    window.setupPhoneticUrdu = initAll;

})();
</script>
