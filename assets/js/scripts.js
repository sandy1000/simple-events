(function($) {

  $('#simple_event_filters select,#simple_event_filters input').on('change', function() {
      let eventType = $('#simple_event_filters select').val();
      let eventDate = $('#simple_event_filters input').val();
      let ajax_url = pt_site_global.ajax_url;
      // console.log(eventType + ' ' + eventDate + ' ' + pt_site_global.ajax_url);

      $.ajax({
        url: ajax_url,
        type: "post",
        data: {'eventType':eventType,'eventDate':eventDate,'action':'getFilteredEventList'},
        dataType: 'json',
        beforeSend: function() {
            
        },
        success: function(response) {
          console.log(response);

            if ( response.data.message ) {
                var messageType = response.success ? 'updated' : 'error';
                var messageHtml = response.data.message;

                $('.simple_event_filters').empty().append(messageHtml);
            }

        },
        error: function (xhr, textStatus, errorThrown) {
            if ( xhr.status != 200 ) {
                console.log( xhr.status + ' (' + xhr.statusText + ')' );
            }
        }
    });

    return false;

  });

})(jQuery);