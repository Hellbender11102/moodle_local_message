/**
 *  Show a delete message modal instead of doing it in a seperated page
 *  @module local_message
 */

define(['jquery', 'core/modal_factory', 'core/str', "core/modal_events", 'core/ajax', 'core/notification'],
    function ($, ModalFactory, String, ModalEvents, Ajax, Notification) {
        var trigger = $('.local_message_delete_button');
        ModalFactory.create({
            type: ModalFactory.types.SAVE_CANCEL,
            title: String.get_string('delete_title', 'local_message'),
            body: String.get_string('delete_message', 'local_message'),
            large: true,
            // Get id before modal is displayed
            preShowCallback: function (triggerElement, modal) {
                triggerElement = $(triggerElement);

                let classString = triggerElement[0].classList[0];
                let messageid = classString.substr(classString.lastIndexOf('local_messageid') + 'local_messageid'.length);

                modal.params = {'messageid': messageid};
                modal.setSaveButtonText(String.get_string('delete_button', 'local_message'));
            },
        }, trigger)
            .done(function(modal) {
                modal.getRoot().on(ModalEvents.save, function(e) {
                    let footer = Y.one('.modal-footer');
                    footer.setContent('Deleting');
                    let spinner = M.util.add_spinner(Y,footer);
                    spinner.show();
                    e.preventDefault();
                    Y.log(modal.params);
                    let request = {
                        methodname: 'local_message_delete_message',
                        args: modal.params,
                    };

                    Ajax.call([request])[0].done(function(data) {
                        if (data === true) {
                            window.location.reload();
                            Y.log('deleted message ' + modal.params);
                        } else {
                            Notification.addNotification({
                                message: String.get_string('delete_message_failed', 'local_message'),
                                type: 'error'
                            });
                        }
                    }).fail(Notification.exception);

                });
            });
    });