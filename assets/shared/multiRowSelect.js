let multiRowSelect = {};

multiRowSelect.LOCAL_STORAGE_PREFIX = 'mrs-';
multiRowSelect.INIT_DATA_ATTRIBUTE_NAME = 'multi-row-select';
multiRowSelect.ELEMENT_ID_DATA_ATTRIBUTE_NAME = 'multi-row-select-element-id';

multiRowSelect.init = () => {
    $(`[data-${multiRowSelect.INIT_DATA_ATTRIBUTE_NAME}]`).each((index, element) => {
        const $element = $(element);
        const collectionName = $element.data(multiRowSelect.INIT_DATA_ATTRIBUTE_NAME);
        const elementId = $element.data(multiRowSelect.ELEMENT_ID_DATA_ATTRIBUTE_NAME) || $element.prop('id');
        const elementStatus = $element.prop('checked');

        const elementStoredStatus = multiRowSelect.getStoredCollectionData(collectionName)[elementId];
        if (elementStoredStatus === undefined) {
            multiRowSelect.storeElementStatus(collectionName, elementId, elementStatus);
        } else {
            $element.prop('checked', elementStoredStatus);
        }

        $element.on('change', () => {
            const newElementStatus = $element.prop('checked');
            multiRowSelect.storeElementStatus(collectionName, elementId, newElementStatus);
        });
    });
}

multiRowSelect.storeElementStatus = (collectionName, elementId, elementStatus) => {
    let collectionData = multiRowSelect.getStoredCollectionData(collectionName);
    collectionData[elementId] = elementStatus;

    const collectionKeyName = `${multiRowSelect.LOCAL_STORAGE_PREFIX}${collectionName}`;
    window.localStorage.setItem(collectionKeyName, JSON.stringify(collectionData));
}

multiRowSelect.getStoredCollectionData = (collectionName) => {
    const collectionKeyName = `${multiRowSelect.LOCAL_STORAGE_PREFIX}${collectionName}`;
    let collectionData = window.localStorage.getItem(collectionKeyName);
    if (!collectionData) {
        collectionData = JSON.stringify({});
        window.localStorage.setItem(collectionKeyName, collectionData);
    }

    return JSON.parse(collectionData);
}

$(() => {
    multiRowSelect.init();
})

window.multiRowSelect = multiRowSelect;
