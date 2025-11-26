(function ($, window, document) {
    'use strict';

    const ColumnVisibility = {
        toggle($wrapper, columnIndex, isVisible) {
            if (!$wrapper || !$wrapper.length) {
                return;
            }

            const index = parseInt(columnIndex, 10);
            if (Number.isNaN(index)) {
                return;
            }

            const display = isVisible ? '' : 'none';
            $wrapper.find(`th[data-column="${index}"]`).css('display', display);
            $wrapper.find('tbody tr').each(function () {
                $(this).find('td').eq(index).css('display', display);
            });
        }
    };

    function FilterModule($form, toolkit) {
        this.$form = $form;
        this.toolkit = toolkit;
        this.module = $form.data('module') || 'global';
        this.wrapperSelector = $form.data('table-wrapper');
        this.$wrapper = this.wrapperSelector ? $(this.wrapperSelector) : $form.closest('.card').find('.table-wrapper').first();

        if (!this.$wrapper.length) {
            this.$wrapper = $('.table-wrapper').first();
        }

        this.enableAjax = $form.data('ajax') !== false && this.$wrapper.length > 0;
        this.storageKey = $form.data('columnStorageKey') || `${this.module}_table_columns_visibility`;
        this.savedSettings = this.getSavedSettings();

        this.init();
    }

    FilterModule.prototype.init = function () {
        if (this.enableAjax) {
            this.applyColumnVisibility();
            this.bindAjaxEvents();
        } else {
            this.bindNonAjaxEvents();
        }
    };

    FilterModule.prototype.getSavedSettings = function () {
        try {
            return JSON.parse(localStorage.getItem(this.storageKey)) || {};
        } catch (error) {
            console.error('Failed to parse column visibility settings:', error);
            return {};
        }
    };

    FilterModule.prototype.saveSettings = function () {
        localStorage.setItem(this.storageKey, JSON.stringify(this.savedSettings));
    };

    FilterModule.prototype.applyColumnVisibility = function () {
        const self = this;
        this.$form.find('.toggle-column').each(function () {
            const $checkbox = $(this);
            const columnIndex = $checkbox.data('column');
            const defaultVisible = true;
            const isVisible = Object.prototype.hasOwnProperty.call(self.savedSettings, columnIndex)
                ? self.savedSettings[columnIndex]
                : defaultVisible;
            $checkbox.prop('checked', isVisible);
            ColumnVisibility.toggle(self.$wrapper, columnIndex, isVisible);
        });
    };

    FilterModule.prototype.bindAjaxEvents = function () {
        const self = this;

        this.$form.on('submit', function (event) {
            event.preventDefault();
            self.ajaxLoad(self.$form.attr('action'));
        });

        this.$form.on('change', '.toggle-column', function () {
            const $checkbox = $(this);
            const columnIndex = $checkbox.data('column');
            const isVisible = $checkbox.is(':checked');
            self.savedSettings[columnIndex] = isVisible;
            self.saveSettings();
            ColumnVisibility.toggle(self.$wrapper, columnIndex, isVisible);
        });

        this.$form.on('change', '.perPageSelect', function () {
            const value = $(this).val();
            self.ajaxLoad(self.$form.attr('action'), { per_page: value, page: 1 });
        });

        this.$form.on('change', '.filterAutoSubmit', function () {
            const fieldName = $(this).attr('name');
            const overrides = { page: 1 };
            if (fieldName) {
                overrides[fieldName] = $(this).val();
            }
            self.ajaxLoad(self.$form.attr('action'), overrides);
        });

        const searchInputs = this.$form.find('input[name="search"]');
        if (searchInputs.length) {
            const handler = this.toolkit.debounce(function (event) {
                const value = $(event.currentTarget).val();
                self.ajaxLoad(self.$form.attr('action'), { search: value, page: 1 });
            }, 400);

            searchInputs.on('keyup', handler);
        }

        this.$wrapper.on('click', '.pagination a', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                self.ajaxLoad(url, {}, true);
            }
        });
    };

    FilterModule.prototype.bindNonAjaxEvents = function () {
        const self = this;
        this.$form.on('change', '.perPageSelect', function () {
            self.$form.trigger('submit');
        });

        const searchInputs = this.$form.find('input[name="search"]');
        if (searchInputs.length) {
            const handler = this.toolkit.debounce(function () {
                self.$form.trigger('submit');
            }, 400);
            searchInputs.on('keyup', handler);
        }
    };

    FilterModule.prototype.serialize = function (overrides = {}) {
        const payload = {};
        this.$form.serializeArray().forEach(({ name, value }) => {
            payload[name] = value;
        });
        return Object.assign(payload, overrides);
    };

    FilterModule.prototype.ajaxLoad = function (url, overrides = {}, skipSerialize = false) {
        if (!this.enableAjax) {
            this.$form.trigger('submit');
            return;
        }

        const requestUrl = url || this.$form.attr('action');
        const data = skipSerialize ? undefined : this.serialize(overrides);

        this.toolkit.ajaxLoad(this.$wrapper, requestUrl, data, () => {
            this.applyColumnVisibility();
        });
    };

    FilterModule.prototype.refresh = function () {
        this.ajaxLoad(this.$form.attr('action'));
    };

    const AdminToolkit = {
        filterControllers: {},
        confirmTextsCache: null,

        init() {
            this.registerFilterForms();
            this.bindPhotoUploads();
            this.bindDeleteButtons();
            this.bindStatusToggles();
            this.bindFilterVisibilityToggle();
            this.registerGlobalAjaxFunction();
        },

        dataset(key, fallback) {
            const dataset = document.body ? document.body.dataset : {};
            return Object.prototype.hasOwnProperty.call(dataset, key) ? dataset[key] : fallback;
        },

        getConfirmTexts() {
            if (this.confirmTextsCache) {
                return this.confirmTextsCache;
            }

            this.confirmTextsCache = {
                title: this.dataset('deleteConfirmTitle', 'Are you sure?'),
                text: this.dataset('deleteConfirmText', 'This record will be permanently deleted!'),
                confirm: this.dataset('deleteConfirmConfirm', 'Yes, delete it!'),
                cancel: this.dataset('deleteConfirmCancel', 'No, cancel!')
            };

            return this.confirmTextsCache;
        },

        registerFilterForms() {
            const self = this;
            this.filterControllers = {};
            $('.filterForm').each(function () {
                const controller = new FilterModule($(this), self);
                self.addFilterController(controller.module, controller);
            });
        },

        addFilterController(moduleName, controller) {
            if (!moduleName || !controller) {
                return;
            }

            if (!this.filterControllers[moduleName]) {
                this.filterControllers[moduleName] = [];
            }
            this.filterControllers[moduleName].push(controller);
        },

        getControllers(moduleName) {
            if (!moduleName) {
                return [];
            }

            return this.filterControllers[moduleName] || [];
        },

        getDefaultController() {
            const modules = Object.keys(this.filterControllers);
            if (!modules.length) {
                return null;
            }

            const controllers = this.filterControllers[modules[0]];
            return controllers && controllers.length ? controllers[0] : null;
        },

        refreshModule(moduleName) {
            const controllers = this.getControllers(moduleName);
            controllers.forEach((controller) => controller.refresh());
        },

        bindPhotoUploads() {
            $(document).on('click', '.photo-upload-btn, .photo-box', function (event) {
                if ($(event.target).is('.photo-input')) {
                    return;
                }

                const $container = $(this).closest('.form-group');
                const $input = $container.find('.photo-input').first();

                if (!$input.length) {
                    return;
                }

                event.preventDefault();
                $input.trigger('click');
            });

            $(document).on('change', '.photo-input', function (event) {
                const input = event.currentTarget;
                if (!input.files || !input.files.length) {
                    return;
                }

                const file = input.files[0];
                if (!file.type || !file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (loadEvent) {
                    const $container = $(input).closest('.form-group');
                    const $preview = $container.find('.photo-preview');
                    $preview.attr('src', loadEvent.target.result).css({
                        width: '100%',
                        height: '100%',
                        objectFit: 'cover',
                        opacity: 1
                    });
                };
                reader.readAsDataURL(file);
            });
        },

        bindDeleteButtons() {
            const self = this;
            $(document).off('click.adminToolkitDelete')
                .on('click.adminToolkitDelete', '.btn-delete, .btn-delete-user, [data-delete="record"]', function (event) {
                    event.preventDefault();
                    const $button = $(this);
                    self.handleDelete($button);
                });
        },

        handleDelete($button) {
            const url = $button.data('href') || $button.attr('href');
            if (!url) {
                return;
            }

            const confirmTexts = this.getConfirmTexts();
            const swalInstance = window.Swal ? window.Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-2',
                    cancelButton: 'btn btn-danger mx-2'
                },
                buttonsStyling: false
            }) : null;

            const executeDelete = () => {
                const formSelector = $button.data('form');
                let payload = {};
                let method = ($button.data('method') || 'DELETE').toUpperCase();
                let requestType = $button.data('type') || 'POST';

                if (formSelector && $(formSelector).length) {
                    payload = $(formSelector).serialize();
                } else {
                    const token = this.csrfToken();
                    if (token) {
                        payload._token = token;
                    }

                    if (method !== 'POST') {
                        payload._method = method;
                        requestType = 'POST';
                    }
                }

                $.ajax({
                    type: requestType,
                    url: url,
                    data: payload,
                    success: (response) => {
                        if (response && response.success) {
                            this.toastSuccess(response.msg || 'Deleted successfully.');
                            const moduleName = $button.data('refreshModule');
                            if (moduleName) {
                                this.refreshModule(moduleName);
                            }

                            const removeTarget = $button.data('removeTarget');
                            if (removeTarget) {
                                $(removeTarget).fadeOut(300, function () {
                                    $(this).remove();
                                });
                            }
                        } else {
                            this.toastError((response && response.msg) || this.dataset('userDeleteError', 'Unable to delete record.'));
                        }
                    },
                    error: () => {
                        this.toastError(this.dataset('userDeleteError', 'Unable to delete record.'));
                    }
                });
            };

            if (swalInstance) {
                swalInstance.fire({
                    title: confirmTexts.title,
                    text: confirmTexts.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmTexts.confirm,
                    cancelButtonText: confirmTexts.cancel,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeDelete();
                    }
                });
            } else if (window.confirm(confirmTexts.text)) {
                executeDelete();
            }
        },

        bindStatusToggles() {
            const self = this;
            $(document).on('change', '.status-toggle, input.status', function () {
                const $toggle = $(this);
                const url = $toggle.data('href');
                if (!url) {
                    return;
                }

                const newState = $toggle.is(':checked');
                const payload = {
                    status: newState ? 1 : 0
                };
                const id = $toggle.data('id');
                if (typeof id !== 'undefined') {
                    payload.id = id;
                }

                const token = self.csrfToken();
                if (token) {
                    payload._token = token;
                }

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: payload,
                    success: (response) => {
                        if (response && response.success) {
                            self.toastSuccess(response.msg || 'Status updated.');
                        } else {
                            self.toastError((response && response.msg) || 'Failed to update status.');
                            $toggle.prop('checked', !newState);
                        }
                    },
                    error: () => {
                        self.toastError('Failed to update status.');
                        $toggle.prop('checked', !newState);
                    }
                });
            });
        },

        bindFilterVisibilityToggle() {
            const $section = $('.section-filter');
            if (!$section.length) {
                return;
            }

            const storageKey = $section.first().data('filterStorage') || 'filterPanelOpen';
            const $trigger = $('.open-filter');
            const $printCard = $('.print-card');
            const isOpen = localStorage.getItem(storageKey) === 'true';

            $section.toggle(isOpen);
            if ($printCard.length) {
                $printCard.toggle(!isOpen);
            }

            $trigger.on('click', function () {
                const currentlyOpen = $section.is(':visible');
                $section.slideToggle(300);
                if ($printCard.length) {
                    $printCard.slideToggle(300);
                }
                localStorage.setItem(storageKey, (!currentlyOpen).toString());
            });
        },

        ajaxLoad($wrapper, url, data, onSuccess, errorMessage) {
            if (!$wrapper || !$wrapper.length || !url) {
                return;
            }

            this.showLoader($wrapper);

            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                success: (response) => {
                    $wrapper.html(response);
                    if (typeof onSuccess === 'function') {
                        onSuccess(response);
                    }
                },
                error: () => {
                    this.toastError(errorMessage || this.dataset('userLoadingError', 'Failed to load records.'));
                },
                complete: () => {
                    this.hideLoader($wrapper);
                }
            });
        },

        showLoader($wrapper) {
            if (!$wrapper.css('position') || $wrapper.css('position') === 'static') {
                $wrapper.css('position', 'relative');
            }

            $wrapper.find('.loading-overlay').remove();
            $wrapper.append(`
                <div class="loading-overlay">
                    <div class="spinner-border text-info" role="status" style="width:3rem;height:3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
        },

        hideLoader($wrapper) {
            $wrapper.find('.loading-overlay').fadeOut(200, function () {
                $(this).remove();
            });
        },

        toastSuccess(message) {
            if (!message) {
                return;
            }

            if (window.toastr) {
                window.toastr.success(message);
            } else {
                window.console.log(message);
            }
        },

        toastError(message) {
            if (!message) {
                return;
            }

            if (window.toastr) {
                window.toastr.error(message);
            } else {
                window.console.error(message);
            }
        },

        csrfToken() {
            return $('meta[name="csrf-token"]').attr('content');
        },

        debounce(fn, delay) {
            let timer;
            return function (...args) {
                const context = this;
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(context, args), delay);
            };
        },

        registerGlobalAjaxFunction() {
            const self = this;
            window.ajaxLoad = function (url, data = {}, third = null, fourth = null) {
                let moduleName = null;
                let skipSerialize = false;

                if (typeof third === 'string') {
                    moduleName = third;
                    skipSerialize = Boolean(fourth);
                } else if (typeof third === 'boolean') {
                    skipSerialize = third;
                    if (typeof fourth === 'string') {
                        moduleName = fourth;
                    }
                }

                if (moduleName) {
                    const controllers = self.getControllers(moduleName);
                    if (controllers.length) {
                        controllers.forEach((controller) => controller.ajaxLoad(url, data || {}, skipSerialize));
                        return;
                    }
                }

                const defaultController = self.getDefaultController();
                if (defaultController) {
                    defaultController.ajaxLoad(url, data || {}, skipSerialize);
                    return;
                }

                const $fallbackWrapper = $('.table-wrapper').first();
                if ($fallbackWrapper.length) {
                    self.ajaxLoad($fallbackWrapper, url, data);
                }
            };
        }
    };

    $(function () {
        AdminToolkit.init();
    });

    window.AdminToolkit = AdminToolkit;
})(window.jQuery, window, document);
