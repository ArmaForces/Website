let modForm = {};

// Consts
modForm.TYPE_SERVER_SIDE = 'server_side';
modForm.TYPE_REQUIRED = 'required';
modForm.TYPE_OPTIONAL = 'optional';
modForm.TYPE_CLIENT_SIDE = 'client_side';
modForm.SOURCE_STEAM_WORKSHOP = 'steam_workshop';
modForm.SOURCE_DIRECTORY = 'directory';

modForm.onModTypeChange = () => {
    const $type = $('#mod_form_type');
    const $source = $('#mod_form_source');

    if ($type.val() === modForm.TYPE_SERVER_SIDE) {
        $source.prop('disabled', false);
    } else {
        $source.val(modForm.SOURCE_STEAM_WORKSHOP);
        $source.prop('disabled', true);
        modForm.onModSourceChange();
    }
};

modForm.onModSourceChange = () => {
    const $source = $('#mod_form_source');
    const $url = $('#mod_form_url');
    const $path = $('#mod_form_path');

    if ($source.val() === modForm.SOURCE_STEAM_WORKSHOP) {
        $path.val('');
        $path.closest('.form-group').hide();
        $url.closest('.form-group').show();
    } else if ($source.val() === modForm.SOURCE_DIRECTORY) {
        $url.val('');
        $url.closest('.form-group').hide();
        $path.closest('.form-group').show();
    }
};

$(() => {
    modForm.onModTypeChange();
    $('#mod_form_type').on('change', (e) => {
        modForm.onModTypeChange();
    });

    modForm.onModSourceChange();
    $('#mod_form_source').on('change', (e) => {
        modForm.onModSourceChange();
    });
});

window.modForm = modForm;
