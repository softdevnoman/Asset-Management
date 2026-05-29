// Bootstrap compiled file for global jQuery, Axios, and compatibility wrappers
// Replicates the asset handling patterns from OnsiteV2

(function() {
  function initBootstrap() {
    // Wait for jQuery to be available
    if (typeof jQuery === 'undefined') {
      setTimeout(initBootstrap, 50);
      return;
    }

    // Set up axios if available
    if (typeof axios !== 'undefined') {
      window.axios = axios;
      window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }

    // Make jQuery globally available
    window.jQuery = window.$ = jQuery;

    // Initialize modal wrapper for compatibility with Bootstrap 5
    initModalWrapper();
  }

  function initModalWrapper() {
    // jQuery Bootstrap 5 Modal Plugin - simple wrapper for .modal() syntax
    if (typeof jQuery !== 'undefined') {
      jQuery.fn.modal = function(action) {
        if (!window.bootstrap?.Modal) return this;
        return this.each(function() {
          const modal = window.bootstrap.Modal.getInstance(this) || new window.bootstrap.Modal(this);
          if (action === 'show') modal.show();
          else if (action === 'hide') modal.hide();
          else if (action === 'toggle') modal.toggle();
        });
      };
    }
  }

  // Start initialization
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initBootstrap);
  } else {
    initBootstrap();
  }
})();
