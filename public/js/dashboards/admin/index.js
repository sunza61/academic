$(document).ready(function() {
    console.log("Admin dashboard script loaded.");

    $('#fiscal_year').change(function() {
        console.log("Fiscal year changed to: " + $(this).val());
        
        let fiscalYearId = $(this).val();
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('fiscal_year', fiscalYearId);
        window.location.href = currentUrl.toString();
    });
});
