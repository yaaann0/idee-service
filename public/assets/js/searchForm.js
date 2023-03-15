/* $(document).ready(function() {
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
}); */

$('#weeksheet_search_to, #weeksheet_search_from').on('change', function() {
    const from = new Date($('#weeksheet_search_from').val());
    const to = new Date($('#weeksheet_search_to').val());
    if(( from - to ) > 0) {
        $('#weeksheet_search_to')[0].setCustomValidity('Merci de renseigner un interval valide');
    } else {
        $('#weeksheet_search_to')[0].setCustomValidity('')
    }
})
