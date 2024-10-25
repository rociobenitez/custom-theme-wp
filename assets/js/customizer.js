/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
  wp.customize("body_font", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--body-font", newval);
    });
  });
  wp.customize("title_font", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--title-font", newval);
    });
  });
  wp.customize("tagline_font", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--tagline-font", newval);
    });
  });
  wp.customize("body_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--body_font_size", newval);
    });
  });
  wp.customize("h1_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--h1_font_size", newval);
    });
  });
  wp.customize("h2_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--h2_font_size", newval);
    });
  });
  wp.customize("h3_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--h3_font_size", newval);
    });
  });
  wp.customize("h4_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--h4_font_size", newval);
    });
  });
  wp.customize("h5_font_size", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--h5_font_size", newval);
    });
  });
  wp.customize("primary_color", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--primary", newval);
    });
  });
  wp.customize("secondary_color", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--secondary", newval);
    });
  });
  wp.customize("background_color", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--background-color", newval);
    });
  });
  wp.customize("body_color", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--body-color", newval);
    });
  });
  wp.customize("heading_color", function (value) {
    value.bind(function (newval) {
      document.documentElement.style.setProperty("--heading-color", newval);
    });
  });
})(jQuery);
