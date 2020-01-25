let modForm = {};

// Consts
modForm.USED_BY_CLIENT = 'client';
modForm.USED_BY_SERVER = 'server';
modForm.TYPE_REQUIRED = 'required';
modForm.TYPE_OPTIONAL = 'optional';
modForm.SOURCE_STEAM_WORKSHOP = 'steam_workshop';
modForm.SOURCE_DIRECTORY = 'directory';

modForm.setUsedBy = () => {
  	const $usedBy = $('#mod_form_usedBy');
    const $type = $('#mod_form_type');
    const $source = $('#mod_form_source');

  	const currentValue = $usedBy.val();

    if (currentValue === modForm.USED_BY_CLIENT) {
        $source.val(modForm.SOURCE_STEAM_WORKSHOP);
        $source.prop('disabled', true);
        $type.prop('disabled', false);
    } else if (currentValue === modForm.USED_BY_SERVER) {
        $type.val(modForm.TYPE_REQUIRED)
        $type.prop('disabled', true);
        $source.prop('disabled', false);
    }
};

$(() => {
    modForm.setUsedBy();
    $('#mod_form_usedBy').on('change', (e) => {
        modForm.setUsedBy(this);
    });

    $('#mod_form_source').on('change', (e) => {
        $('#mod_form_path').val('');
    });
});

window.modForm = modForm;
