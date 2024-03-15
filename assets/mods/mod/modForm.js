let modForm = {};

// Consts
modForm.TYPE_SERVER_SIDE = 'server_side';
modForm.TYPE_REQUIRED = 'required';
modForm.TYPE_OPTIONAL = 'optional';
modForm.TYPE_CLIENT_SIDE = 'client_side';
modForm.SOURCE_STEAM_WORKSHOP = 'steam_workshop';
modForm.SOURCE_DIRECTORY = 'directory';

modForm.onModSourceChange = () => {
    const $source = $('#mod_form_source');
    const $url = $('#mod_form_url');
    const $directory = $('#mod_form_directory');
    const $type = $('#mod_form_type');

    if ($source.val() === modForm.SOURCE_STEAM_WORKSHOP) {
        $directory.val('');
        $type.prop('selectedIndex', 0);
    } else if ($source.val() === modForm.SOURCE_DIRECTORY) {
        $url.val('');
        $type.val(modForm.TYPE_SERVER_SIDE);
    }

    $url.closest('.form-group').toggleClass('d-none', $source.val() !== modForm.SOURCE_STEAM_WORKSHOP);
    $type.prop('disabled', $source.val() !== modForm.SOURCE_STEAM_WORKSHOP);
    $directory.closest('.form-group').toggleClass('d-none', $source.val() !== modForm.SOURCE_DIRECTORY);
};

$(() => {
    modForm.onModSourceChange();
    $('#mod_form_source').on('change', () => {
        modForm.onModSourceChange();
    });
});

window.modForm = modForm;
