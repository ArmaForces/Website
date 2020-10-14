
export const createConfirmModal = (content, confirmCallback, cancelCallback) => {
    let template = $('#modal-confirm-template').html();
    template = template.replace('__content__', content);
    let $modal = $(template).modal({
        'backdrop': 'static',
        'keyboard': false,
    });

    $modal.find('#modal-confirm-button-cancel').on('click', (e) => {
        if (cancelCallback) {
            cancelCallback(e);
        }
    });

    $modal.find('#modal-confirm-button-confirm').on('click', (e) => {
        if (confirmCallback) {
            confirmCallback(e);
        }
    });
};

export const initConfirmModals = () => {
    $('[data-modal-confirm]').on('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const $element = $(e.currentTarget);
        const message = $element.data('modal-confirm');
        const url = $element.prop('href');

        createConfirmModal(message, () => {
            window.location = url;
        });
    });
};

export const createMissionModal = (title, content) => {
    let template = $('#modal-mission-template').html();
    template = template.replace('__content__', content);
    template = template.replace('__title__', title);

    let $modal = $(template).modal({
        'backdrop': 'static',
        'keyboard': false,
    });
};

export const initMissionModals = () => {
    $('[data-mission-widget]').on('click', '[data-mission-title]', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const $mission = jQuery(e.delegateTarget);
        const title = $mission.find('[data-mission-title]').text();
        const description = $mission.find('[data-mission-description]').html();

        createMissionModal(title, description)
    });
};
