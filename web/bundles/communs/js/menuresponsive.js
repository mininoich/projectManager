var urlCourant = $('select#menu-ppl').val();
var nomCourant = $('select#menu-ppl option[value="'+urlCourant+'"]').text();
$('select#sous-menu').prepend('<option value="'+urlCourant+'">Retour à '+nomCourant+'</option>');

$('select#menu-ppl').change(function() {
    if ($(this).val() !== 'javascript:void(0);') {
        window.location.href = $(this).val();
    }
});

$('select#sous-menu').change(function() {
    if ($(this).val() !== 'javascript:void(0);') {
        window.location.href = $(this).val();
    }
});

$('nav ul li a').tooltip({placement: 'auto top'});
$('nav').css({position: 'relative'});
