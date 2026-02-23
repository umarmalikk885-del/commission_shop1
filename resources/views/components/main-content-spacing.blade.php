<style>
    /* Main Content Spacing - Accounts for 260px sidebar */
    .main {
        margin-left: 260px;
        width: calc(100% - 260px);
        box-sizing: border-box;
    }
    
    /* In RTL we put the sidebar on the right */
    [dir="rtl"] .main {
        margin-left: 0;
        margin-right: 260px;
    }
    
    @media (max-width: 768px) {
        .main {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }
    }
</style>
