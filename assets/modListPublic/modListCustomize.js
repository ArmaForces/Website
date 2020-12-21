let modListCustomize = {};

modListCustomize.downloadMods = (modListName, optionalModsJson) => {
    window.location = Routing.generate('app_mod_list_public_download', {
        'name': modListName,
        'optionalModsJson': optionalModsJson ? JSON.stringify(optionalModsJson) : null,
    });
};

modListCustomize.downloadRequired = () => {
    const $element = $('[data-download-required]');
    const modListName = $element.data('download-required');

    modListCustomize.downloadMods(modListName);
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
