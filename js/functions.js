/*******************************************************************************
Title:  functions.js (bsv-buelach.ch backend)
Update: 22.12.2009
*******************************************************************************/

$(document).ready(function() {
	$('a.delete').click(function() {
    return confirm("Wirklich löschen? - Mit OK bestätigen.");
  });
  $('.herstellen').click(function() {
    return confirm("Möchten Sie den Eintrag wirklich wieder herstellen?\Mit OK bestätigen.");
  });
  $('.cancel').click(function() {
    return confirm("Möchten Sie wirklich abbrechen?\nBitte mit OK bestätigen.");
  });
   $('.external').click(function() {
    open(this.href);
		return false;
  });
});

$(document).ready(function() {
  $('<li class="checkall"></li>').html('<label><input type="checkbox" id="discover-all" />' + ' <em>Alle markieren</em></label>').prependTo('dl#jprogrammlist dd > ul');
  $('#discover-all').click(function() {
    var $checkboxes = $(this).parents('ul:first').find(':checkbox');
    if (this.checked) {
      $(this).next().text(' Markierung aufheben');
      $checkboxes.attr('checked', true);
} else {
  $(this).next().text('Alle markieren');
  $checkboxes.attr('checked', '');
}
  });
});
