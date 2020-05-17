let modListCustomize = {};

modListCustomize.downloadMods = (modListId, optionalModsJson) => {
    window.location = Routing.generate('app_mod_list_public_download', {
        'id': modListId,
        'optionalModsJson': optionalModsJson ? JSON.stringify(optionalModsJson) : null,
    });
};

modListCustomize.downloadRequired = () => {
    const $element = $('[data-download-required]');
    const modListId = $element.data('download-required');

    modListCustomize.downloadMods(modListId);
};

modListCustomize.downloadOptional = () => {
    const $element = $('[data-download-optional]');
    const modListId = $element.data('download-optional');
    const optionalMods = multiRowSelect.getStoredCollectionData('optionalMods');

    modListCustomize.downloadMods(modListId, optionalMods);
};

$(() => {
    $('[data-download-required]').on('click', () => {
        modListCustomize.downloadRequired();
    })

    $('[data-download-optional]').on('click', () => {
        modListCustomize.downloadOptional();
    })
});

window.modListCustomize = modListCustomize;
