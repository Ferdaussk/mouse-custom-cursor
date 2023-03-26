(function ($) {
    'use strict';
    var getElementSettings = function ($element) {
		var elementSettings  = {},
			modelCID         = $element.data('model-cid');
		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];
			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if (settingsKeys == null) {
					return;
				}
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}
		return elementSettings;
	};
	var isEditMode		= false;
	var CustomCursorHandler  = function ($scope, $) {
		var elementSettings    = getElementSettings( $scope ),
			custom_cursor_enable = elementSettings.mcustomc_custom_cursor_enable,
			columnId             = $scope.data('id'),
			cursorType           = elementSettings.mcustomc_custom_cursor_type,
			cursorIcon           = elementSettings.mcustomc_custom_cursor_icon,
			cursorText           = elementSettings.mcustomc_custom_cursor_text,
			cursorText2          = elementSettings.mcustomc_custom_cursor_text2,
			cursorTarget         = elementSettings.mcustomc_custom_cursor_target;

		if ( 'yes' === custom_cursor_enable ) {
			var selector  = ".elementor-element-" + columnId,
				$selector = $(".elementor-element-" + columnId);
			if ( 'selector' === cursorTarget ) {
				selector = elementSettings.mcustomc_custom_cursor_css_selector,
				$selector = $scope.find(selector);
			}
			if ( 'follow-image' === cursorType ) {
				$("#style-" + columnId).remove();
				if ( cursorIcon.url === undefined || cursorIcon.url === '' ) {
					return;
				}
				$scope.append('<img src="' + cursorIcon.url + '" alt="Cursor Image" class="mcustomc-cursor-pointer">');
				$selector.mouseenter(function() {
					$(".mcustomc-custom-cursor").removeClass("mcustomc-cursor-active");
					$scope.addClass( "mcustomc-cursor-active" );
					$(document).mousemove(function(e){
						$('.mcustomc-cursor-pointer',this).offset({
							left: e.pageX + 0,
							top: e.pageY + 0
						});
					});
				}).mouseleave(function() {
					$scope.removeClass( "mcustomc-cursor-active" );
				});
			} else if ( 'img-coursor' === cursorType ) {
				$("#style-" + columnId).remove();
				if ( cursorIcon.url === undefined || cursorIcon.url === '' ) {
					return;
				}
				console.log(cursorIcon.url);
				var style = document.createElement('style');
				style.innerHTML = '.elementor-element:has(.mcustomc-img-cursor) .elementor-widget-container{ cursor: url(' + cursorIcon.url + '), auto; }';
				document.head.appendChild(style);
				$scope.append('<div class="mcustomc-cursor-pointer mcustomc-img-cursor"></div>');
				$selector.mouseenter(function() {
					$(".mcustomc-custom-cursor").removeClass("mcustomc-cursor-active");
					$scope.addClass( "mcustomc-cursor-active" );
					var cursor = $scope.find('.mcustomc-cursor-pointer'),
						width  = cursor.outerWidth(),
						height = cursor.outerHeight();
					$(document).mousemove(function(e){
						cursor.offset({
							left: e.pageX + 0 - (width/2),
							top: e.pageY + 0 - (height/2)
						});
					});
				}).mouseleave(function() {
					$scope.removeClass( "mcustomc-cursor-active" );
				});
			} else if ( 'follow-text' === cursorType ) {
				$("#style-" + columnId).remove();
				$scope.append('<div class="mcustomc-cursor-pointer mcustomc-cursor-pointer-text">' + cursorText + '</div>');
				$selector.mouseenter(function() {
					$(".mcustomc-custom-cursor").removeClass("mcustomc-cursor-active");
					$scope.addClass( "mcustomc-cursor-active" );
					var cursor = $scope.find('.mcustomc-cursor-pointer'),
						width  = cursor.outerWidth(),
						height = cursor.outerHeight();
					$(document).mousemove(function(e){
						cursor.offset({
							left: e.pageX + 0 - (width/2),
							top: e.pageY + 0 - (height/2)
						});
					});
				}).mouseleave(function() {
					$scope.removeClass( "mcustomc-cursor-active" );
				});
			} else if('mcustomc_cursor_icon' === cursorType){
				$("#style-" + columnId).remove();
				$scope.append('<div class="mcustomc-cursor-pointer mcustomc-cursor-pointer-text"><i class="' + cursorText2 + '"></i></div>');
				$selector.mouseenter(function() {
					$(".mcustomc-custom-cursor").removeClass("mcustomc-cursor-active");
					$scope.addClass( "mcustomc-cursor-active" );
					var cursor = $scope.find('.mcustomc-cursor-pointer'),
						width  = cursor.outerWidth(),
						height = cursor.outerHeight();
					$(document).mousemove(function(e){
						cursor.offset({
							left: e.pageX + 0 - (width/2),
							top: e.pageY + 0 - (height/2)
						});
					});
				}).mouseleave(function() {
					$scope.removeClass( "mcustomc-cursor-active" );
				});
			}
		} else {
			$("#style-" + columnId).remove();
		}
	};
	$(window).on('elementor/frontend/init', function () {
	if ( elementorFrontend.isEditMode() ) {
		isEditMode = true;
	}
		elementorFrontend.hooks.addAction('frontend/element_ready/global', CustomCursorHandler);
	});
}(jQuery));
