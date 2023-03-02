(function ($, Drupal) {
  Drupal.behaviors.backstopForms = {
    attach: function (context, settings) {
      let defaults = document.querySelector('#edit-use-defaults');
      let advanced_settings = document.querySelectorAll('.advanced-setting');

      defaults.addEventListener('change', (e) => {
        advanced_settings.forEach((field) => {
          if (defaults.checked) {
            field.setAttribute('disabled', true);
            field.closest('.form-item').classList.add('form-disabled');
            return;
          }
          field.removeAttribute('disabled');
          field.closest('.form-item').classList.remove('form-disabled');
        });
      });
    }
  }
})(jQuery, Drupal);
