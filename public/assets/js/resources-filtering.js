$(document).ready(function(){

	// RESOURCES FILTERING
	// RESOURCES FILTERING
    // Resources Categories Filters - AJAX loading.  
    var historySupport = !!(window.history && history.pushState);

    // Add an event listener for filter clicks
    $(function () {
      $('body').on('click', '.resources-filters a.filter', onFilterClick);
    });

    // Event handler for the filter click
    function onFilterClick (e) {
      e.preventDefault();
      var $filter = $(e.currentTarget);
      var href = $filter.attr('href');
      if (historySupport) {
        // Update the browser's address bar
        history.pushState(null, null, href);  
      }
      // Make the container look like its loading 
      $('.grid-container').addClass('ajax-loading');

      $.ajax($filter.attr('href'), {
        dataType : 'html',
        success : function (response) {
          refreshProjects(response);
        }
      });
    }
	
    function refreshProjects (html) {
      // Update the <title> tag
      document.title = $(html).filter('title').text();
      $('.grid-container').removeClass('ajax-loading');
      
      // Update the .projects DOM element with new HTML
      var $html = $('<div />').append(html);
      $('.grid-container').html($html.find('.grid-container').html());

      $('.resources-filters').html($html.find('.resources-filters').html());
      
      // Fix lazy loading images not loading once Ajax finishes
	  // Lozad - Lazy Loading
		const observer = lozad('.lozad', {
			threshold: 0,
			loaded: function(el) {
				el.classList.add('fade');
			}
		});
		observer.observe();
		}

		$(window).bind("popstate", function() {
			
			// Make the container look like its loading 
			$('.grid-container').addClass('ajax-loading');
	  
			$.ajax($(location).attr('href'), {
			  dataType : 'html',
			  success : function (response) {
				refreshProjects(response);
			  }
			});
		  });


});
// End of document ready
