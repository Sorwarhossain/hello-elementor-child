/*
 * NavPoints
 * https://github.com/Ins-V/navpoints
 *
 * Author: Vitaliy Gusarev
 *
 * Licensed under the MIT license.
 */
;(function($) {
	var settings = {
		speed: 300,
		offset: 0,
		currentClass: 'active',
		updateHash: false,
		classToParent: false
	}

	var NavPoints = {
		init: function(options, navbar) {
			settings = $.extend(settings, options);
			this.$navbar = $(navbar);
			this.$items = this.$navbar.find('a:not(.externals)');
			this.$window = $(window);
			this.hashNow = undefined;

			this.$items.on('click', this.clicked);
			this.$window.on('scroll', $.proxy(this.scrollPage, this));
			this.$window.on('resize', $.proxy(this.scrollPage, this));
		},
		clicked: function(e) {
			e.preventDefault();
			var hash = $(this).attr('href');

			if(!$(hash).length){
				return;
			}
			var position = $(hash).offset().top - settings.offset;

			$('html, body').animate({
				scrollTop: position
			}, settings.speed);
		},
		scrollPage: function() {
			var self = this,
				sections = [],
				result = undefined;

			self.$items.each(function() {
				var hash = $(this).attr('href');
				var section = $(hash);

				if (section) sections.push(section);
			});

			for (var i = 0; i < sections.length; i++) {
				var section = sections[i];

				if(!section.length){
					break;
				}

				var sectionHeight = section.height(),
					sectionTop = section.offset().top,
					windowTop = self.$window.scrollTop(),
					windoHeight = self.$window.height();

				if (windowTop <= sectionTop && (sectionHeight + sectionTop) < (windowTop + windoHeight)) {
					result = sections[i].attr('id');
					break;
				}
			}

			if (result) {
				if (settings.updateHash) {
					var hash = '#' + result;
					if (self.hashNow != hash) {
						self.hashNow = hash;
						history.pushState({}, '', hash);
					}
				}

				if (settings.classToParent) {
					self.$items.parent().removeClass(settings.currentClass);
					$('a[href="#' + result + '"]').parent().addClass(settings.currentClass);
				}
				else {
					self.$items.removeClass(settings.currentClass);
					$('a[href="#' + result + '"]').addClass(settings.currentClass);
				}
			}
		}
	}

	$.fn.navpoints = function(options) {
		this.each(function() {
			var navpoints = Object.create(NavPoints);
			navpoints.init(options, this);
		});
	}


	function isElement(obj) {
		try {
		  //Using W3 DOM2 (works for FF, Opera and Chrome)
		  return obj instanceof HTMLElement;
		}
		catch(e){
		  //Browsers not supporting W3 DOM2 don't have HTMLElement and
		  //an exception is thrown and we end up here. Testing some
		  //properties that all elements have (works on IE7)
		  return (typeof obj==="object") &&
			(obj.nodeType===1) && (typeof obj.style === "object") &&
			(typeof obj.ownerDocument ==="object");
		}
	}

})(jQuery);
