$(document).ready(function() {
    $('#fiscal_year_user').change(function() {
        let fiscalYearId = $(this).val();
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('fiscal_year', fiscalYearId);
        window.location.href = currentUrl.toString();
    });
});
