!function(t){"use strict";t.fn.fitVids=function(e){var i={customSelector:null};if(!document.getElementById("fit-vids-style")){var r=document.createElement("div"),a=document.getElementsByTagName("base")[0]||document.getElementsByTagName("script")[0],o="&shy;<style>.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>";r.className="fit-vids-style",r.id="fit-vids-style",r.style.display="none",r.innerHTML=o,a.parentNode.insertBefore(r,a)}return e&&t.extend(i,e),this.each(function(){var e=["iframe[src*='player.vimeo.com']","iframe[src*='youtube.com']","iframe[src*='youtube-nocookie.com']","iframe[src*='kickstarter.com'][src*='video.html']","object","embed"];i.customSelector&&e.push(i.customSelector);var r=t(this).find(e.join(","));r=r.not("object object"),r.each(function(){var e=t(this);if(!("embed"===this.tagName.toLowerCase()&&e.parent("object").length||e.parent(".fluid-width-video-wrapper").length)){var i="object"===this.tagName.toLowerCase()||e.attr("height")&&!isNaN(parseInt(e.attr("height"),10))?parseInt(e.attr("height"),10):e.height(),r=isNaN(parseInt(e.attr("width"),10))?e.width():parseInt(e.attr("width"),10),a=i/r;if(!e.attr("id")){var o="fitvid"+Math.floor(999999*Math.random());e.attr("id",o)}e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*a+"%"),e.removeAttr("height").removeAttr("width")}})})}}(window.jQuery||window.Zepto);

/*!
 * Yeahthemes
 *
 * Custom Javascript
 */
if (typeof Yeahthemes == 'undefined') {
	var Yeahthemes = {};
}

