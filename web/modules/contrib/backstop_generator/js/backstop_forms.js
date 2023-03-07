(function ($, Drupal) {
  Drupal.behaviors.backstopForms = {
    attach: function (context, settings) {
      let defaults = document.querySelector('#edit-use-defaults') ?? document.querySelector('#edit-use-globals');
      let advanced_settings = document.querySelectorAll('.advanced-setting');

      defaults.addEventListener('change', (e) => {
        advanced_settings.forEach((field) => {
          if (!defaults.checked) {
            field.removeAttribute('readonly');
            if (field.hasAttribute('type') && field.getAttribute('type') === 'checkbox') {
              field.removeAttribute('disabled');
              field.closest('.form-item').classList.remove('form-disabled');
            }
            return;
          }
          field.setAttribute('readonly', 'true');
          if (field.hasAttribute('type') && field.getAttribute('type') === 'checkbox') {
            field.setAttribute('disabled', true);
            field.closest('.form-item').classList.add('form-disabled');
          }
        })
      })
    }
  }
})(jQuery, Drupal);
