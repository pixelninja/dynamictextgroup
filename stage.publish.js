(function($) {

	// Language strings
	Symphony.Language.add({
		'Browse': false,
		'Create New': false,
		'Remove Item': false,
		'No items found.': false
	});
	
	/**
	 * Stage is a JavaScript utility for Symphony 
	 * which adds a multiselect interface to the backend.
	 *
	 * @author: Nils Hörrmann, post@nilshoerrmann.de
	 * @source: http://github.com/nilshoerrmann/stage
	 */
	$(document).ready(function() {

		// Initialize Stage
		$('div.stage').each(function() {
			var stage = $(this),
				selection = stage.find('ul.selection'),
				templates = stage.find('li.template').remove(),
				empty = stage.find('li.empty').remove(),			
				items = stage.find('li'),
				queue = $('<div class="queue" />'),
				index;

			// Handle empty stage
			if(empty.size() == 0) {
				empty = templates.filter('.create');
			}
			if(items.size() == 0) {
				selection.append(empty);
			}

			// Add constructors
			if(stage.is('.constructable')) {
				$('<a class="create">' + Symphony.Language.get('Create New') + '</a>').appendTo(queue);
			}

			// Add destructors
			if(stage.is('.destructable')) {
				var destructor = $('<a class="destructor">' + Symphony.Language.get('Remove Item') + '</a>');
				items.append(destructor);
				
				// It's possible that the empty message is a create template
				if(empty.is('.template.create')) {
					empty.append(destructor);
				}
			}
	
			// Add search field
			if(stage.is('.searchable')) {
				$('<input type="search" placeholder="' + Symphony.Language.get('Browse') + ' &#8230;" class="browser" value="" />').appendTo(queue);
			}

			// Add queue
			if(queue.children().size() > 0) {
				selection.after(queue);
			}
		
			// Store templates:
			// This is needed for other script that interact with Stage
			stage.data('templates.stage', {
				templates: templates,
				empty: empty			
			});

		/*-----------------------------------------------------------------------*/
		
			// Construction
			stage.delegate('a.create', 'click', function(event) {
				event.preventDefault();
				event.stopPropagation();
				
				// Create new item
				construct();			
			});

			// Destruction
			stage.delegate('a.destructor', 'click', function(event) {
				event.preventDefault();
				event.stopPropagation();

				// Find and destruct item
				var item = $(this).parents('li');
				item.trigger('destruct');
			});
			stage.delegate('li', 'destruct', function() {
				var item = $(this);
				destruct(item);
			});
			
			// Selecting
			queue.delegate('li', 'click choose', function() {
				var item = $(this);
				choose(item);
			});
			
			// Queuing
			stage.delegate('.browser', 'click', function() {
				stage.trigger('browsestart');
			})
			$('body').one('click', function() {
				stage.trigger('browsestop');
			});
			stage.bind('browsestart', function() {
				queue.find('ul').slideDown('fast');
			});
			stage.bind('browsestop', function() {
				queue.find('.browser').val('');
				queue.find('ul').slideUp('fast');
			});
			
			// Searching
			stage.delegate('.browser', 'click keyup', function(event) {
				var strings = $.trim($(event.target).val()).toLowerCase().split(' ');

				// Searching
				if(strings.length > 0 && strings[0] != '') {
					stage.trigger('searchstart', [strings]);
				}
								
				// Not searching 
				else {
					stage.trigger('searchstop');
					stage.trigger('browsestart');
				}
			});
			stage.bind('searchstart', function(event, strings) {
				search(strings);			
			});
					
		/*-----------------------------------------------------------------------*/

			// Construct an item
			var construct = function(item) {
				stage.trigger('constructstart', [item]);
				selection.addClass('constructing');

				// Remove empty selection message
				empty.slideUp('fast', function() {
					empty.remove();
				});
				
				// Existing item
				if(item) {
					item = item.clone().hide().appendTo(selection);
					items = items.add(item);
				}
				
				// New item
				else {
					item = templates.filter('.create').clone().removeClass('template create empty').hide().appendTo(selection);
					items = items.add(item);
				}
				
				// Add destructor
				if(stage.is('.destructable')) {
					$('<a class="destructor">' + Symphony.Language.get('Remove Item') + '</a>').appendTo(item);
				}
				
				// Destruct other items in single mode
				if(stage.is('.single')) {
					items.not(item).trigger('destruct');
				}
				
				// Sync queue
				queue.find('li[data-value="' + item.attr('data-value') + '"]').trigger('choose');
				
				// Show item
				item.appendTo(selection).slideDown('fast', function() {
					selection.removeClass('constructing');
					stage.trigger('constructstop', [item]);
				});
			};

			// Destruct an item
			var destruct = function(item) {
				stage.trigger('destructstart', [item]);
				selection.addClass('destructing');

				// Remove item
				item.slideUp('fast', function() {
					item.remove();
					items = items.not(item);
				});
				
				// Update queue
				queue.find('li[data-value=' + item.attr('data-value') + ']').removeClass('selected');
				
				// Check selection size
				if(items.not(item).size() == 0 && !selection.is('.constructing')) {
					
					// It's possible that the empty message is a create template
					if(empty.is('.template.create')) {
						var empty_item = empty.clone().appendTo(selection).slideDown('fast').removeClass('template create empty');
						items = items.add(empty_item);
					}
					else {
						empty.appendTo(selection).slideDown('fast');
					}
				}
				
				// Sync queue
				queue.find('li[data-value="' + item.attr('data-value') + '"]').trigger('choose');

				selection.removeClass('destructing');
				stage.trigger('destructstop', [item]);
			};
			
			// Choose an item in the queue
			var choose = function(item) {
				stage.trigger('choosestart', [item]);
				
				// Deselect
				if(item.is('.selected')) {
				
					// Destruct item
					if(object.is('.destructable')) {
						item.removeClass('selected');
						selection.find('li[data-value="' + item.attr('data-value') + '"]').trigger('destruct');
					}
				}
				
				// Select
				else {
				
					// Construct item	
					if(object.is('.constructable')) {
						item.addClass('selected');
						item.trigger('construct');
					}

					// Single selects
					if(object.is('.single')) {
						items.not(item).trigger('destruct');
					}
				}
				
				stage.trigger('choosestop', [item]);
			}
				
			// Search the queue
			var search = function(string) {
				var queue_items = queue.find('li');

				// Build search index
				if(!index) {
					index = queue.find('li').map(function() {
						return $(this).text().toLowerCase();
					});
				}
				
				// Search
				index.each(function(index, content) {
					var found = true,
						current = queue_items.filter(':nth(' + index + ')');

					// Items have to match all search strings
					$.each(strings, function(index, string) {
						if(content.search(string) == -1) {
							found = false;
						}
					});
				
					// Show matching items
					if(found) {
						current.addClass('found').slideDown('fast');
					}

					// Hide other items
					else {
						current.slideUp('fast');
					}
				});

				// Found
				if(queue_items.filter('.found').size() > 0) {
					queue_items.removeClass('found');
					stage.trigger('searchfound');
				}

				// None found
				else {
					stage.trigger('searchnonfound');
				}
			}
					
		});

	});
	
	
})(jQuery.noConflict());