;(function($) {
	
	"use strict";
	
	if (typeof Yeahthemes == 'undefined') {
	   return;
	}
	//http://stackoverflow.com/questions/17104265/caching-a-jquery-ajax-response-in-javascript-browser
	// var localCache = {
	//    /**
	//      * timeout for cache in millis
	//      * @type {number}
	//      */
	//     timeout: 30000,
	//     /** 
	//      * @type {{_: number, data: {}}}
	//      **/
	//     data: {},
	//     remove: function (url) {
	//         delete localCache.data[url];
	//     },
	//     exist: function (url) {
	//         return !!localCache.data[url] && ((new Date().getTime() - localCache.data[url]._) < localCache.timeout);
	//     },
	//     get: function (url) {
	//         console.log('Getting in cache for url' + url);
	//         return localCache.data[url].data;
	//     },
	//     set: function (url, cachedData, callback) {
	//         localCache.remove(url);
	//         localCache.data[url] = {
	//             _: new Date().getTime(),
	//             data: cachedData
	//         };
	//         if ($.isFunction(callback)) callback(cachedData);
	//     }
	// }
	/**
	 * Yeahthemes.Elegance
	 *
	 * @since 1.0
	 */
	Yeahthemes.Elegance = {
		
		std:{},
		
		/**
		 * Init Function
		 * @since 1.0
		 */
		init: function(){
			
			var self = this,
				_framework = Yeahthemes.Framework;
				
			this._vars = Yeahthemes.themeVars;

			/*Prevent fast clicking*/
			
			//console.log(Yeahthemes);

			var isScrolling = false,
				isStoppedScrolling = false,
				srollStopTimeout,
				fixMenuInterval,

				isTouch = _framework.helpers.isTouch(),
				$event = isTouch ? 'touch' : 'mouseover';
			
			/**
			 * On load
			 */
			$(window).on('load', function(){

				setTimeout( function(){
					
					self.ui.replaceScriptTemplate('[data-role=".footer-instagram-feed"]');
				}, 300);

				//if( !isTouch )
					window.addEventListener('scroll', onScroll, false);
				
			}).on('scroll', function(){

				isScrolling = true;
				//console.log("isScrolling = true");

				

			    
			    if ( isScrolling ) {
					//Code goes here
				}

				clearTimeout($.data(this, 'scrollTimer'));
			    $.data(this, 'scrollTimer', setTimeout(function() {
			        // do something
			        //console.log("isScrolling = false");
			        
			        isScrolling = false;
			    }, 250));
				


			}).on('resize', function(){

				if( typeof resizeTimeOut !== 'undefined' )
					clearTimeout(resizeTimeOut);

				var resizeTimeOut = setTimeout( function(){
				    // Haven't resized in 100ms!
				   
				    //console.log('called setTimeout')
				}, 100);
			}).on('srollStop', function(){
				
			});
			/**
			 * Attach the event handler functions for theme element
			 */

			 // For Lazy load
			$( 'body' ).on( 'post-load', lazy_load_init ); // Work with infinite scroll
			$( 'body' ).on( 'template-load', lazy_load_init ); // Work with Javascript template

			function lazy_load_init() {
				$( 'div[data-lazy-src]' ).on( 'scrollin', { distance: 200 }, function() {
					lazy_load_image( this );
				});
			}

			function lazy_load_image( el ) {
				var $el = jQuery( el ),
					src = $el.attr( 'data-lazy-src' );
					// console.log( src );

				if ( ! src || 'undefined' === typeof( src ) )
					return;

				$('<img src="' + src + '"/>').on( 'load',function() {
					var image = $(this);
					// console.log(image);

					$el.css( 'background-image', 'url(' + src + ')');
					$el.unbind( 'scrollin' ) // remove event binding
					
					.removeAttr( 'data-lazy-src' )
					.attr( 'data-lazy-loaded', 'true' );

					image.remove();
		    	})
				// $el.fadeIn();
			}

			$(document)
				
				.ready(function(){

					Yeahthemes.General.ui.bootstrap();

					$('.yeahslider:not(.initialized)').each(function(index, element) {
						var $el = $(this);
							// imgs = $('img', $el ),
							// imgsCount = imgs.length;

						/**
						 * Check if all images are loaded, init the slider
						 */
						/*imgs.load(function(){
							 imgsCount--;
							if (!imgsCount){
								setTimeout(function(){
									Yeahthemes.General.ux.yeahSlider( $el );
								},500);
							}
								
						});*/

						Yeahthemes.General.ux.yeahSlider( $el );
						
					});
					lazy_load_init();		

					// Wrap widget border title
					$( '.border-box.widget .widget-title' ).wrapInner( '<span></span>' );
					if(/* !$('body').hasClass( 'single') && */!$('body').hasClass( 'search-results') )
						self.ui.articleMetaInfo();

					// Target your .container, .wrapper, .post, etc.
		   			$('#primary, #secondary').fitVids();

		   			$('.post-page-navigation.direction-nav > [data-heading]').each(function(){

		   				var $el = $(this),
		   					$title = $('.pager-title', $el ),
		   					text = $el.data('heading');
		   					

		   				if( $('body').hasClass('single') ){
		   					$title.before('<span class="pager-heading">' + text + '</span>');
		   				}else{
		   					var firstWord = text.split(' ')[0],
			   					lastWord = text.replace( firstWord, '' ),
			   					newFirstW = '<span class="pager-heading">' + firstWord + '</span>',
								newLastW = '<span class="pager-title">' + lastWord + '</span>';

							$title.replaceWith( newFirstW + newLastW );
		   				}
		
					});

					if( !$('#mobile-main-menu').length ){
						
						var socialLinks = '',
							mTopMenu = $('.top-navigation ul.menu').clone(true),
							mMainMenu = $('.main-navigation ul.menu').clone(true),
							toggler = '<a href="javascript:void(0)" class="main-menu-toggle hidden-md hidden-lg"><span class="bar1"></span><span class="bar2"></span><span class="bar3"></span></a>';

						if( $('.site-top-menu-right .site-social-networks').length ){
							socialLinks = $('.site-top-menu-right .site-social-networks').html();
						}else if( $('.main-navigation .site-social-networks').length ){
							// Remove social media network
							socialLinks = $('.main-navigation .site-social-networks').html();
						}
						// remove social networks
						mMainMenu.find('.site-social-networks').remove();
						mTopMenu.find('.site-social-networks').remove();
						// Remove menu item logo
						mMainMenu.find('.menu-item-logo').remove();

						if( socialLinks )
							socialLinks = '<div class="site-social-networks gray-2-secondary">' + socialLinks + '</div>';

						mTopMenu.attr('class', 'menu').attr('id', 'mobile-top-menu' );
						mMainMenu.attr('class', 'menu').attr('id', 'mobile-main-menu' );

						$('<div id="mobile-menu-nav-wrapper" class="mobile-navigation hidden-md hidden-lg"></div>').insertAfter('.inner-wrapper');
						var mMenuWrapper = $('#mobile-menu-nav-wrapper');

						mMenuWrapper.append(socialLinks).append( mTopMenu ).append( mMainMenu ).append( toggler );
						
					}
					// setTimeout(function(){
					// 	self.ui.socialSharedCount.getCount();
					// },5000);
					
				})
				.on('click', '.main-menu-toggle', function(e){


					e.preventDefault();
					var $body = $('body');

					if( typeof $body.data( 'mobile-menu' ) == 'undefined' || 'closed' == $body.data( 'mobile-menu' ) ){
						$body.data( 'mobile-menu', 'opening' );
						$('body').addClass('active-mobile-menu');
					}else{						
						$body.data( 'mobile-menu', 'closed' );
						$('body').removeClass('active-mobile-menu');
					}

				}).on('click', '#mobile-menu-nav-wrapper .menu li:has(ul)', function(e){ 

					var thisLi = $(this);

					if( !thisLi.hasClass('active') ){
						if(thisLi.siblings('.active:has(ul)').length){
							thisLi.siblings('.active').find(' > ul').slideUp(function(){
								$(this).closest('li').removeClass('active');
							});
						}
						$('> ul', thisLi).slideToggle(function(){
							thisLi.toggleClass('active');
						});
						e.preventDefault();	
					}
					
				}).on('touchstart click', '.active-mobile-menu .inner-wrapper', function(e){

					var target = e.target,
						innnerWrapper = document.querySelector( '.inner-wrapper' ),
						$body = $('body');

					if( target == innnerWrapper ){

						$body.removeClass('active-mobile-menu');
						$body.data( 'mobile-menu', 'closed' );
						
					}
				})
				/*Smooth scroll for anchor*/
				.on('click', 'a[href*="#"]:not([role="tab"], [data-toggle="collapse"])', _framework.ui.smoothAnchor)

				.on('click', '.menu-item-gsearch', function(){
					var $el = $(this);

					if( $el.data('open') )
						return;

					if( $el.find('form[role="search"]').length ){
						$('form[role="search"]').fadeIn(300);
					}else{

		   				if( typeof $el.data('appended') !== 'undefined' )
		   					return;
		   				
		   				var	dataAppend = $el.find('[data-role=".menu-item-gsearch"]').html();

		   				
		   				//var newDataMeta = '';
		   				// Remove CDATA
		   				dataAppend = Yeahthemes.General.helpers.removeCDATA(dataAppend );

		   				//console.log(dataMeta);

		   				$(dataAppend).appendTo($el);

	   					$el.data('appended', true );

	   				}

	   				$el.find('[type="search"]').focus();
	   				// $el.find('form').css('right', $el.outerWidth() );
	   				// $('[data-role=".menu-item-gsearch"]').remove();
	   				// $('a i', $el ).removeClass($el.data('default')).addClass($el.data('close'));
	   				$el.data('open', true );


				} )
				.on('blur', '.menu-item-gsearch [type="search"]', function(){
					$(this).closest('form[role="search"]').fadeOut(300);
					// var $el = $('.menu-item-gsearch');
					// $('a i', $el ).removeClass($el.data('close')).addClass($el.data('default'));
					setTimeout(function(){
						$('.menu-item-gsearch').data('open', false );
					}, 500);
				})
				
				/*Hero Slider Fake control*/
				.on('click', '.site-hero .yeahslider .slides li', function(){

					var $el = $(this);
					if( $el.next().is('.yeahslider-active-slide') )
						$('.site-hero .yeahslider').flexslider('prev');
					else if( $el.prev().is('.yeahslider-active-slide') )
						$('.site-hero .yeahslider').flexslider('next');

				})
				/*Social Sharing*/
				.on('click', '.social-share-buttons > *', _framework.ui.socialSharing)
				
				/*Tabby tab*/
				.on('click', '.yt-tabby-tabs-header ul li', _framework.ui.tabbyTabs )
				/*Mailchimp*/
				.on('submit', 'form.yt-mailchimp-subscribe-form', _framework.widgets.mailchimpSubscription )

				// Infinite Scroll
				.on( 'post-load', function () {

					self.ui.articleMetaInfo();
					
					// setTimeout(function(){
					// 	self.ui.socialSharedCount.getCount();
					// },5000);

					if( $('body').hasClass('infinity-end')){
						var loader = $('.infinity-end .infinite-loader');
						if(load.length){
							setTimeout(function(){
								loader.remove();
							}, 3000);

						}
					}

					if( $('.yeahslider:not(.initialized)').length )
						Yeahthemes.General.ux.yeahSlider( $('.yeahslider:not(.initialized)') );
					
					/*Media element*/
					/*if( $( '.wp-audio-shortcode' ).length || $( '.wp-video-shortcode' ).length ){
						$('.wp-audio-shortcode:not(.mejs-container), .wp-video-shortcode:not(.mejs-container)').mediaelementplayer();
					}*/
					/*Media element*/
					if( $( 'article.format-video' ).length ){
						$( 'article.format-video' ).fitVids();
					}
					
				} )
				// Woocommerce
				.on( 'cart_page_refreshed', function( a){

					Yeahthemes.General.ui.bootstrap();					
				});/*end $(document)*/
				
			
			$(window).on('scroll', function (e) {
				// var scrollTop = $(this).scrollTop();
				
				// var delta = null;
				// if (e.wheelDelta){
				// 	//IE Opera
				// 	delta = e.wheelDelta;
				// }else if (e.originalEvent.detail){
				// 	//Firefox
				// 	delta = e.originalEvent.detail * -40;	
				// }else if (e.originalEvent && e.originalEvent.wheelDelta){
				// 	//Webkit
				// 	delta = e.originalEvent.wheelDelta;
				// }
				
				// if (delta >= 0) {
					
				// 	/*Scroll up*/
				// } else if (delta = 0) {
					
				// } else {
				// 	/*Scroll down*/
					
					
				// }
				
				/*if (t >= 0) {
					$('#site-banner').addClass('fixed');
				} else if (t = 0) {
					$('#site-banner').removeClass('fixed')
				} else {
					$('#site-banner').removeClass('fixed')
				}*/
			});
			
			//window.requestAnimationFrame(self.ux.stickyHeader);
			

			var lastScrollY     = 0,
		        ticking         = false;

			//Callback for our scroll event - just keeps a track on the last scroll value
		    function onScroll() {
		        lastScrollY = window.scrollY;
		        onScrollRequestTick();
		    }

		    //Calls rAF if it's not already been done already
		    
		    function onScrollRequestTick() {
		        if(!ticking) {
		            window.requestAnimationFrame(onScrollUpdate);
		            ticking = true;
		        }
		    }

		    //Our animation callback
		    function onScrollUpdate() {
		        //console.log('Code goes here');


		        /**
		          * Init infiniteScrollPostThumbnailWidget
		          */
		        if( $('[data-action="load-more-post"][data-role="milestone"]:not(.all-loaded)').length )
					$('[data-action="load-more-post"][data-role="milestone"]:not(.all-loaded)').each( Yeahthemes.General.ux.infiniteScrollPostThumbnailWidget );

				/**
				  * Init stickyHeader
		          */
		        if( $('body').hasClass( 'scroll-fix-header') )
					self.ux.stickyHeader();
				


		        // allow further rAFs to be called
		        ticking = false;
		    }
			
			//$('.site-headlines').endlessScroll();

			// function getTranslateCoordinate (value, coordinate) {
			// 	value = value.toString();
			// 	var coordinateValue = 0,
			// 		pattern = /([0-9-]+)+(?![3d]\()/gi , 
			// 		positionMatched = value.match( pattern );
			 
			// 	if (positionMatched.length) {
			// 		var coordinatePosition = coordinate == 'x' ? 0 : coordinate == 'y' ? 1 : 2;
			// 		coordinateValue = parseFloat( positionMatched[coordinatePosition] );
			// 	}
					   
			// 	return coordinateValue;
			// }
			
		},
		/**
		 * Setup
		 * @since 1.0
		 */
		setup: function(){
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ui:{
			/**
			 * text/html
			 * Trick the pagespeed
			 */
			replaceScriptTemplate: function( _selector  ){
				if( !$( _selector ).length )
					return;

				var	dataAppend = $(_selector ).html();
   				// Remove CDATA
   				dataAppend = Yeahthemes.General.helpers.removeCDATA(dataAppend );

		   		$( _selector ).replaceWith(dataAppend);
			},
			socialSharedCount: {
				getCount: function(){

					var urlJson = {
						
							//new FQL method by Sire
							facebook: '//graph.facebook.com/?id=',
						    twitter: '//cdn.api.twitter.com/1/urls/count.json?url=',
						    pinterest: '//api.pinterest.com/v1/urls/count.json?url='
						};
					$('.social-share-buttons.with-counter').each(function(){


						var $el = $(this),
							url = typeof $el.data('url') == 'undefined' || $el.data('url') == false ? $el.closest('[data-url]').data('url') : $el.data('url');

						url = encodeURIComponent( url );
					
						if( $el.data('init') )
							return;
						
						// if( $el.find('[data-service="facebook"]').length ){
						// // facebook
						// 	$.ajax({
						// 		dataType: 'jsonp',
						// 		url: urlJson.facebook + url ,
						// 		success: function(responses){
						// 			//responses.shares
						// 			$el.find('[data-service="facebook"]').find('.counter').text(Yeahthemes.General.helpers.shorterTotal( responses.shares ).replace(".", ",") ).show();
						// 		},
						// 		cache: true
						// 	});
						// }
						// twitter
						// if( $el.find('[data-service="twitter"]').length ){
						// 	$.ajax({
						// 		dataType: 'jsonp',
						// 		url: urlJson.twitter + url ,
						// 		success: function(responses){
						// 			//console.log(responses.count);
						// 			//responses.count
						// 			$el.find('[data-service="twitter"]').find('.counter').text( Yeahthemes.General.helpers.shorterTotal( responses.count ).replace(".", ",") ).show();
						// 		}
						// 	});
						// }

						// pinterest
						// if( $el.find('[data-service="pinterest"]').length ){
						// 	$.ajax({
						// 		dataType: 'jsonp',
						// 		url: urlJson.pinterest + url ,
						// 		success: function(responses){
						// 			if( responses && typeof responses.count != 'undefined' ){
						// 				$el.find('[data-service="pinterest"]').find('.counter').text( Yeahthemes.General.helpers.shorterTotal( responses.count ).replace(".", ",") ).show();
						// 			}
									


						// 		}
						// 	});
						// }

						$el.data('init', true);
					});

				}
			},
			articleMetaInfo: function(){
				// Read More + sharing button
				$('.site-content > article.post, .site-hero article.post').each(function(){
					var $el = $(this);
					
					if( typeof $el.data('appended') !== 'undefined' )
						return;


					if( $('body').hasClass('has-format-icon') && $el.is('.format-video') && typeof Yeahthemes.themeVars.postFormatVideoIcon !== 'undefined' && Yeahthemes.themeVars.postFormatVideoIcon){
						$el.find('.entry-thumbnail').append(Yeahthemes.themeVars.postFormatVideoIcon);
					}
					
					var target = $('body').hasClass('single') ? $('header.entry-header', $el) : $('footer.entry-meta', $el),
						shareContent = typeof Yeahthemes.themeVars.sharingButtons !== 'undefined' ? Yeahthemes.themeVars.sharingButtons : '' ,
						cat = $el.data('cat');

					if( !$('body').hasClass('single') ){
						if( $('.entry-header .entry-title', $el).length ){
							$(cat).insertBefore( $('.entry-header .entry-title', $el) );
						}else{
							if( $el.hasClass('layout-default') ){
								$('.entry-header', $el).prepend( cat );
							}
							else{
								$(cat).insertAfter( $('.entry-header .entry-thumbnail', $el) );
							}

						}
					}
					$el.find('a.more-tag').detach().prependTo(target);	
					if( shareContent )
						$(shareContent).prependTo( target );
					$el.data('appended', true);
					$el.removeAttr('data-share');
					$el.removeAttr('data-cat');

				});

				// Remove In cat
				$('.entry-meta .in-cat').remove();
			}
		},
		ux:{
			stickyHeader: function(){			

				var mainNav = $('.main-navigation');
				
				if( $('body').data('sHHO') == undefined ){
					var $mainNavOffset = mainNav.offset().top + mainNav.outerHeight();
					$('body').data('sHHO', $mainNavOffset );
				}

				//$('.site-top-menu').outerHeight() to $('.site-header').offset().top
				if ($(window).scrollTop() >= $('body').data('sHHO') ) {
			        $('body').addClass('sticky-header');

			        // Assign data height
			    	if( $('body').data('bannerH') == undefined || false == $('body').data('bannerH') ){
			    		$('body').data('bannerH', $('.site-banner').outerHeight());
			    	}

			    	// Set parent height
			    	$('.site-banner').css('height', $('body').data('bannerH') );	

			    	// Add Place holder
			        if( $('body').data('sHP') == undefined || false == $('body').data('sHP') ){
			    		$( '<div class="main-navigation-placeholder"></div>' ).css({
			    			'height': mainNav.outerHeight(),
			    			'clear': 'both'

			    		}).insertAfter( '.main-navigation' );
			    		$('body').data('sHP', true);
			    	}    	
			    	// Wrap the nav
			    	if( $('body').data('sHW') == undefined || false == $('body').data('sHW') ){

			    		mainNav/*.addClass('container')*/.wrap('<div class="sticky-main-nav-wrapper"><div class="sticky-main-nav-inner container"></div></div>');

			    		$('body').data('sHW', true);
			    	}

			    	if( ( $('body').data('sHLG') == undefined || false == $('body').data('sHLG') ) && !mainNav.find('.site-social-networks').length ){
			    		
			    		var siteTitle = $('.site-branding .site-title').clone();
			    		$(siteTitle).removeAttr('class').addClass('sticky-branding hidden-md').prependTo('.sticky-main-nav-inner');
			    		
			    		$('body').data('sHLG', true);
			    	}


					// if is image logo, set it as background of sticky nav
			    	// if( $('.site-logo.image-logo img').length )
				    // 	$('.sticky-main-nav-wrapper').css({
				    // 		'background-image' : 'url(' + $('.site-logo.image-logo img').attr('src') + ')'
				    // 		// ,'background-repeat': 'no-repeat',
				    // 		// 'background-position': '20px center',
				    // 		// 'background-size': 'auto 40px'

				    // 	});
				    // if has admin bar, get offset top of nav wrapper
			        if( $('body').hasClass('admin-bar') )
			        	$('.sticky-main-nav-wrapper').css('top', $('body').offset().top);

			    }
			    // Release Nav
			    else {

			        $('body').removeClass('sticky-header');
			        mainNav.removeAttr('style');
			        $('.site-banner').css('height', '' );
			        $('.main-navigation-placeholder').remove();
			        $('.sticky-main-nav-wrapper .sticky-branding').remove();
			        if ( mainNav.parent().is( '.sticky-main-nav-inner' ) )
			        	mainNav/*.removeClass('container')*/.unwrap();
			        if ( mainNav.parent().is( '.sticky-main-nav-wrapper' ) ) {
						mainNav/*.removeClass('container')*/.unwrap();

						$('body').data('sHW', false);
						$('body').data('sHP', false);
						$('body').data('sHLG', false);
					}
			    }
			},
			
		}
		
		
	}
	
	/**
	 * Yeahthemes.General
	 *
	 * @since 1.0
	 */
	Yeahthemes.General = {
		ui: {
			bootstrap: function(){
				$('[type="submit"]').addClass( 'btn btn-primary' );
				$('[type="reset"], [type="button"], button:not([type]), .button:not([type="submit"])').addClass( 'btn btn-default' );
				$('').addClass( 'btn btn-default' );
				$('[type="text"], [type="password"], [type="search"], [type="email"], [type="number"], textarea').addClass('form-control');
				
			}
		},
		ux:{
			
			/**
			 * Infinite Scroll
			 * @since 1.0
			 */
			infiniteScrollPostThumbnailWidget:function() {
				// if( Yeahthemes.Framework._eventRunning ){
				// 	return;
				// }


				var $el = $(this);
				//Restrict request for each widget.
				if( $el.data('event-running') == undefined )
					$el.data('event-running', 0);

				if( 1 == parseInt( $el.data('event-running') ) ){
					return;
				}

				if( $el.is(':visible')){

					//console.log('is_visible');
					var content_offset = $el.offset();
					if ( window.pageYOffset >= Math.round(content_offset.top - (window.outerHeight + 150) ) ) {

					
						var $post_list = $el.siblings('.post-list-with-thumbnail'),
							dataSettings = $post_list.data('settings'),
							offset = $post_list.data('offset') !== undefined ? parseInt( $post_list.data('offset') ) : parseInt( dataSettings.offset );

							dataSettings.offset = offset;
							console.log( 'offset:' + dataSettings.offset );

						var erunning = parseInt( $el.data('event-running') );
						$el.data('event-running', erunning+1 );
						console.log( 'event runnin:' + erunning);

						//Yeahthemes.Framework._eventRunning = true;
						$el.addClass( 'yt-loading' );
						//console.log(dataSettings);
				    	$.ajax({
							type: 'GET',
							url: Yeahthemes._vars.ajaxurl,
							data: {
								action: 'yt-site-ajax-load-posts-infinitely',
								data: dataSettings
							},
							success: function(responses){
								//console.log(responses);
								if(responses && !responses.error ){
									if( !responses.all_loaded ){
										$post_list.append( responses.html );
										$el.removeClass('yt-loading');
									}
									
									// if( responses.offset )
									// 	$post_list.data('offset', parseInt( responses.offset ) );

									if( responses.all_loaded ){
										$el.addClass('all-loaded').removeClass('yt-loading').html( responses.html );
										setTimeout( function(){ $el.fadeOut(function(){$(this).remove()}); }, 3000 );
									}
								}
								$(document.body).trigger('post-load', responses);
								//console.log(document.body);

								//Yeahthemes.Framework._eventRunning = false;

								erunning = parseInt( $el.data('event-running') );
								$el.data('event-running', erunning-1 );
							}
						});
				    	$post_list.data('offset', parseInt( offset + parseInt( dataSettings.number ) ) );


					}
				}

			},
			yeahSlider: function( $el ){
				var yeahSliderDefaultSettings = {
						namespace: 'yeahslider-',
						animation: 'slide',
						init: function(slider) {

							slider.addClass('initialized');

							slider.on('mouseenter', function(){
								$(this).addClass('hover');
							});

							$(slider).on('mouseleave', function(){
								var el = $(this);
								setTimeout( function(){
									el.removeClass('hover');
								}, 1000);
							});
							//console.log(slider);
						},
						start: function(slider){ 
						},
						before: function(slider){
							var current = slider.currentSlide == slider.count - 1 ? 0 : slider.currentSlide+1,
								css3Effect = slider.vars.css3Effect;
							if( css3Effect )
								slider.slides.removeClass( css3Effect ).eq( current).addClass( css3Effect );
						},
						after: function(slider){
						}
					},
					yeahSliderCustomSettings = $el.data('settings') !== undefined ? $el.data('settings') : {},
					yeahSliderSettings = $.extend(yeahSliderDefaultSettings, yeahSliderCustomSettings);
					/*Init*/
					$el.flexslider(yeahSliderSettings);
					//console.log('init');
			}
			
		},
		helpers: {
			removeCDATA: function(dataAppend){
				dataAppend = dataAppend.replace( '//<![CDATA[', '' );
   				dataAppend = dataAppend.replace( '/*<![CDATA[*/', '' );
   				dataAppend = dataAppend.replace( '/* <![CDATA[ */', '' );
   				dataAppend = dataAppend.replace( '<![CDATA[', '' );

   				dataAppend = dataAppend.replace( '//]]>', '' );
   				dataAppend = dataAppend.replace( '/*]]>*/', '' );
   				dataAppend = dataAppend.replace( '/* ]]> */', '' );
   				dataAppend = dataAppend.replace( ']]>', '' );

   				return dataAppend;
			},
			shorterTotal: function (num) {
				if (num >= 1e9){
					num = (num / 1e9).toFixed(3) + "B";
				} else if (num >= 1e6){
					num = (num / 1e6).toFixed(2) + "M";
				} else if (num >= 1e3){ 
					num = (num / 1e3).toFixed(1) + "k";
				}
				if( num )
					return num.toString();
				else
					return '0';
				
			}
		}
	};
	
	/**
	 * Yeahthemes.Framework
	 *
	 * @since 1.0
	 */
	Yeahthemes.Framework = {
		init:function(){

			this._eventRunning 	= false;
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ux:{
			
			tapToTop: function( e ){
				var $el = $(this);
				
				if( $el.attr('id') == undefined )
					return
				if( e.target.id != $el.attr('id') )
					return;

				$("html, body").animate({scrollTop:0},"fast")
				
			}
		},
		/**
		 * Ui
		 * @since 1.0
		 */
		ui:{
			/**
			 * socialSharing
			 * @since 1.0
			 */
			socialSharing:function(e){
				
				e.preventDefault();
			
				var $el 	= $(this),
					service = $el.data('service'),
					wrapper = $el.closest('.social-share-buttons');

				if( typeof wrapper.data('url') == 'undefined' || wrapper.data('url') == false )
					wrapper = wrapper.closest('[data-url]');

				var w		= 560,
					h		= 350,
					x		= Number((window.screen.width-w)/2),
					y		= Number((window.screen.height-h)/2),
					url 	= encodeURIComponent( wrapper.data('url') ),
					source 	= encodeURIComponent( wrapper.data('source') ),
					media 	= typeof wrapper.data('media') !== 'undefined' ? encodeURIComponent( wrapper.data('media') ) : '',
					via 	= typeof $el.data('via') !== 'undefined' ? $el.data('via') : '',
					title 	= wrapper.data('title'),



					href = '';
					title 	= encodeURIComponent( title );

					title 	= via ? title + '&via=' + encodeURIComponent( via ) : title;
				
				if( 'twitter' === service ){
					href = '//twitter.com/intent/tweet?url=' + url + '&text=' + title;
				}else if( 'facebook' === service ){
					href = '//www.facebook.com/sharer/sharer.php?u=' + url;
				}else if( 'google-plus' === service ){
					href = '//plus.google.com/share?url=' + url;
				}else if( 'linkedin' === service ){
					href = '//www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title + '&source=' + source;
				}else if( 'pinterest' === service ){
					href = '//pinterest.com/pin/create/button/?url=' + url + ( media ? '&media=' + media : '') + "&description=" + title;
				}else if( 'tumblr' === service ){
					href = '//www.tumblr.com/share/photo?source='+ media +'&caption=' + title + '&clickthru=' + url;
				}else if( 'stumble-upon' === service ){
					href = '//www.stumbleupon.com/badge/?url='+ url;
				}else if( 'email' === service ){
					href = 'mailto:?subject=' + title + ' ' + url;
				}
				
				if( 'more' !== service ){
					window.open( href,'','width=' + w + ',height=' + h + ',left=' + x + ',top=' + y + ', scrollbars=no,resizable=no');
				}else{
					$el.siblings('[data-show="false"]').toggleClass('hidden');
				}
				
				//console.log(wrapper.data('title'));
			},
			
			/**
			 * socialSharing
			 * @since 1.0
			 */
			smoothAnchor: function(e){
				var $el = $(this),
					target = window.location.href.split('#'),
					currentUrl = $el.attr('href').split('#'),
					id = typeof currentUrl[1] == 'undefined' ? '' : currentUrl[1];
				
				if( ( currentUrl[0] == target[0] || '' == currentUrl[0]) && $( '#' + id ).length ){
					
					$('html, body').animate({
						scrollTop: $( '#' + id ).offset().top
					}, 800);
					
					e.preventDefault();
				}
			},
			/**
			 * tabbyTabs
			 * @since 1.0
			 */
			tabbyTabs:function(e){
				
			/*	$('.yt-tabby-tabs-content').find('>*:first').addClass('active');
				$('.yt-tabby-tabs-header ul li:first').addClass('active');*/
				
				e.preventDefault();
				
				var $el = $(this),
					wrapper = $el.closest('.yt-tabby-tabs-header'),
					position = wrapper.hasClass('yt-tabby-tabs-header-bottom') ? 'bottom' : 'top',
					tabContent = wrapper.siblings('.yt-tabby-tabs-content'),
					index = $el.index();
				
				if( tabContent.find('>*[data-index]').length ){
					$el.addClass('active').siblings().removeClass('active');
					tabContent.find('>*[data-index="' + index + '"]').fadeIn(200,function(){
						//console.log('tag triggered');
					}).addClass('active').siblings().hide().removeClass('active');

				}else{
					if( tabContent.find('>*').eq(index).length ){
						$el.addClass('active').siblings().removeClass('active');
						tabContent.find('>*').eq(index).fadeIn(200).addClass('active').siblings().hide().removeClass('active');
					}
				}
			},
			
			
			
		},

		/**
		 * Bootstrap
		 * @since 1.0
		 */
		bootstrap:{
			accordion: function(){

				$('.yt-vc-accordion').each(function(){
					var $el = $(this),
						dataSettings = $el.data('settings'),
						activeTab = typeof dataSettings.active_tab !== 'undefined' && parseInt( dataSettings.active_tab ) ? parseInt( dataSettings.active_tab ) : 0;

					/**
					 * Active tab
					 */
					if( activeTab ){
						var activePanel = $el.find( '.panel:eq(' + ( activeTab - 1 ) + ')' );
						activePanel.addClass('active');
						activePanel.find( '.panel-collapse:not(.in)' ).collapse('show');
					}

					/**
					 * Click trigger
					 */
					$( '.panel-heading', $el ).on('click.bs.collapse.data-api', function(e){
						
						e.preventDefault();
						
						var $this   = $(this),
							$target = $this.next(),
							data    = $target.data('bs.collapse'),
							option  = typeof dataSettings.collapsible !== 'undefined' && 'yes' == dataSettings.collapsible ? 'toggle' : 'show';

					    $this.closest('.panel').addClass('active').siblings().removeClass('active');

					    $this.parent().siblings().find('.panel-collapse.in').collapse('hide');

						$target.collapse(option);

					});
				});

					

			}
		},

		/**
		 * Widgets
		 * @since 1.0
		 */
		widgets:{

			mailchimpSubscription: function(e){
				e.preventDefault();

				if( Yeahthemes.Framework._eventRunning )
					return;

				var $el = $(this).closest('.yt-mailchimp-subscription-form-content'),
					nonce = $el.find('[name="yt_mailchimp_subscribe_nonce"]').val(),
					list = $el.find('[name="yt_mailchimp_subscribe_list"]').val(),
					check = $el.find('[name="yt_mailchimp_subscribe_check"]').val(),
					email = $el.find('[name="yt_mailchimp_subscribe_email"]').val(),
					fname = $el.find('[name="yt_mailchimp_subscribe_fname"]').length ? $el.find('[name="yt_mailchimp_subscribe_fname"]').val() : '',
					lname = $el.find('[name="yt_mailchimp_subscribe_lname"]').length ? $el.find('[name="yt_mailchimp_subscribe_lname"]').val() : '',
					result = $el.find('.yt-mailchimp-subscription-result');
				
				$el.addClass('yt-loading');
				Yeahthemes.Framework._eventRunning 	= true;
				result.fadeOut().html('');

				$.ajax({
					type: 'POST',
					url: Yeahthemes._vars.ajaxurl,
					data: {
						action: 'yt-mailchimp-add-member',
						nonce: nonce,
						email: email,
						fname: fname,
						lname: lname,
						list: list,
						checking: check
					},
					success: function(responses){
						Yeahthemes.Framework._eventRunning 	= false;
						$el.removeClass('yt-loading');
						//console.log(responses);
						result.html(responses).fadeIn();
						setTimeout( function(){
							result.fadeOut();
						}, 10000 );
					},
				});
			}
		},
		/**
		 * Helpers
		 * @since 1.0
		 */
		helpers: {

			/**
			 * Browser supports style
			 */
			thisBrowserSupportsStyle: function(style) {
				var vendors = ['Webkit', 'Moz', 'ms', 'O'];
				var num_vendors = vendors.length;
				var dummy_el = window.document.createElement('div');

				// First test the bare style without prefix
				if (dummy_el.style[style] !== undefined) {
					return true;
				}

				// Test the camel-cased vendor-prefixed styles
				style = style.replace(/./, function(first) {return first.toUpperCase();});
				for (var i = 0; i < num_vendors; i++) {
					var pfx_style = vendors[i] + style;
					// The browser will return an empty string if a style is supported but not present, and undefined if the style is not supported
					if (dummy_el.style[pfx_style] !== undefined) {
						return true;
					}
				}
				return false;
			},
			/**
			 * Is Valid email
			 */
			isValidEmailAddress: function (emailAddress) {
				var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    			return regex.test(emailAddress);
			},
			/**
			 * inViewport
			 */
			inViewport: function(_selectors, _extra){
				
				if(!_selectors.length)
					return;
				
				_extra = _extra || 0;
				var scrollTop = window.pageYOffset,
					docViewTop = scrollTop - _extra,
					docViewBottom = scrollTop + window.outerHeight,
					elemTop = _selectors.offset().top,
					elemBottom = ( elemTop + _selectors.outerHeight() ) - _extra;
					
					//console.log( elemTop + '-' + docViewTop);
				return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
			},
			/**
			 * equalHeight
			 */
			equalHeight: function( _selectors, _all, _breakpoint ){


				$( _selectors ).css('min-height', '');

				_breakpoint = parseInt( _breakpoint ) || 992;
				if( $(window).width() > _breakpoint ){
					
					if( _all ){
						$( _selectors ).siblings().css('min-height', '' );
					}
					
					_all = _all || false;
					
					var height = $( _selectors ).outerHeight();
					
					$( _selectors ).siblings().each(function(index, element) {
						
						var thisHeight = $(this).outerHeight();
						
						if( thisHeight > height ){
							height = thisHeight;
						}
					});
					
					setTimeout(function(){
						$( _selectors ).css('min-height', height + 'px' );
						
						if( _all ){
							$( _selectors ).siblings().css('min-height', height + 'px' );
						}
					},100);
				}
				
			},
			/**
			 * isTouch
			 */
			isTouch: function() {
				if( 'ontouchstart' in document.documentElement )
					return true;
				else
					return false;
			},
			/**
			 * isIOS
			 */
			isIOS: function() {
				if( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) )
					return true;
				else
					return false;
			},
			/**
			 * hasParentClass
			 */
			isMobile: function() {
				var check = false;
				(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
				return check;
			},
			isBrowser: function( _class ) {
				if( !_class )
					return false;
				if( $('body').hasClass( _class + '-browser' )){
					return true;
				}else{
					return false;
				}
			},
			/**
			 * hasParentClass
			 */
			hasParentClass: function( e, classname ) {
				if(e === document) return false;
				if( $(e).hasClass(classname ) ) {
					return true;
				}
				return e.parentNode && yt_hasParentClass( e.parentNode, classname );
			},

			windowAnimationFrame: function(){
				var lastTime = 0;
			    var vendors = ['ms', 'moz', 'webkit', 'o'];
			    for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
			        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
			        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']
			                || window[vendors[x]+'CancelRequestAnimationFrame'];
			    }

			    if (!window.requestAnimationFrame)
			        window.requestAnimationFrame = function(callback, element) {
			            var currTime = new Date().getTime();
			            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
			            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
			                    timeToCall);
			            lastTime = currTime + timeToCall;
			            return id;
			        };

			    if (!window.cancelAnimationFrame)
			        window.cancelAnimationFrame = function(id) {
			            clearTimeout(id);
			        };
			}
			
		}
	};	
	
	
	
	Yeahthemes.Framework.init();
	Yeahthemes.Elegance.init();
	
})(jQuery);